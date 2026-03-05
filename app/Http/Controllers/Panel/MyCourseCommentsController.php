<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MyCourseCommentsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Comment::where('status', 'active')
            ->whereHas('webinar', function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                    $query->orWhere('teacher_id', $user->id);
                });
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


        $myCourses = Webinar::query()->select('id', 'teacher_id', 'creator_id')
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id);
                $query->orWhere('teacher_id', $user->id);
            })->get();


        $selectedCourseId = $request->get('course_id');
        $selectedCourse = !empty($selectedCourseId) ? $myCourses->where('id', $selectedCourseId)->first() : null;


        $data = [
            'pageTitle' => trans('panel.my_class_comments'),
            'repliedCommentsCount' => $repliedCommentsCount,
            'myCourses' => $myCourses,
            'selectedCourse' => $selectedCourse,
            'totalCommentsCount' => $totalCommentsCount,
            'totalReportsCommentsCount' => $totalReportsCommentsCount,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.webinars.comments.index', $data);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $user = $request->get('user');
        $webinar = $request->get('webinar');
        $filter_new_comments = $request->get('new_comments');
        $course_id = $request->get('course_id');

        fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($user)) {
            $usersIds = User::where('full_name', 'like', "%$user%")->pluck('id')->toArray();

            $query->whereIn('user_id', $usersIds);
        }

        if (!empty($course_id)) {
            $query->where('webinar_id', $course_id);
        }

        if (!empty($webinar)) {
            $webinarsIds = Webinar::whereTranslationLike('title', "%$webinar%")->pluck('id')->toArray();

            $query->whereIn('webinar_id', $webinarsIds);
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
                'webinar' => function ($query) {
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
            $html .= (string)view()->make('design_1.panel.webinars.comments.table_items', ['comment' => $commentRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $comments, $total, $count, true)
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'webinar_id' => 'required_without:product_id',
            'product_id' => 'required_without:webinar_id',
            'comment' => 'nullable',
        ]);

        Comment::create([
            'webinar_id' => $request->input('webinar_id'),
            'product_id' => $request->input('product_id'),
            'user_id' => $request->input('user_id'),
            'comment' => $request->input('comment'),
            'reply_id' => $request->input('reply_id'),
            'status' => 'pending',
            'created_at' => time()
        ]);

        return redirect()->back();
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
                $query->orWhereHas('webinar', function ($query) use ($user) {
                    $query->where(function ($query) use ($user) {
                        $query->where('creator_id', $user->id);
                        $query->orWhere('teacher_id', $user->id);
                    });
                });
                $query->orWhereHas('product', function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                });
            })->first();

        if (!empty($comment)) {

            Comment::create([
                'user_id' => $user->id,
                'comment' => $request->get('comment'),
                'webinar_id' => $comment->webinar_id,
                'product_id' => $comment->product_id,
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
                $query->orWhereHas('webinar', function ($query) use ($user) {
                    $query->where(function ($query) use ($user) {
                        $query->where('creator_id', $user->id);
                        $query->orWhere('teacher_id', $user->id);
                    });
                });
                $query->orWhereHas('product', function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                });
            })->first();

        if (!empty($comment)) {

            CommentReport::create([
                'webinar_id' => $comment->webinar_id,
                'product_id' => $comment->product_id,
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
