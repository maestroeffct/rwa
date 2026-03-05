<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BlogCommentsController extends Controller
{
    private function handleAuthorize($user)
    {
        if (!$user->isTeacher()) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $this->authorize("panel_blog_comments");

        $user = auth()->user();

        $this->handleAuthorize($user);

        $posts = Blog::query()->select('id', 'author_id')
            ->where('author_id', $user->id)
            ->get();

        $blogIds = $posts->pluck('id')->toArray();
        $query = Comment::query()->whereIn('blog_id', $blogIds);

        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $data = [
            'pageTitle' => trans('panel.comments'),
            'posts' => $posts,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.blog.comments.index', $data);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $blogId = $request->get('blog_id', null);

        fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($blogId) and is_numeric($blogId)) {
            $query->where('blog_id', $blogId);
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
                'blog'
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
            $html .= (string)view()->make('design_1.panel.blog.comments.table_items', ['comment' => $commentRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $comments, $total, $count, true)
        ]);
    }

}
