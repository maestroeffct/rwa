<?php

namespace App\Http\Controllers\Web\traits;

use App\Models\RewardAccounting;
use App\Models\Sale;
use App\Models\Webinar;
use App\Models\WebinarReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait CourseShowTrait
{

    public function getShareModal($slug)
    {
        $course = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($course)) {
            $data = [
                'course' => $course
            ];

            $html = (string)view("design_1.web.courses.show.includes.modals.share_modal", $data)->render();

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 400);
    }

    public function getReportModal($slug)
    {
        $course = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($course)) {
            $data = [
                'course' => $course
            ];

            $html = (string)view("design_1.web.courses.show.includes.modals.report_modal", $data)->render();

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 400);
    }

    public function getBuyWithPointModal($slug)
    {
        $user = auth()->user();

        $course = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($user) and !empty($course) and !empty($course->points)) {
            $availablePoints = $user->getRewardPoints();
            $haveEnoughPoints = ($availablePoints >= $course->points);

            $data = [
                'course' => $course,
                'availablePoints' => $availablePoints,
                'haveEnoughPoints' => $haveEnoughPoints,
            ];

            $html = (string)view("design_1.web.courses.show.includes.modals.buy_with_point_modal", $data)->render();

            return response()->json([
                'code' => 200,
                'html' => $html,
                'btn_url' => $haveEnoughPoints ? "/course/{$course->slug}/points/apply" : "/panel/rewards",
                'btn_text' => $haveEnoughPoints ? trans('update.purchase') : trans('update.my_points'),
            ]);
        }

        return response()->json([], 400);
    }

    public function buyWithPoint($slug)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $course = Webinar::where('slug', $slug)
                ->where('status', 'active')
                ->first();

            if (!empty($course)) {
                if (empty($course->points)) {
                    $toastData = [
                        'title' => '',
                        'msg' => trans('update.can_not_buy_this_course_with_point'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $availablePoints = $user->getRewardPoints();

                if ($availablePoints < $course->points) {
                    $toastData = [
                        'title' => '',
                        'msg' => trans('update.you_have_no_enough_points_for_this_course'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $checkCourseForSale = checkCourseForSale($course, $user);

                if ($checkCourseForSale != 'ok') {
                    return back()->with(['toast' => $checkCourseForSale]);
                }

                Sale::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $course->creator_id,
                    'webinar_id' => $course->id,
                    'type' => Sale::$webinar,
                    'payment_method' => Sale::$credit,
                    'amount' => 0,
                    'total_amount' => 0,
                    'created_at' => time(),
                ]);

                RewardAccounting::makeRewardAccounting($user->id, $course->points, 'withdraw', null, false, RewardAccounting::DEDUCTION);

                $toastData = [
                    'title' => '',
                    'msg' => trans('update.success_pay_course_with_point_msg'),
                    'status' => 'success'
                ];
                return back()->with(['toast' => $toastData]);
            }

            abort(404);
        } else {
            return redirect('/login');
        }
    }

    public function reportWebinar(Request $request, $id)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $data = $request->all();

            $validator = Validator::make($data, [
                'reason' => 'required|string',
                'message' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'errors' => $validator->errors()
                ], 422);
            }


            $webinar = Webinar::select('id', 'status')
                ->where('id', $id)
                ->where('status', 'active')
                ->first();

            if (!empty($webinar)) {
                WebinarReport::create([
                    'user_id' => $user->id,
                    'webinar_id' => $webinar->id,
                    'reason' => $data['reason'],
                    'message' => $data['message'],
                    'created_at' => time()
                ]);

                $notifyOptions = [
                    '[u.name]' => $user->full_name,
                    '[content_type]' => trans('product.course')
                ];
                sendNotification("new_report_item_for_admin", $notifyOptions, 1);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.report_successfully_registered_msg'),
                    'redirect_timeout' => 1500
                ], 200);
            }
        }

        return response()->json([
            'code' => 401
        ], 200);
    }


}
