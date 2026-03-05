<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Waitlist;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WaitlistController extends Controller
{

    public function getWaitlistModal($courseSlug)
    {
        $course = Webinar::where('slug', $courseSlug)
            ->where('status', 'active')
            ->first();

        if (!empty($course)) {
            $user = auth()->user();

            $data = [
                'course' => $course,
                'user' => $user,
            ];

            $html = (string)view("design_1.web.courses.show.includes.waitlist_modal", $data)->render();

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 400);
    }

    public function store(Request $request, $courseSlug)
    {
        $user = auth()->user();
        $data = $request->all();

        $rules = [];

        if (empty($user)) {
            $rules['name'] = 'required|string';
            $rules['email'] = 'required|email';
            $rules['phone'] = 'required';
            $rules['captcha'] = 'required|captcha';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Webinar::where('slug', $courseSlug)
            ->where('status', 'active')
            ->first();

        if (!empty($course)) {
            $userId = !empty($user) ? $user->id : null;
            $fullName = $data['name'] ?? null;
            $email = $data['email'] ?? null;
            $phone = $data['phone'] ?? null;

            Waitlist::query()->updateOrCreate([
                'webinar_id' => $course->id,
                'user_id' => $userId,
                'email' => $email,
                'phone' => $phone
            ], [
                'full_name' => $fullName,
                'created_at' => time()
            ]);

            $notifyOptions = [
                '[c.title]' => $course->title,
                '[u.name]' => !empty($fullName) ? $fullName : (!empty($user) ? $user->full_name : 'User'),
            ];

            sendNotification("waitlist_submission_for_admin", $notifyOptions, 1);

            if (!empty($user)) {
                sendNotification("waitlist_submission", $notifyOptions, $user->id);
            } else {
                sendNotificationToEmail("waitlist_submission", $notifyOptions, $email);
            }

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.course_added_to_waitlists_successful'),
                'redirect_timeout' => 1500
            ]);
        }

        abort(404);
    }
}
