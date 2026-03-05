<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class InstructorFinderController extends Controller
{

    public function settings(Request $request)
    {
        $this->authorize('admin_instructor_finder_settings');

        removeContentLocale();

        $setting = Setting::where('page', 'general')
            ->where('name', Setting::$instructorFinderSettingsName)
            ->first();

        $data = [
            'pageTitle' => trans('update.settings'),
            'setting' => $setting,
            'selectedLocale' => mb_strtolower($request->get('locale', Setting::$defaultSettingsLocale)),
        ];

        return view('admin.instructor_finder.settings.index', $data);
    }

}
