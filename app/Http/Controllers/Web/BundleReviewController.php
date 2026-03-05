<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Comment;
use App\Models\Webinar;
use App\Models\WebinarReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BundleReviewController extends Controller
{

    public function getReviewsByBundleSlug(Request $request, $bundleSlug)
    {
        $bundle = Bundle::query()->select('id', 'slug')
            ->where('slug', $bundleSlug)
            ->first();

        if (!empty($bundle)) {
            $page = $request->get('page', 1);
            $count = 10;

            $query = WebinarReview::query()->where('bundle_id', $bundle->id);
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
        $this->validate($request, [
            'bundle_id' => 'required',
            'content_quality' => 'required',
            'instructor_skills' => 'required',
            'purchase_worth' => 'required',
            'support_quality' => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();

        $bundle = Bundle::where('id', $data['bundle_id'])
            ->where('status', 'active')
            ->first();

        if (!empty($bundle)) {
            if ($bundle->checkUserHasBought($user, false)) {
                $bundleReview = WebinarReview::where('creator_id', $user->id)
                    ->where('bundle_id', $bundle->id)
                    ->first();

                if (!empty($bundleReview)) {
                    return response()->json([
                        'toast_alert' => [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('public.duplicate_review_for_webinar'),
                        ]
                    ], 422);
                }

                $rates = 0;
                $rates += (int)$data['content_quality'];
                $rates += (int)$data['instructor_skills'];
                $rates += (int)$data['purchase_worth'];
                $rates += (int)$data['support_quality'];

                $status = Comment::$pending;
                if (!empty(getGeneralOptionsSettings('direct_publication_of_reviews'))) {
                    $status = Comment::$active;
                }

                WebinarReview::create([
                    'bundle_id' => $bundle->id,
                    'creator_id' => $user->id,
                    'content_quality' => (int)$data['content_quality'],
                    'instructor_skills' => (int)$data['instructor_skills'],
                    'purchase_worth' => (int)$data['purchase_worth'],
                    'support_quality' => (int)$data['support_quality'],
                    'rates' => $rates > 0 ? $rates / 4 : 0,
                    'description' => $data['description'],
                    'status' => $status,
                    'created_at' => time(),
                ]);


                $notifyOptions = [
                    '[item_title]' => $bundle->title,
                    '[u.name]' => $user->full_name,
                    '[rate.count]' => $rates > 0 ? $rates / 4 : 0,
                    '[content_type]' => trans('update.bundle'),
                ];
                sendNotification('new_review_for_bundle', $notifyOptions, $bundle->teacher_id);
                sendNotification('new_user_item_rating', $notifyOptions, 1);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => ($status == Comment::$active) ? trans('webinars.your_reviews_successfully_submitted') : trans('webinars.your_reviews_successfully_submitted_and_waiting_for_admin'),
                ]);
            } else {
                return response()->json([
                    'toast_alert' => [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('cart.you_not_purchased_this_bundle'),
                    ]
                ], 422);
            }
        }


        return response()->json([
            'toast_alert' => [
                'title' => trans('public.request_failed'),
                'msg' => trans('cart.bundle_not_found'),
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

        if (!empty($user)) {
            $status = Comment::$pending;
            if (!empty(getGeneralOptionsSettings('direct_publication_of_comments'))) {
                $status = Comment::$active;
            }

            Comment::create([
                'user_id' => $user->id,
                'comment' => $data['reply'],
                'review_id' => $data['review_id'],
                'status' => $status,
                'created_at' => time()
            ]);

            return response()->json([
                'code' => 200,
                'title' => trans('product.comment_success_store'),
                'msg' => trans('product.comment_success_store_msg'),
            ]);
        }

        abort(403);
    }

    public function destroy(Request $request, $id)
    {
        if (auth()->check()) {
            $review = WebinarReview::where('id', $id)
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
        }

        return response()->json([
            'code' => 403,
            'title' => trans('public.request_failed'),
            'msg' => trans('webinars.you_not_access_review'),
        ]);
    }
}
