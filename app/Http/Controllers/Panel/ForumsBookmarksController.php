<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumTopic;
use App\Models\ForumTopicBookmark;
use App\Models\ForumTopicPost;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ForumsBookmarksController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize("panel_forums_bookmarks");

        if (getForumsGeneralSettings('forums_status')) {
            $user = auth()->user();

            $topicsIds = ForumTopicBookmark::where('user_id', $user->id)->pluck('topic_id')->toArray();

            $query = ForumTopic::query()->whereIn('id', $topicsIds);

            $getListData = $this->getListsData($request, $query, $user);

            if ($request->ajax()) {
                return $getListData;
            }

            $data = [
                'pageTitle' => trans('update.forum_bookmark'),
            ];
            $data = array_merge($data, $getListData);

            return view('design_1.panel.forum.bookmarks.index', $data);
        }

        abort(403);
    }


    private function getListsData(Request $request, Builder $query, $user)
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
            ->orderBy('created_at', 'desc')
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
            $html .= (string)view()->make('design_1.panel.forum.bookmarks.table_items', ['topic' => $topicRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $topics, $total, $count, true)
        ]);
    }

}
