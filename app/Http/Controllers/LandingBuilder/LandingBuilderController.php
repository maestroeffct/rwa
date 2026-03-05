<?php

namespace App\Http\Controllers\LandingBuilder;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LandingBuilder\traits\LandingBuilderTrait;
use App\Models\Landing;
use App\Models\LandingBuilderComponent;
use App\Models\LandingComponent;
use App\Models\Translation\LandingComponentTranslation;
use App\Models\Translation\LandingTranslation;
use Illuminate\Http\Request;

class LandingBuilderController extends Controller
{
    use LandingBuilderTrait;

    public function welcome(Request $request)
    {
        $this->authorize("admin_landing_builder");

        $data = [
            'pageTitle' => trans('update.landing_builder'),
        ];

        return view('admin.landing_builder.welcome', $data);
    }

    public function index(Request $request)
    {
        $this->authorize("admin_landing_builder");

        $data = [
            'pageTitle' => trans('update.landing_builder'),
        ];
        $data = array_merge($data, $this->getPanelCommonData($request));

        return view('landingBuilder.admin.pages.main.index', $data);
    }

    public function create(Request $request)
    {
        $this->authorize("admin_landing_builder_create");

        $data = [
            'pageTitle' => trans('update.new_landing'),
        ];
        $data = array_merge($data, $this->getPanelCommonData($request));

        return view('landingBuilder.admin.pages.create.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("admin_landing_builder_create");

        $this->validate($request, [
            'title' => 'required',
            'url' => 'required|regex:/^[a-zA-Z0-9]+$/|unique:landings,url',
        ]);

        $storeData = $this->makeStoreData($request);
        $landingItem = Landing::query()->create($storeData);

        $this->handleExtraData($request, $landingItem);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.landing_page_created_successful'),
            'status' => 'success'
        ];

        return redirect(getLandingBuilderUrl("/{$landingItem->id}/edit"))->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $id)
    {
        $this->authorize("admin_landing_builder_create");

        $landingItem = Landing::query()->where('id', $id)
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
            $locale = $request->get('locale', app()->getLocale());

            $landingBuilderComponents = LandingBuilderComponent::query()->get();

            $data = [
                'pageTitle' => trans('update.edit_landing') . ' ' . $landingItem->title,
                'landingItem' => $landingItem,
                'locale' => mb_strtolower($locale),
                'landingBuilderComponents' => $landingBuilderComponents,
            ];
            $data = array_merge($data, $this->getPanelCommonData($request));

            return view('landingBuilder.admin.pages.create.index', $data);
        }

        abort(404);
    }

    public function update(Request $request, $id)
    {
        $this->authorize("admin_landing_builder_create");

        $landingItem = Landing::query()->where('id', $id)->first();

        if (!empty($landingItem)) {
            $this->validate($request, [
                'title' => 'required',
                'url' => 'required|regex:/^[a-zA-Z0-9]+$/|unique:landings,url,' . $landingItem->id,
            ], [
                'url.regex' => trans('update.landing_page_url_validation'),
            ]);

            $storeData = $this->makeStoreData($request, $landingItem);
            $landingItem->update($storeData);

            $this->handleExtraData($request, $landingItem);

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.landing_page_updated_successful'),
                'status' => 'success'
            ];

            return redirect(getLandingBuilderUrl("/{$landingItem->id}/edit"))->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function delete(Request $request, $id)
    {
        $this->authorize("admin_landing_builder_delete");

        $landingItem = Landing::query()->where('id', $id)->first();

        if (!empty($landingItem)) {
            $landingItem->delete();

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.landing_page_deleted_successful'),
                'status' => 'success'
            ];

            return redirect()->back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    private function makeStoreData(Request $request, $landingItem = null)
    {
        $data = $request->all();

        return [
            'url' => $data['url'],
            'color_id' => $data['color_id'] ?? null,
            'enable' => (!empty($data['enable']) and $data['enable'] == 'on'),
            'created_at' => !empty($landingItem) ? $landingItem->created_at : time(),
        ];
    }

    private function handleExtraData(Request $request, $landingItem)
    {
        $data = $request->all();

        LandingTranslation::query()->updateOrCreate([
            'landing_id' => $landingItem->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
        ]);

        $previewImg = $landingItem->preview_img;
        $previewImgFile = $request->file('preview_img');

        if (!empty($previewImgFile)) {
            $destination = "landing_builder/landing_{$landingItem->id}/";

            $previewImg = $this->uploadFile($previewImgFile, $destination, 'preview_img');
        }

        $landingItem->update([
            'preview_img' => $previewImg,
        ]);
    }


    public function allLandingPages(Request $request)
    {
        $this->authorize("admin_landing_builder_all_pages");

        $statQuery = Landing::query();
        $totalLandingPages = deepClone($statQuery)->count();
        $activeLandingPages = deepClone($statQuery)->where('enable', true)->count();
        $disabledLandingPages = deepClone($statQuery)->where('enable', false)->count();
        $totalLandingComponents = LandingBuilderComponent::query()->count();


        $data = [
            'pageTitle' => trans('update.landing_pages'),
            'totalLandingPages' => $totalLandingPages,
            'activeLandingPages' => $activeLandingPages,
            'disabledLandingPages' => $disabledLandingPages,
            'totalLandingComponents' => $totalLandingComponents,
        ];
        $data = array_merge($data, $this->getPanelCommonData($request));

        return view('admin.landing_builder.landings.index', $data);
    }

    public function duplicate(Request $request, $id)
    {
        $this->authorize("admin_landing_builder_duplicate");

        $landingItem = Landing::query()->where('id', $id)->first();

        if (!empty($landingItem)) {
            $newLanding = Landing::query()->create([
                'url' => $landingItem->url . random_str(3),
                'preview_img' => $landingItem->preview_img,
                'enable' => false,
                'created_at' => time(),
            ]);

            // Landing Translations
            $oldLandingTranslations = LandingTranslation::query()->where('landing_id', $landingItem->id)->get();
            $newLandingTranslationInserts = [];

            foreach ($oldLandingTranslations as $oldLandingTranslation) {
                $newLandingTranslationInserts[] = [
                    'landing_id' => $newLanding->id,
                    'locale' => $oldLandingTranslation->locale,
                    'title' => $oldLandingTranslation->title,
                ];
            }

            LandingTranslation::query()->insert($newLandingTranslationInserts);
            // End Landing Translations

            // Landing Components
            $oldLandingComponents = LandingComponent::query()->where('landing_id', $landingItem->id)->get();

            foreach ($oldLandingComponents as $oldLandingComponent) {
                $newLandingComponent = LandingComponent::query()->create([
                    'landing_id' => $newLanding->id,
                    'component_id' => $oldLandingComponent->component_id,
                    'preview' => $oldLandingComponent->preview,
                    'enable' => $oldLandingComponent->enable,
                    'order' => $oldLandingComponent->order,
                ]);

                $oldLandingComponentTranslations = LandingComponentTranslation::query()->where('landing_component_id', $oldLandingComponent->id)->get();
                $newTranslations = [];

                foreach ($oldLandingComponentTranslations as $oldLandingComponentTranslation) {
                    $newTranslations[] = [
                        'landing_component_id' => $newLandingComponent->id,
                        'locale' => $oldLandingComponentTranslation->locale,
                        'content' => $oldLandingComponentTranslation->content,
                    ];
                }

                LandingComponentTranslation::query()->insert($newTranslations);
            }

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.landing_page_duplicated_successful'),
                'status' => 'success',
                'code' => 200,
            ];

            if ($request->ajax()) {
                return response()->json($toastData);
            }

            return redirect()->back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function sortComponents(Request $request, $id)
    {
        $landingItem = Landing::query()->where('id', $id)->first();

        if (!empty($landingItem)) {
            $assignedComponentsIds = $request->get("items");

            if (!empty($assignedComponentsIds)) {
                $assignedComponentsIds = explode(',', $assignedComponentsIds);

                foreach ($assignedComponentsIds as $order => $assignedComponentId) {
                    LandingComponent::query()->where('landing_id', $landingItem->id)
                        ->where('id', $assignedComponentId)
                        ->update([
                            'order' => $order
                        ]);
                }
            }
        }

        return response()->json([
            'code' => 200,
            'title' => trans('request_success'),
            'msg' => trans('update.landing_components_sorted_successful'),
        ]);
    }

    public function componentPreview($name)
    {
        $html = (string)view()->make('landingBuilder.admin.includes.modals.component_preview', [
            'name' => $name,
        ]);

        return response()->json([
            'code' => 200,
            'html' => $html,
        ]);
    }

}
