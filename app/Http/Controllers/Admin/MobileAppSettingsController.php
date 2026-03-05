<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\traits\DeviceLimitSettings;
use App\Http\Controllers\Admin\traits\FinancialCurrencySettings;
use App\Http\Controllers\Admin\traits\FinancialOfflineBankSettings;
use App\Http\Controllers\Admin\traits\FinancialUserBankSettings;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\NotificationTemplate;
use App\Models\OfflineBank;
use App\Models\PaymentChannel;
use App\Models\Setting;
use App\Models\Translation\SettingTranslation;
use App\Models\UserBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MobileAppSettingsController extends Controller
{
    public function index(Request $request, $name = null)
    {
        $this->authorize('admin_settings_mobile_app');
        removeContentLocale();

        if (empty($name)) {
            $name = Setting::$mobileAppGeneralSettingsName;
        }

        $settings = Setting::where('name', $name)->first();

        $values = null;
        $defaultLocale = Setting::$defaultSettingsLocale;
        $locale = $request->get('locale', mb_strtolower($defaultLocale));

        if (!empty($settings)) {
            storeContentLocale($locale, $settings->getTable(), $settings->id);

            if (!empty($settings->value)) {
                $values = json_decode($settings->value, true);

                $values['locale'] = mb_strtoupper($settings->locale);
            }
        }

        $data = [
            'pageTitle' => trans('update.mobile_app_configuration'),
            'itemValue' => $values,
            'name' => $name,
            'locale' => $locale,
        ];

        return view("admin.settings.mobile_app.index", $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_settings_mobile_app');

        $this->validate($request, [
            'name' => 'required',
        ]);

        $name = $request->get('name');

        $tmpValues = $request->get('value', null);

        if (!empty($tmpValues)) {
            $defaultLocale = Setting::$defaultSettingsLocale;
            $locale = $request->get('locale', $defaultLocale);

            $values = [];
            foreach ($tmpValues as $key => $val) {
                if (is_array($val)) {
                    $values[$key] = array_filter($val);
                } else {
                    $values[$key] = $val;
                }
            }

            $values = json_encode($values);
            $values = str_replace('record', rand(1, 600), $values);

            $setting = Setting::updateOrCreate([
                'name' => $name
            ], [
                'page' => "mobile_app",
                'updated_at' => time(),
            ]);

            SettingTranslation::updateOrCreate(
                [
                    'setting_id' => $setting->id,
                    'locale' => mb_strtolower($locale)
                ],
                [
                    'value' => $values,
                ]
            );

            cache()->forget('settings.' . $name);
        }

        removeContentLocale();

        return back();
    }

}
