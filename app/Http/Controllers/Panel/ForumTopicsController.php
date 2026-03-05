<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumTopic;
use App\Models\ForumTopicBookmark;
use App\Models\ForumTopicPost;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ForumTopicsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_forums_my_topics");

        if (getForumsGeneralSettings('forums_status')) {
            $user = auth()->user();
            $query = ForumTopic::query()->where('creator_id', $user->id);

            $copyQuery = deepClone($query);
            $query = $this->handleFilters($request, $query);
            $getListData = $this->getListsData($request, $query);

            if ($request->ajax()) {
                return $getListData;
            }

            $publishedTopics = deepClone($copyQuery)->count();
            $lockedTopics = deepClone($copyQuery)->where('close', true)->count();

            $topicsIds = deepClone($copyQuery)->pluck('id')->toArray();
            $topicMessages = ForumTopicPost::whereIn('topic_id', $topicsIds)->count();

            $forums = Forum::orderBy('order', 'asc')
                ->whereNull('parent_id')
                ->where('status', 'active')
                ->with([
                    'subForums' => function ($query) {
                        $query->where('status', 'active');
                    }
                ])->get();

            $data = [
                'pageTitle' => trans('update.topics'),
                'forums' => $forums,
                'publishedTopicsCount' => $publishedTopics,
                'lockedTopicsCount' => $lockedTopics,
                'topicMessagesCount' => $topicMessages,
            ];
            $data = array_merge($data, $getListData);

            return view('design_1.panel.forum.topics.index', $data);
        }

        abort(403);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $search = $request->get('search');
        $forumId = $request->get('forum_id');
        $status = $request->get('status');
        $sort = $request->get('sort');

        if (!empty($search)) {
            $query->where(function (Builder $query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
                $query->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($forumId)) {
            $query->where('forum_id', $forumId);
        }

        if ($status) {
            if ($status == 'closed') {
                $query->where('close', true);
            } else {
                $query->where('close', false);
            }
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'create_date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'create_date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $topics = $query
            ->with([
                'forum'
            ])
            ->withCount([
                'posts'
            ])
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $topics, $total, $count);
        }

        return [
            'topics' => $topics,
            'pagination' => $this->makePagination($request, $topics, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $topics, $total, $count)
    {
        $html = "";

        foreach ($topics as $topicRow) {
            $html .= (string)view()->make('design_1.panel.forum.topics.table_items', ['topic' => $topicRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $topics, $total, $count, true)
        ]);
    }

    public function removeBookmarks($topicId)
    {
        if (getForumsGeneralSettings('forums_status')) {
            $user = auth()->user();

            $bookmark = ForumTopicBookmark::where('user_id', $user->id)
                ->where('topic_id', $topicId)
                ->first();

            if (!empty($bookmark)) {
                $bookmark->delete();
            }

            return response([
                'code' => 200
            ]);
        }

        abort(403);
    }

}
