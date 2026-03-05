<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LandingBuilder\traits\LandingComponentsTrait;
use App\Models\Role;
use App\Models\ThemeHeaderFooter;
use App\Models\Translation\ThemeHeaderFooterTranslation;
use Illuminate\Http\Request;

class ThemeFootersController extends Controller
{
    use LandingComponentsTrait;

    protected $uploadDestination;

    public function index(Request $request)
    {
        $this->authorize("admin_themes_footers");

        $themeFooters = ThemeHeaderFooter::query()->where('type', 'footer')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('update.theme_footers'),
            'themeFooters' => $themeFooters,
        ];

        return view('admin.theme.footers.lists', $data);
    }

    public function edit(Request $request, $themeId)
    {
        $this->authorize("admin_themes_footers");

        $footerItem = ThemeHeaderFooter::query()->findOrFail($themeId);
        $locale = mb_strtolower($request->get('locale', app()->getLocale()));

        $contents = [];
        if (!empty($footerItem->content) and !empty($footerItem->translate($locale)) and !empty($footerItem->translate($locale)->content)) {
            $contents = json_decode($footerItem->translate($locale)->content, true);
        }

        $data = [
            'pageTitle' => trans("update.edit_footer"),
            'footerItem' => $footerItem,
            'contents' => $contents,
            'locale' => $locale,
            'userRoles' => Role::query()->get(),
        ];

        return view('admin.theme.footers.create', $data);
    }

    public function update(Request $request, $themeId)
    {
        $this->authorize("admin_themes_footers");

        $footerItem = ThemeHeaderFooter::query()->findOrFail($themeId);

        $this->validate($request, [
            'title' => 'required',
        ]);

        $locale = mb_strtolower($request->get('locale', app()->getLocale()));

        $this->uploadDestination = "themes/footers/{$footerItem->id}";

        $newContents = $request->all(['contents']);
        $newContentData = $this->makeContentDataByName($request, $newContents);
        $newContentData = (!empty($newContentData['contents']) and is_array($newContentData['contents'])) ? $newContentData['contents'] : [];

        $oldContents = [];
        if (!empty($footerItem->translate($locale)) and !empty($footerItem->translate($locale)->content)) {
            $oldContents = json_decode($footerItem->translate($locale)->content, true);
        }

        $contentsData = $this->handleOldAndNewContentData($oldContents, $newContentData);

        ThemeHeaderFooterTranslation::query()->updateOrCreate([
            'theme_header_footer_id' => $footerItem->id,
            'locale' => mb_strtolower($locale)
        ], [
            'title' => $request->get('title'),
            'content' => json_encode($contentsData),
        ]);


        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans("update.theme_footer_updated_successful"),
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl("/themes/footers/{$footerItem->id}/edit?locale={$locale}"))->with(['toast' => $toastData]);
    }

}
