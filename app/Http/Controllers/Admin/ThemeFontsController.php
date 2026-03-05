<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeColorFont;
use Illuminate\Http\Request;

class ThemeFontsController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize("admin_themes_fonts");

        $themeFonts = ThemeColorFont::query()->where('type', 'font')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('admin/main.theme_fonts'),
            'themeFonts' => $themeFonts,
        ];

        return view('admin.theme.fonts.lists', $data);
    }

    public function create(Request $request)
    {
        $this->authorize("admin_themes_fonts");

        $data = [
            'pageTitle' => trans('admin/main.theme_fonts'),
        ];

        return view('admin.theme.fonts.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("admin_themes_fonts");

        $this->validate($request, [
            'title' => 'required',
        ]);

        $storeData = $this->makeStoreData($request);
        $fontItem = ThemeColorFont::query()->create($storeData);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans("update.theme_font_created_successful"),
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl("/themes/fonts/{$fontItem->id}/edit"))->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $themeId)
    {
        $this->authorize("admin_themes_fonts");

        $fontItem = ThemeColorFont::query()->findOrFail($themeId);


        $contents = [];
        if (!empty($fontItem->content)) {
            $contents = json_decode($fontItem->content, true);
        }


        $data = [
            'pageTitle' => trans("update.edit_font"),
            'fontItem' => $fontItem,
            'contents' => $contents,
        ];

        return view('admin.theme.fonts.create', $data);
    }

    public function update(Request $request, $themeId)
    {
        $this->authorize("admin_themes_fonts");

        $fontItem = ThemeColorFont::query()->findOrFail($themeId);

        $this->validate($request, [
            'title' => 'required',
        ]);

        $storeData = $this->makeStoreData($request, $fontItem);
        $fontItem->update($storeData);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans("update.theme_font_updated_successful"),
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl("/themes/fonts/{$fontItem->id}/edit"))->with(['toast' => $toastData]);
    }

    public function delete(Request $request, $themeId)
    {
        $this->authorize("admin_themes_fonts");

        $fontItem = ThemeColorFont::query()->findOrFail($themeId);

        $fontItem->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans("update.theme_font_deleted_successful"),
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl("/themes/fonts"))->with(['toast' => $toastData]);
    }

    private function makeStoreData(Request $request, $fontItem = null)
    {
        $data = $request->all();
        $contents = $this->handleContents($request, $fontItem);

        return [
            'type' => 'font',
            'title' => $data['title'],
            'content' => $contents,
            'created_at' => !empty($fontItem) ? $fontItem->created_at : time(),
        ];
    }


    private function handleContents(Request $request, $fontItem)
    {
        $content = [];
        $tmpContents = $request->get('contents', []);

        if (!empty($tmpContents)) {
            foreach ($tmpContents as $key => $val) {
                if (is_array($val)) {
                    $content[$key] = array_filter($val);
                } else {
                    $content[$key] = $val;
                }
            }
        }

        $content = json_encode($content);
        return str_replace('record', rand(1, 600), $content);
    }
}
