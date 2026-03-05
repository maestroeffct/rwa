<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Translation\SettingTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeetingPackagesSettingsController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('admin_meeting_packages_settings');

        removeContentLocale();

        $setting = Setting::where('page', 'general')
            ->where('name', Setting::$meetingPackagesSettingsName)
            ->first();

        $values = [];
        if (!empty($setting)) {
            $values = json_decode($setting->value, true);
        }

        $data = [
            'pageTitle' => trans('update.meeting_packages_settings'),
            'values' => $values,
        ];

        return view('admin.meeting_packages.settings.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_meeting_packages_settings');

        $data = $request->all();
        $page = $data['page'];
        $name = $data['name'];
        $locale = $request->get('locale', Setting::$defaultSettingsLocale);
        $newValues = $data['value'];
        $values = [];

        $settings = Setting::where('name', $name)->first();

        if (!empty($settings) and !empty($settings->value)) {
            $values = json_decode($settings->value);
        }

        if (!empty($newValues) and !empty($values)) {
            foreach ($newValues as $newKey => $newValue) {
                foreach ($values as $key => $value) {
                    if ($key == $newKey) {
                        $values->$key = $newValue;
                        unset($newValues[$key]);
                    }
                }
            }
        }

        if (!empty($newValues)) {
            $values = array_merge((array)$values, $newValues);
        }

        $settings = Setting::updateOrCreate(
            ['name' => $name],
            [
                'page' => $page,
                'updated_at' => time(),
            ]
        );

        SettingTranslation::updateOrCreate(
            [
                'setting_id' => $settings->id,
                'locale' => mb_strtolower($locale)
            ],
            [
                'value' => json_encode($values),
            ]
        );

        cache()->forget('settings.' . $name);

        return back();
    }
}
