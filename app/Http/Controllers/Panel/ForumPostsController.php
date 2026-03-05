<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumTopic;
use App\Models\ForumTopicBookmark;
use App\Models\ForumTopicPost;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ForumPostsController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize("panel_forums_my_posts");

        if (getForumsGeneralSettings('forums_status')) {
            $user = auth()->user();

            $query = ForumTopicPost::query()->where('user_id', $user->id);

            //$copyQuery = deepClone($query);
            $query = $this->handleFilters($request, $query);
            $getListData = $this->getListsData($request, $query, $user);

            if ($request->ajax()) {
                return $getListData;
            }

            $forums = Forum::orderBy('order', 'asc')
                ->whereNull('parent_id')
                ->where('status', 'active')
                ->with([
                    'subForums' => function ($query) {
                        $query->where('status', 'active');
                    }
                ])->get();

            $data = [
                'pageTitle' => trans('site.posts'),
                'forums' => $forums,
            ];
            $data = array_merge($data, $getListData);

            return view('design_1.panel.forum.posts.index', $data);
        }

        abort(403);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $forumId = $request->get('forum_id');
        $status = $request->get('status');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($forumId) and $forumId != 'all') {
            $query->whereHas('topic', function ($query) use ($forumId) {
                $query->where('forum_id', $forumId);
            });
        }

        if ($status and $status !== 'all') {
            $query->whereHas('topic', function ($query) use ($status) {
                if ($status == 'closed') {
                    $query->where('close', true);
                } else {
                    $query->where('close', false);
                }
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query;
    }

    private function getListsData(Request $request, Builder $query, $user)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $posts = $query
            ->with([
                'topic' => function ($query) {
                    $query->with([
                        'creator' => function ($query) {
                            $query->select('id', 'full_name', 'avatar', 'avatar_settings');
                        },
                        'forum' => function ($query) {
                            $query->select('id', 'slug');
                        }
                    ]);
                },
                'user' => function ($query) {
                    $query->select('id', 'full_name', 'avatar', 'avatar_settings');
                },
            ])
            ->get();

        foreach ($posts as $post) {
            $post->replies_count = ForumTopicPost::where('parent_id', $post->id)->count();
        }


        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $posts, $total, $count);
        }

        return [
            'posts' => $posts,
            'pagination' => $this->makePagination($request, $posts, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $posts, $total, $count)
    {
        $html = "";

        foreach ($posts as $postRow) {
            $html .= (string)view()->make('design_1.panel.forum.posts.table_items', ['post' => $postRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $posts, $total, $count, true)
        ]);
    }

}
