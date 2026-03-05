<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Webinar;
use App\Models\WebinarReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebinarReviewController extends Controller
{

    public function getReviewsByCourseSlug(Request $request, $courseSlug)
    {
        $total = 0;
        $course = Webinar::query()->select('id', 'slug')
            ->where('slug', $courseSlug)
            ->first();

        if (!empty($course)) {
            $page = $request->get('page', 1);
            $count = 10;

            $query = WebinarReview::query()->where('webinar_id', $course->id);
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
                'reviews_count' => $total,
            ];
        }

        abort(404);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = auth()->user();

        $validator = Validator::make($data, [
            'webinar_id' => 'required',
            'content_quality' => 'required',
            'instructor_skills' => 'required',
            'purchase_worth' => 'required',
            'support_quality' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }


        $webinar = Webinar::where('id', $data['webinar_id'])
            ->where('status', 'active')
            ->first();

        if (!empty($webinar)) {
            if ($webinar->checkUserHasBought($user, false)) {
                $webinarReview = WebinarReview::where('creator_id', $user->id)
                    ->where('webinar_id', $webinar->id)
                    ->first();

                if (!empty($webinarReview)) {
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
                    'webinar_id' => $webinar->id,
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
                    '[c.title]' => $webinar->title,
                    '[item_title]' => $webinar->title,
                    '[student.name]' => $user->full_name,
                    '[u.name]' => $user->full_name,
                    '[rate.count]' => $rates > 0 ? $rates / 4 : 0,
                    '[content_type]' => trans('admin/main.course'),
                ];
                sendNotification('new_rating', $notifyOptions, $webinar->teacher_id);
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
                        'msg' => trans('cart.you_not_purchased_this_course'),
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
