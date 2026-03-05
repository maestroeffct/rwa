<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Landing;
use App\Models\Setting;
use App\Models\Theme;
use App\Models\ThemeColorFont;
use App\Models\ThemeHeaderFooter;
use Illuminate\Http\Request;

class ThemesController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize("admin_themes_create");

        $themes = Theme::query()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(200);

        $data = [
            'pageTitle' => trans('update.themes'),
            'themes' => $themes,
        ];

        return view('admin.theme.lists.index', $data);
    }

    private function getCreatePageExtraData()
    {
        $themeColors = ThemeColorFont::query()->where('type', 'color')->get();
        $themeFonts = ThemeColorFont::query()->where('type', 'font')->get();
        $themeHeaders = ThemeHeaderFooter::query()->where('type', 'header')->get();
        $themeFooters = ThemeHeaderFooter::query()->where('type', 'footer')->get();
        $landings = Landing::query()->where('enable', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'themeColors' => $themeColors,
            'themeFonts' => $themeFonts,
            'themeHeaders' => $themeHeaders,
            'themeFooters' => $themeFooters,
            'landings' => $landings,
        ];
    }

    public function create(Request $request)
    {
        $this->authorize("admin_themes_create");


        $data = [
            'pageTitle' => trans('update.new_theme'),
        ];
        $data = array_merge($data, $this->getCreatePageExtraData());

        return view('admin.theme.create.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("admin_themes_create");

        $this->validate($request, [
            'title' => 'required',
            'preview_image' => 'required',
            'color_id' => 'required|exists:theme_colors_fonts,id',
            'font_id' => 'required|exists:theme_colors_fonts,id',
            'header_id' => 'required|exists:theme_headers_footers,id',
            'footer_id' => 'required|exists:theme_headers_footers,id',
            'home_landing_id' => 'required|exists:landings,id',
        ]);

        $storeData = $this->makeStoreData($request);
        $themeItem = Theme::query()->create($storeData);

        if ($themeItem->enable) {
            Theme::query()->where('enable', true)
                ->where('id', '!=', $themeItem->id)
                ->update([
                    'enable' => false
                ]);
        }

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans("update.theme_created_successful"),
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl("/themes/{$themeItem->id}/edit"))->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $themeId)
    {
        $this->authorize("admin_themes_create");

        $themeItem = Theme::query()->where('id', $themeId)
            ->with([
                'homeLanding' => function ($query) {
                    $query->with([
                        'components' => function ($query) {
                            $query->with([
                                'landingBuilderComponent'
                            ]);
                            $query->orderBy('order', 'asc');
                        }
                    ]);
                }
            ])->first();

        if (!empty($themeItem)) {
            $set = Setting::query()->where('name', 'page_background')->first();
            $settVal = json_decode($set->value, true);

            $themeContents = [];
            if (!empty($themeItem->contents)) {
                $themeContents = json_decode($themeItem->contents, true);
            }


            $data = [
                'pageTitle' => trans("update.edit_theme"),
                'themeItem' => $themeItem,
                'themeContents' => $themeContents,
                'settVal' => $settVal,
            ];
            $data = array_merge($data, $this->getCreatePageExtraData());

            return view('admin.theme.create.index', $data);
        }

        abort(404);
    }

    public function update(Request $request, $themeId)
    {
        $this->authorize("admin_themes_create");

        $themeItem = Theme::query()->findOrFail($themeId);

        $this->validate($request, [
            'title' => 'required',
            'preview_image' => 'required',
            'color_id' => 'required|exists:theme_colors_fonts,id',
            'font_id' => 'required|exists:theme_colors_fonts,id',
            'header_id' => 'required|exists:theme_headers_footers,id',
            'footer_id' => 'required|exists:theme_headers_footers,id',
            'home_landing_id' => 'required|exists:landings,id',
        ]);

        $storeData = $this->makeStoreData($request);
        $themeItem->update($storeData);

        if ($themeItem->enable) {
            Theme::query()->where('enable', true)
                ->where('id', '!=', $themeItem->id)
                ->update([
                    'enable' => false
                ]);
        }

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans("update.theme_updated_successful"),
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl("/themes/{$themeItem->id}/edit"))->with(['toast' => $toastData]);
    }

    public function delete(Request $request, $themeId)
    {
        $this->authorize("admin_themes_create");

        $themeItem = Theme::query()->where('id', $themeId)
            ->where('is_default', false)
            ->first();

        if (!empty($themeItem)) {
            if ($themeItem->enable) {
                Theme::query()->where('is_default', true)
                    ->update([
                        'enable' => true
                    ]);
            }


            $themeItem->delete();

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans("update.theme_deleted_successful"),
                'status' => 'success'
            ];

            return redirect(getAdminPanelUrl("/themes"))->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function enable(Request $request, $themeId)
    {
        $this->authorize("admin_themes_create");

        $themeItem = Theme::query()->findOrFail($themeId);

        $themeItem->update([
            'enable' => true
        ]);

        // Disable Other Themes
        Theme::query()->where('enable', true)
            ->where('id', '!=', $themeItem->id)
            ->update([
                'enable' => false
            ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans("update.theme_enabled_successful"),
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl("/themes"))->with(['toast' => $toastData]);
    }

    private function makeStoreData(Request $request, $themeItem = null)
    {
        $data = $request->all();
        $contents = $this->handleContents($request, $themeItem);

        return [
            'title' => $data['title'],
            'preview_image' => $data['preview_image'],
            'default_color_mode' => $data['default_color_mode'] ?? 'light',
            'color_id' => $data['color_id'],
            'font_id' => $data['font_id'],
            'header_id' => $data['header_id'],
            'footer_id' => $data['footer_id'],
            'home_landing_id' => $data['home_landing_id'],
            'contents' => $contents,
            'enable' => (!empty($data['enable']) and $data['enable'] == 'on'),
            'created_at' => !empty($themeItem) ? $themeItem->created_at : time(),
        ];
    }


    private function handleContents(Request $request, $themeItem)
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

    public function getHomeLandingComponents(Request $request)
    {
        $this->validate($request, [
            'landing_id' => 'required|exists:landings,id',
        ]);

        $landingId = $request->get('landing_id');
        $landingItem = Landing::query()->where('enable', true)
            ->where('id', $landingId)
            ->with([
                'components' => function ($query) {
                    $query->with([
                        'landingBuilderComponent'
                    ]);
                    $query->orderBy('order', 'asc');
                }
            ])
            ->first();

        if (!empty($landingItem)) {
            $html = "";

            foreach ($landingItem->components as $component) {
                $html .= (string)view()->make('admin.theme.create.tabs.includes.landing_component_card', ['landingComponent' => $component, 'landingItemId' => $landingItem->id]);
            }

            return response()->json([
                'code' => 200,
                'html' => $html,
                'sort_url' => getLandingBuilderUrl("/{$landingItem->id}/sort-components"),
            ]);
        }

        return response()->json([], 422);
    }

}
