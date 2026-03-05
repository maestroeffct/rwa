<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Event;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MyEventsCommentsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Comment::where('status', 'active')
            ->whereHas('event', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            });

        $copyQuery = deepClone($query);
        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $totalCommentsCount = deepClone($copyQuery)->count();
        $totalReportsCommentsCount = CommentReport::whereIn('comment_id', deepClone($copyQuery)->pluck('id')->toArray())->count();
        $repliedCommentsCount = deepClone($copyQuery)->whereNotNull('reply_id')->count();


        $myEvents = Event::query()->select('id', 'creator_id')
            ->where('creator_id', $user->id)
            ->get();


        $selectedEventId = $request->get('event_id');
        $selectedEvent = !empty($selectedEventId) ? $myEvents->where('id', $selectedEventId)->first() : null;


        $data = [
            'pageTitle' => trans('update.event_comments'),
            'repliedCommentsCount' => $repliedCommentsCount,
            'myEvents' => $myEvents,
            'selectedEvent' => $selectedEvent,
            'totalCommentsCount' => $totalCommentsCount,
            'totalReportsCommentsCount' => $totalReportsCommentsCount,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.events.comments.index', $data);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $user = $request->get('user');
        $event = $request->get('event');
        $filter_new_comments = $request->get('new_comments');
        $event_id = $request->get('event_id');

        fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($user)) {
            $usersIds = User::where('full_name', 'like', "%$user%")->pluck('id')->toArray();

            $query->whereIn('user_id', $usersIds);
        }

        if (!empty($event_id)) {
            $query->where('event_id', $event_id);
        }

        if (!empty($event)) {
            $eventsIds = Webinar::whereTranslationLike('title', "%$event%")->pluck('id')->toArray();

            $query->whereIn('event_id', $eventsIds);
        }

        if (!empty($filter_new_comments) and $filter_new_comments == 'on') {

        }

        $query->orderBy('created_at', 'desc');

        return $query;
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $comments = $query
            ->with([
                'event' => function ($query) {
                    $query->select('id', 'slug');
                },
                'user' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
                'replies'
            ])
            ->get();

        foreach ($comments->whereNull('viewed_at') as $comment) {
            $comment->update([
                'viewed_at' => time()
            ]);
        }

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $comments, $total, $count);
        }

        return [
            'comments' => $comments,
            'pagination' => $this->makePagination($request, $comments, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $comments, $total, $count)
    {
        $html = "";

        foreach ($comments as $commentRow) {
            $html .= (string)view()->make('design_1.panel.events.comments.table_items', ['comment' => $commentRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $comments, $total, $count, true)
        ]);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'comment' => 'required',
        ]);

        $user = auth()->user();

        $comment = Comment::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($comment)) {
            $comment->update([
                'comment' => $request->input('comment'),
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg' => trans('product.comment_success_store')
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();

        $comment = Comment::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($comment)) {
            $comment->delete();
        }

        return response()->json([
            'code' => 200
        ], 200);
    }

    public function reply(Request $request, $id)
    {
        $this->validate($request, [
            'comment' => 'required|string'
        ]);

        $user = auth()->user();

        $comment = Comment::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id);
                $query->orWhereHas('event', function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                });
            })
            ->first();

        if (!empty($comment)) {

            Comment::create([
                'user_id' => $user->id,
                'comment' => $request->get('comment'),
                'event_id' => $comment->event_id,
                'reply_id' => $comment->id,
                'status' => 'active',
                'created_at' => time()
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg' => trans('product.comment_success_store')
        ], 200);
    }

    public function report(Request $request, $id)
    {
        $this->validate($request, [
            'message' => 'required|string'
        ]);

        $data = $request->all();
        $user = auth()->user();

        $comment = Comment::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id);
                $query->orWhereHas('event', function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                });
            })
            ->first();

        if (!empty($comment)) {

            CommentReport::create([
                'event_id' => $comment->event_id,
                'user_id' => $user->id,
                'comment_id' => $comment->id,
                'message' => $data['message'],
                'created_at' => time()
            ]);

            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[content_type]' => trans('admin/main.comment')
            ];
            sendNotification("new_report_item_for_admin", $notifyOptions, 1);

            return response()->json([
                'code' => 200
            ], 200);
        }

        return response()->json([], 422);
    }
}
