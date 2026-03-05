<?php

namespace App\Http\Controllers\Api\Config;

use App\Api\Request;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Web\traits\UserFormFieldsTrait;
use App\Models\PaymentChannel;
use Illuminate\Http\Request as HttpRequest;

class ConfigController extends Controller
{
    use UserFormFieldsTrait;


    public function list(Request $request)
    {
        $generalSettings = getGeneralSettings();
        $generalOptionsSettings = getGeneralOptionsSettings();
        $featuresSettings = getFeaturesSettings();
        $financialSettings = getFinancialSettings();
        $financialCurrencySettings = getFinancialCurrencySettings();
        $referralSettings = getReferralSettings();

        $registerMethod = $generalSettings['register_method'] ?? 'mobile';
        $userLanguages = $generalSettings['user_languages'] ?? [];

        if (!empty($userLanguages) and is_array($userLanguages)) {
            $userLanguages = getLanguages($userLanguages);
        } else {
            $userLanguages = [];
        }

        $paymentChannels = PaymentChannel::get()->groupBy('status');

        $currency = [
            'sign' => currencySign(),
            'name' => currency()
        ];
        $showOtherRegisterMethod = (!empty($featuresSettings) and !empty($featuresSettings['show_other_register_method']));

        $selectRolesDuringRegistration = !empty($featuresSettings['select_the_role_during_registration']) ? $featuresSettings['select_the_role_during_registration'] : null;

        $allowInstructorDeleteContent = !!(!empty($generalOptionsSettings['allow_instructor_delete_content']));
        $contentDeleteMethod = !empty($generalOptionsSettings['content_delete_method']) ? $generalOptionsSettings['content_delete_method'] : 'delete_directly';

        $data = [
            'register_method' => $registerMethod,
            'selectRolesDuringRegistration' => $selectRolesDuringRegistration,
            'offline_bank_account' => getOfflineBanksTitle() ?? null,
            'user_language' => $userLanguages,
            'payment_channels' => $paymentChannels,
            'minimum_payout_amount' => !empty($financialSettings['minimum_payout']) ? $financialSettings['minimum_payout'] : null,
            'currency' => $currency,
            'price_display' => !empty($financialSettings['price_display']) ? $financialSettings['price_display'] : 'only_price',
            'multi_currency' => !empty($financialCurrencySettings['multi_currency']),
            'currency_position' => !empty($financialCurrencySettings['currency_position']) ? $financialCurrencySettings['currency_position'] : 'left',
            'currency_decimal' => $financialCurrencySettings['currency_decimal'] ?? null,
            'forum_settings' => getForumsHomepageSettings(),
            'course_forum_status' => !empty($featuresSettings['course_forum_status']) ? $featuresSettings['course_forum_status'] : null,
            'show_google_login_button' => !empty($featuresSettings['show_google_login_button']),
            'show_facebook_login_button' => !empty($featuresSettings['show_facebook_login_button']),
            'showOtherRegisterMethod' => $showOtherRegisterMethod,
            'webinar_private_content_status' => !empty($featuresSettings['webinar_private_content_status']) ? $featuresSettings['webinar_private_content_status'] : null,
            'sequence_content_status' => !empty($featuresSettings['sequence_content_status']) ? $featuresSettings['sequence_content_status'] : null,
            'course_notes_status' => !empty($featuresSettings['course_notes_status']) ? $featuresSettings['course_notes_status'] : null,
            'course_notes_attachment' => !empty($featuresSettings['course_notes_attachment']) ? $featuresSettings['course_notes_attachment'] : null,
            'allow_instructor_delete_content' => $allowInstructorDeleteContent,
            'content_delete_method' => $contentDeleteMethod,
            'referralSettings' => $referralSettings,
            'free_mode' => !empty($featuresSettings['free_mode']),
            'free_mode_show_price' => !empty($featuresSettings['free_mode_show_price']),
            'free_mode_show_cart' => !empty($featuresSettings['free_mode_show_cart']),
        ];

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),
            $data
        );
    }

    public function getRegisterConfig(HttpRequest $request, $type)
    {
        $generalSettings = getGeneralSettings();
        $featuresSettings = getFeaturesSettings();
        $referralSettings = getReferralSettings();
        $generalOptionsSettings = getGeneralOptionsSettings();

        $registerMethod = $generalSettings['register_method'] ?? 'mobile';
        $userLanguages = $generalSettings['user_languages'] ?? [];

        if (!empty($userLanguages) and is_array($userLanguages)) {
            $userLanguages = getLanguages($userLanguages);
        } else {
            $userLanguages = [];
        }

        $showOtherRegisterMethod = !empty($featuresSettings['show_other_register_method']);

        $formFields = $this->getFormFieldsByType($type);
        $showCertificateAdditionalInRegister = !empty($featuresSettings['show_certificate_additional_in_register']);
        $selectRolesDuringRegistration = !empty($featuresSettings['select_the_role_during_registration']) ? $featuresSettings['select_the_role_during_registration'] : null;
        $selectedTimezone = $generalSettings['default_time_zone'] ?? null;


        $config = [
            'selectedTimezone' => $selectedTimezone,
            'selectRolesDuringRegistration' => $selectRolesDuringRegistration,
            'showCertificateAdditionalInRegister' => $showCertificateAdditionalInRegister,
            'showOtherRegisterMethod' => $showOtherRegisterMethod,
            'referralSettings' => $referralSettings,
            'formFields' => $formFields,
            'register_method' => $registerMethod,
            'user_language' => $userLanguages,
            'show_google_login_button' => !empty($featuresSettings['show_google_login_button']),
            'show_facebook_login_button' => !empty($featuresSettings['show_facebook_login_button']),
            'disable_registration_verification' => !empty($generalOptionsSettings['disable_registration_verification_process']),
        ];

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),
            $config
        );
    }


}
