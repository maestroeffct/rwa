<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeColorFont;
use Illuminate\Http\Request;

class ThemeColorsController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize("admin_themes_colors");

        $themeColors = ThemeColorFont::query()
            ->where('type', 'color')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('admin/main.theme_colors'),
            'themeColors' => $themeColors,
        ];

        return view('admin.theme.colors.lists', $data);
    }

    public function create(Request $request)
    {
        $this->authorize("admin_themes_colors");

        $data = [
            'pageTitle' => trans('admin/main.theme_colors'),
        ];

        return view('admin.theme.colors.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("admin_themes_colors");

        $this->validate($request, [
            'title' => 'required',
        ]);

        $storeData = $this->makeStoreData($request);
        $colorItem = ThemeColorFont::query()->create($storeData);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans("update.theme_color_created_successful"),
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl("/themes/colors/{$colorItem->id}/edit"))->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $themeId)
    {
        $this->authorize("admin_themes_colors");

        $colorItem = ThemeColorFont::query()->findOrFail($themeId);


        $contents = [];
        if (!empty($colorItem->content)) {
            $contents = json_decode($colorItem->content, true);
        }


        $data = [
            'pageTitle' => trans("update.edit_color"),
            'colorItem' => $colorItem,
            'contents' => $contents,
        ];

        return view('admin.theme.colors.create', $data);
    }

    public function update(Request $request, $themeId)
    {
        $this->authorize("admin_themes_colors");

        $colorItem = ThemeColorFont::query()->findOrFail($themeId);

        $this->validate($request, [
            'title' => 'required',
        ]);

        $storeData = $this->makeStoreData($request, $colorItem);
        $colorItem->update($storeData);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans("update.theme_color_updated_successful"),
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl("/themes/colors/{$colorItem->id}/edit"))->with(['toast' => $toastData]);
    }

    public function delete(Request $request, $themeId)
    {
        $this->authorize("admin_themes_colors");

        $colorItem = ThemeColorFont::query()->findOrFail($themeId);

        $colorItem->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans("update.theme_color_deleted_successful"),
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl("/themes/colors"))->with(['toast' => $toastData]);
    }

    private function makeStoreData(Request $request, $colorItem = null)
    {
        $data = $request->all();
        $contents = $this->handleContents($request, $colorItem);

        return [
            'type' => 'color',
            'title' => $data['title'],
            'content' => $contents,
            'created_at' => !empty($colorItem) ? $colorItem->created_at : time(),
        ];
    }


    private function handleContents(Request $request, $colorItem)
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
