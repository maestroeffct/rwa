<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MyCommentsController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Comment::query()->where('user_id', $user->id)
            ->whereNotNull('webinar_id');

        //$copyQuery = deepClone($query);
        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $data = [
            'pageTitle' => trans('panel.my_comments'),
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.webinars.my_comments.index', $data);
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
                }
            ])
            ->get();

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
            $html .= (string)view()->make('design_1.panel.webinars.my_comments.table_items', ['comment' => $commentRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $comments, $total, $count, true)
        ]);
    }

}
