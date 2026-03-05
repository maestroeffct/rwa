<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LandingBuilder\traits\LandingComponentsTrait;
use App\Models\Role;
use App\Models\ThemeHeaderFooter;
use App\Models\Translation\ThemeHeaderFooterTranslation;
use Illuminate\Http\Request;

class ThemeHeadersController extends Controller
{
    use LandingComponentsTrait;

    protected $uploadDestination;

    public function index(Request $request)
    {
        $this->authorize("admin_themes_headers");

        $themeHeaders = ThemeHeaderFooter::query()->where('type', 'header')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('update.theme_headers'),
            'themeHeaders' => $themeHeaders,
        ];

        return view('admin.theme.headers.lists', $data);
    }

    public function edit(Request $request, $themeId)
    {
        $this->authorize("admin_themes_headers");

        $headerItem = ThemeHeaderFooter::query()->findOrFail($themeId);
        $locale = mb_strtolower($request->get('locale', app()->getLocale()));

        $contents = [];
        if (!empty($headerItem->content) and !empty($headerItem->translate($locale)) and !empty($headerItem->translate($locale)->content)) {
            $contents = json_decode($headerItem->translate($locale)->content, true);
        }

        $data = [
            'pageTitle' => trans("update.edit_header"),
            'headerItem' => $headerItem,
            'contents' => $contents,
            'locale' => $locale,
            'userRoles' => Role::query()->get(),
        ];

        return view('admin.theme.headers.create', $data);
    }

    public function update(Request $request, $themeId)
    {
        $this->authorize("admin_themes_headers");

        $headerItem = ThemeHeaderFooter::query()->findOrFail($themeId);

        $this->validate($request, [
            'title' => 'required',
        ]);

        $locale = mb_strtolower($request->get('locale', app()->getLocale()));

        $this->uploadDestination = "themes/headers/{$headerItem->id}";

        $newContents = $request->all(['contents']);
        $newContentData = $this->makeContentDataByName($request, $newContents);
        $newContentData = (!empty($newContentData['contents']) and is_array($newContentData['contents'])) ? $newContentData['contents'] : [];

        $oldContents = [];
        if (!empty($headerItem->translate($locale)) and !empty($headerItem->translate($locale)->content)) {
            $oldContents = json_decode($headerItem->translate($locale)->content, true);
        }

        $contentsData = $this->handleOldAndNewContentData($oldContents, $newContentData);

        ThemeHeaderFooterTranslation::query()->updateOrCreate([
            'theme_header_footer_id' => $headerItem->id,
            'locale' => mb_strtolower($locale)
        ], [
            'title' => $request->get('title'),
            'content' => json_encode($contentsData),
        ]);


        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans("update.theme_header_updated_successful"),
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl("/themes/headers/{$headerItem->id}/edit?locale={$locale}"))->with(['toast' => $toastData]);
    }

}
