<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mixins\RegistrationBonus\RegistrationBonusAccounting;
use App\Models\Affiliate;
use App\Models\Verification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    public function index()
    {
        $verificationId = session()->get('verificationId', null);

        if (!empty($verificationId)) {
            $verification = Verification::where('id', $verificationId)
                ->whereNull('verified_at')
                ->where('expired_at', '>', time())
                ->first();

            if (!empty($verification)) {

                $user = User::find($verification->user_id);

                if (!empty($user) and $user->status != User::$active) {
                    $data = [
                        'pageTitle' => trans('auth.email_confirmation'),
                        'username' => !empty($verification->mobile) ? 'mobile' : 'email',
                        'usernameValue' => !empty($verification->mobile) ? $verification->mobile : $verification->email,
                    ];

                    $authTemplate = getThemeAuthenticationPagesStyleName();
                    return view("design_1.web.auth.{$authTemplate}.verification.index", $data);
                }
            }
        }

        return redirect('/login');
    }

    public function resendCode()
    {
        $verificationId = session()->get('verificationId', null);

        if (!empty($verificationId)) {
            $verification = Verification::where('id', $verificationId)
                ->whereNull('verified_at')
                ->first();

            if (!empty($verification)) {
                $time = time();

                $verification->update([
                    'code' => $this->getNewCode(),
                    'expired_at' => $time + Verification::EXPIRE_TIME,
                ]);

                if (!empty($verification->mobile)) {
                    $verification->sendSMSCode();
                } else {
                    $verification->sendEmailCode();
                }

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.new_verification_code_sent_successfully'),
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function checkConfirmed($user, $username, $value)
    {
        $disableRegistrationVerificationProcess = getGeneralOptionsSettings('disable_registration_verification_process');

        if (!empty($disableRegistrationVerificationProcess)) {
            return [
                'status' => 'verified'
            ];
        }

        if (!empty($value)) {
            $verification = Verification::where($username, $value)
                ->where('expired_at', '>', time())
                ->where(function ($query) {
                    $query->whereNotNull('user_id')
                        ->orWhereHas('user');
                })
                ->first();

            $data = [];
            $time = time();

            if (!empty($verification)) {
                if (!empty($verification->verified_at)) {
                    return [
                        'status' => 'verified'
                    ];
                } else {
                    $data['created_at'] = $time;
                    $data['expired_at'] = $time + Verification::EXPIRE_TIME;

                    if ($time > $verification->expired_at) {
                        $data['code'] = $this->getNewCode();
                    } else {
                        $data['code'] = $verification->code;
                    }
                }
            } else {
                $data[$username] = $value;
                $data['code'] = $this->getNewCode();
                $data['user_id'] = !empty($user) ? $user->id : (auth()->check() ? auth()->id() : null);
                $data['created_at'] = $time;
                $data['expired_at'] = $time + Verification::EXPIRE_TIME;
            }

            $data['verified_at'] = null;

            $verification = Verification::updateOrCreate([$username => $value], $data);

            session()->put('verificationId', $verification->id);

            if ($username == 'mobile') {
                $verification->sendSMSCode();
            } else {
                $verification->sendEmailCode();
            }

            return [
                'status' => 'send'
            ];
        }

        abort(404);
    }

    public function confirmCode(Request $request)
    {
        $username = $request->get('username');
        $usernameValue = $request->get('usernameValue');
        $time = time();

        $code = $request->get('code');

        if (is_array($code)) {
            $code = array_filter($code, function ($value) {
                return $value !== null;
            });
        }

        if (empty($code) or count($code) != 5) {
            return back()->withErrors([
                'code' => trans('update.verification_code_required'),
            ]);
        }

        $code = implode('', $code);

        $verification = Verification::where($username, $usernameValue)
            ->whereNull('verified_at')
            ->where('code', $code)
            ->first();

        if (empty($verification)) {
            return back()->withErrors([
                'code' => trans('update.verification_code_is_invalid'),
            ]);
        }

        if ($verification->expired_at < time()) {
            return back()->withErrors([
                'code' => trans('update.verification_code_is_expired'),
            ]);
        }

        $verification->update([
            'verified_at' => $time,
            'expired_at' => $time + 50,
        ]);

        $authUser = auth()->check() ? auth()->user() : null;
        $referralCode = session()->get('referralCode', null);

        if (empty($authUser)) {
            $authUser = User::where($username, $usernameValue)
                ->first();

            $loginController = new LoginController();

            if (!empty($authUser)) {
                if (\Auth::loginUsingId($authUser->id)) {

                    if (!empty($referralCode)) {
                        Affiliate::storeReferral($authUser, $referralCode);
                    }

                    $enableRegistrationBonus = false;
                    $registrationBonusAmount = null;
                    $registrationBonusSettings = getRegistrationBonusSettings();
                    if (!empty($registrationBonusSettings['status']) and !empty($registrationBonusSettings['registration_bonus_amount'])) {
                        $enableRegistrationBonus = true;
                        $registrationBonusAmount = $registrationBonusSettings['registration_bonus_amount'];
                    }

                    $authUser->update([
                        'enable_registration_bonus' => $enableRegistrationBonus,
                        'registration_bonus_amount' => $registrationBonusAmount,
                    ]);

                    $registrationBonusAccounting = new RegistrationBonusAccounting();
                    $registrationBonusAccounting->storeRegistrationBonusInstantly($authUser);

                    return $loginController->afterLogged($request, true);
                }
            }

            return $loginController->sendFailedLoginResponse($request);
        }
    }

    private function getNewCode()
    {
        return rand(10000, 99999);
    }
}
