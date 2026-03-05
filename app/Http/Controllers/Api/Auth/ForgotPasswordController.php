<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Notifications\SendResetPasswordSMS;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;


class ForgotPasswordController extends Controller
{

    public function forgot(Request $request)
    {
        $type = $request->get('type');

        if ($type == 'mobile') {
            $rules = [
                'mobile' => 'required|numeric',
                'country_code' => 'required',
            ];
        } else {
            $rules = [
                'email' => 'required|email|exists:users,email',
            ];
        }

        validateParam($request->all(), $rules);

        if ($type == 'mobile') {
            return $this->getByMobile($request);
        } else {
            return $this->getByEmail($request);
        }
    }

    private function getByMobile(Request $request)
    {
        $data = $request->all();
        $mobile = ltrim($data['country_code'], '+') . ltrim($data['mobile'], '0');

        $user = User::query()->where('mobile', $mobile)->first();

        if (!empty($user)) {
            $newPass = random_str(6, true, false);

            $user->notify(new SendResetPasswordSMS($user, $newPass));

            $user->update([
                'password' => Hash::make($newPass)
            ]);

            return apiResponse2(1, 'done', trans('update.the_new_password_has_been_sent_to_your_number'));
        }


        $data = [
            'errors' => [
                'mobile' => [trans('validation.exists', ['attribute' => trans('public.mobile')])]
            ]
        ];
        return apiResponse2(0, 'failure', trans('validation.exists', ['attribute' => trans('public.mobile')]), $data);
    }

    private function getByEmail(Request $request)
    {
        $email = $request->get('email');
        $token = \Illuminate\Support\Str::random(60);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $generalSettings = getGeneralSettings();
        $emailData = [
            'token' => $token,
            'generalSettings' => $generalSettings,
            'email' => $email
        ];

        $senderEmail = !empty($generalSettings['site_email']) ? $generalSettings['site_email'] : env('MAIL_FROM_ADDRESS');
        $senderName = !empty($generalSettings['site_name']) ? $generalSettings['site_name'] : env('MAIL_FROM_NAME');

        try {
            Mail::send('design_1.web.emails.password_verify', $emailData, function ($message) use ($email, $senderEmail, $senderName) {
                $message->from($senderEmail, $senderName);
                $message->to($email);
                $message->subject(trans('auth.reset_password_notification'));
            });

            return apiResponse2(1, 'done', trans('auth.send_email_for_reset_password'));
        } catch (\Exception  $e) {
            $data = [
                'errors' => [
                    'email' => [trans('auth.failed_send_email')]
                ]
            ];

            return apiResponse2(0, 'failure', trans('auth.failed_send_email'), $data);
        }
    }
}
