<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\SendResetPasswordSMS;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        $data = [
            'pageTitle' => trans('auth.forget_password'),
        ];

        $authTemplate = getThemeAuthenticationPagesStyleName();
        return view("design_1.web.auth.{$authTemplate}.forgot_password.index", $data);
    }

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

        if (!empty(getGeneralSecuritySettings('captcha_for_forgot_pass'))) {
            $rules['captcha'] = 'required|captcha';
        }

        $this->validate($request, $rules, [], [
            'mobile' => trans('auth.mobile'),
            'email' => trans('auth.email'),
            'captcha' => trans('site.captcha'),
        ]);

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

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.the_new_password_has_been_sent_to_your_number'),
                'status' => 'success'
            ];

            return redirect('/login')->with(['toast' => $toastData]);
        }

        return back()->withInput($request->all())->withErrors([
            'mobile' => [trans('validation.exists', ['attribute' => trans('public.mobile')])]
        ]);
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

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('auth.send_email_for_reset_password'),
                'status' => 'success'
            ];

            return back()->with(['toast' => $toastData]);
        } catch (\Exception  $e) {

            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('auth.failed_send_email'),
                'status' => 'error'
            ];

            return back()->with(['toast' => $toastData]);
        }
    }

    public function username()
    {
        $email_regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

        if (empty($this->username)) {
            $this->username = 'mobile';

            if (preg_match($email_regex, request('username', null))) {
                $this->username = 'email';
            }
        }
        return $this->username;
    }
}
