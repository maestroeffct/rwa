<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{

    public function getReviewsByCourseSlug(Request $request, $productSlug)
    {
        $product = Product::query()->select('id', 'slug')
            ->where('slug', $productSlug)
            ->first();

        if (!empty($product)) {
            $page = $request->get('page', 1);
            $count = 10;

            $query = ProductReview::query()->where('product_id', $product->id);
            $query->where('status', 'active');
            $query->with([
                'comments' => function ($query) {
                    $query->where('status', 'active');
                },
                'creator' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                }
            ]);
            $query->orderBy('created_at', 'desc');

            $total = $query->count();

            $query->limit($count);
            $query->offset(($page - 1) * $count);

            $reviews = $query->get();
            $hasMore = $total > ($page * $count);

            if ($request->ajax()) {
                $html = (string)view()->make('design_1.web.components.reviews.all_cards', ['reviews' => $reviews]);

                return response()->json([
                    'code' => 200,
                    'html' => $html,
                    'has_more' => $hasMore,
                ]);
            }

            return [
                'reviews' => $reviews,
                'has_more' => $hasMore,
            ];
        }

        abort(404);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = auth()->user();

        $validator = Validator::make($data, [
            'product_id' => 'required',
            'product_quality' => 'required',
            'purchase_worth' => 'required',
            'delivery_quality' => 'required',
            'seller_quality' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $product = Product::where('id', $data['product_id'])
            ->where('status', 'active')
            ->first();

        if (!empty($product)) {
            if ($product->checkUserHasBought($user)) {
                $productReview = ProductReview::where('creator_id', $user->id)
                    ->where('product_id', $product->id)
                    ->first();

                if (!empty($productReview)) {
                    return response()->json([
                        'toast_alert' => [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('public.duplicate_review_for_product'),
                        ]
                    ], 422);
                }

                $rates = 0;
                $rates += (int)$data['product_quality'];
                $rates += (int)$data['purchase_worth'];
                $rates += (int)$data['delivery_quality'];
                $rates += (int)$data['seller_quality'];

                ProductReview::create([
                    'product_id' => $product->id,
                    'creator_id' => $user->id,
                    'product_quality' => (int)$data['product_quality'],
                    'purchase_worth' => (int)$data['purchase_worth'],
                    'delivery_quality' => (int)$data['delivery_quality'],
                    'seller_quality' => (int)$data['seller_quality'],
                    'rates' => $rates > 0 ? $rates / 4 : 0,
                    'description' => $data['description'],
                    'status' => 'pending',
                    'created_at' => time(),
                ]);

                $notifyOptions = [
                    '[p.title]' => $product->title,
                    '[u.name]' => $user->full_name,
                    '[item_title]' => $product->title,
                    '[content_type]' => trans('update.product'),
                    '[rate.count]' => $rates > 0 ? $rates / 4 : 0,
                ];
                sendNotification('product_new_rating', $notifyOptions, $product->creator_id);
                sendNotification('new_user_item_rating', $notifyOptions, 1);


                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('webinars.your_reviews_successfully_submitted_and_waiting_for_admin'),
                ]);
            } else {
                return response()->json([
                    'toast_alert' => [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('cart.you_not_purchased_this_product'),
                    ]
                ], 422);
            }
        }

        return response()->json([
            'toast_alert' => [
                'title' => trans('public.request_failed'),
                'msg' => trans('cart.course_not_found'),
            ]
        ], 422);
    }

    public function storeReplyComment(Request $request)
    {
        $user = auth()->user();
        $data = $request->all();

        $validator = Validator::make($data, [
            'reply' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        Comment::create([
            'user_id' => $user->id,
            'comment' => $data['reply'],
            'product_review_id' => $data['review_id'],
            'status' => $request->input('status') ?? Comment::$pending,
            'created_at' => time()
        ]);


        return response()->json([
            'code' => 200,
            'title' => trans('product.comment_success_store'),
            'msg' => trans('product.comment_success_store_msg'),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        if (auth()->check()) {
            $review = ProductReview::where('id', $id)
                ->where('creator_id', auth()->id())
                ->first();

            if (!empty($review)) {
                $review->delete();

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('webinars.your_review_deleted'),
                ]);
            }

            return response()->json([
                'code' => 403,
                'title' => trans('public.request_failed'),
                'msg' => trans('webinars.you_not_access_review'),
            ]);
        }

        abort(404);
    }
}
