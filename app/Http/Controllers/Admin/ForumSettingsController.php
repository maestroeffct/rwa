<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Translation\SettingTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForumSettingsController extends Controller
{

    public function settings(Request $request, $pageTab = "general")
    {
        $this->authorize('admin_forum_settings');

        if (empty($pageTab)) {
            $pageTab = "general";
        }

        removeContentLocale();
        $defaultLocale = Setting::$defaultSettingsLocale;

        $name = Setting::$forumsGeneralSettingsName;

        if ($pageTab == "homepage") {
            $name = Setting::$forumsHomepageSettingsName;
        } elseif ($pageTab == "images") {
            $name = Setting::$forumsImagesSettingsName;
        } elseif ($pageTab == "homepage_revolver") {
            $name = Setting::$forumsHomepageRevolverSettingsName;
        }elseif ($pageTab == "cta_section") {
            $name = Setting::$forumsCtaSectionSettingsName;
        }

        $locale = $request->get('locale', $defaultLocale);

        $setting = Setting::where('page', 'forum')
            ->where('name', $name)
            ->first();

        if (!empty($setting)) {
            storeContentLocale($locale, $setting->getTable(), $setting->id);
        }

        $settingValues = !empty($setting) ? $setting->value : null;

        if (!empty($settingValues)) {
            $settingValues = json_decode($settingValues, true);
        }

        $data = [
            'pageTitle' => trans('update.forum_settings'),
            'settingValues' => $settingValues,
            'pageTab' => $pageTab,
            'selectedLocale' => mb_strtolower($request->get('locale', $defaultLocale)),
        ];

        return view('admin.forums.settings.index', $data);
    }

    public function storeSettings(Request $request)
    {
        $this->authorize('admin_forum_settings');

        $page = 'forum';
        $data = $request->all();
        $name = $data['name'];
        $locale = !empty($data['locale']) ? $data['locale'] : Setting::$defaultSettingsLocale;
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
