<?php

namespace App\Http\Controllers\Panel\Store;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_products_comments");

        $user = auth()->user();

        $query = Comment::where('status', 'active')
            ->whereNotNull('product_id')
            ->whereHas('product', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            })
            ->with([
                'product' => function ($query) {
                    $query->select('id', 'slug');
                },
                'user' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
                'replies'
            ]);

        $copyQuery = deepClone($query);
        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query, $user);

        if ($request->ajax()) {
            return $getListData;
        }


        $allCommentsCount = deepClone($copyQuery)->count();
        $repliedCommentsCount = deepClone($copyQuery)->whereNotNull('reply_id')->count();

        $productsIds = deepClone($copyQuery)->pluck('product_id')->toArray();
        $allProductsLists = Product::query()->select('id')
            ->whereIn('id', $productsIds)->get();

        $usersIds = deepClone($copyQuery)->pluck('user_id')->toArray();
        $allUsersLists = User::query()->select('id', 'full_name')
            ->whereIn('id', $usersIds)->get();

        $data = [
            'pageTitle' => trans('panel.my_class_comments'),
            'allCommentsCount' => $allCommentsCount,
            'repliedCommentsCount' => $repliedCommentsCount,
            'allProductsLists' => $allProductsLists,
            'allUsersLists' => $allUsersLists,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.store.comments.index', $data);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $user_id = $request->get('user_id');
        $product_id = $request->get('product_id');
        $search = $request->get('search');
        $product_type = $request->get('product_type');
        $sort = $request->get('sort');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($user_id)) {
            $query->where('user_id', $user_id);
        }

        if (!empty($product_id)) {
            $query->where('product_id', $product_id);
        }

        if (!empty($search)) {
            $query->where('comment', "like", "%$search%");
        }

        if (!empty($product_type)) {
            $query->whereHas('product', function ($query) use ($product_type) {
                $query->where('type', $product_type);
            });
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

    private function getListsData(Request $request, Builder $query, $user)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $comments = $query->get();

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
            $html .= (string)view()->make('design_1.panel.store.comments.table_items', ['comment' => $commentRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $comments, $total, $count, true)
        ]);
    }

}
