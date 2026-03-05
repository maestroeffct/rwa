<?php

namespace App\Http\Controllers\LandingBuilder;

use App\Enums\LandingBuilderComponentsNames;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LandingBuilder\traits\LandingBuilderTrait;
use App\Http\Controllers\LandingBuilder\traits\LandingComponentsTrait;
use App\Models\Category;
use App\Models\Landing;
use App\Models\LandingComponent;
use App\Models\Role;
use App\Models\Subscribe;
use App\Models\Testimonial;
use App\Models\Translation\LandingComponentTranslation;
use App\Models\TrendCategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LandingBuilderComponentController extends Controller
{
    use LandingBuilderTrait, LandingComponentsTrait;

    protected $uploadDestination;

    public function add(Request $request, $landingId)
    {
        $this->authorize("admin_landing_builder");
        $landingItem = Landing::query()->findOrFail($landingId);

        $data = $request->all();
        $validator = Validator::make($data, [
            'component_id' => 'required|exists:landing_builder_components,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $order = LandingComponent::query()->where('landing_id', $landingId)->count();

        $landingComponent = LandingComponent::query()->create([
            'landing_id' => $landingId,
            'component_id' => $data['component_id'],
            'preview' => null,
            'enable' => false,
            'order' => $order + 1,
        ]);

        $viewData = [
            'landingComponent' => $landingComponent,
            'landingItem' => $landingItem,
        ];
        $html = (string)view()->make('landingBuilder.admin.pages.create.tabs.includes.landing_component_card', $viewData);

        return response()->json([
            'code' => 200,
            'html' => $html,
            'title' => trans('public.request_success'),
            'msg' => trans('update.component_added_to_landing_msg'),
        ]);
    }

    public function edit(Request $request, $landingId, $componentId)
    {
        $this->authorize("admin_landing_builder");

        $landingItem = Landing::query()->where('id', $landingId)
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
            $landingComponent = LandingComponent::query()->where('landing_id', $landingId)
                ->where('id', $componentId)
                ->first();

            if (!empty($landingComponent)) {

                $componentName = $landingComponent->landingBuilderComponent->name;
                $locale = mb_strtolower($request->get('locale', app()->getLocale()));

                $contents = [];
                if (!empty($landingComponent->content) and !empty($landingComponent->translate($locale)) and !empty($landingComponent->translate($locale)->content)) {
                    $contents = json_decode($landingComponent->translate($locale)->content, true);
                }

                $data = [
                    'pageTitle' => trans('update.edit_component'),
                    'locale' => $locale,
                    'landingItem' => $landingItem,
                    'landingComponent' => $landingComponent,
                    'componentName' => $componentName,
                    'contents' => $contents,
                ];
                $data = array_merge($data, $this->getExtraDataForEditPage($landingComponent));

                return view("landingBuilder.admin.components.manage.index", $data);
            }
        }

        abort(404);
    }


    /**
     * @param LandingComponent $landingComponent
     * */
    private function getExtraDataForEditPage($landingComponent): array
    {
        $data = [];

        if ($landingComponent->landingBuilderComponent->name == LandingBuilderComponentsNames::TRENDING_CATEGORIES) {
            $data['trendingCategories'] = TrendCategory::query()
                ->with([
                    'category'
                ])
                ->orderBy('created_at', 'desc')
                ->get();
        } else if ($landingComponent->landingBuilderComponent->name == LandingBuilderComponentsNames::MEETING_BOOKING_LIST) {
            $data['meetingInstructors'] = User::query()->select('id', 'full_name', 'username', 'avatar', 'avatar_settings', 'email', 'mobile')
                ->where('status', 'active')
                ->whereHas('meeting', function ($query) {
                    $query->where('disabled', false)
                        ->whereHas('meetingTimes');
                })->get();

        } else if ($landingComponent->landingBuilderComponent->name == LandingBuilderComponentsNames::SUBSCRIPTION_PLANS) {
            $data['subscriptionPlans'] = Subscribe::query()->get();
        } else if ($landingComponent->landingBuilderComponent->name == LandingBuilderComponentsNames::SLIDING_TESTIMONIALS_2_ROWS) {
            $data['testimonials'] = Testimonial::query()
                ->where('status', 'active')
                ->get();
        } else if ($landingComponent->landingBuilderComponent->name == LandingBuilderComponentsNames::ORGANIZATIONS) {
            $data['organizations'] = User::query()->select('id', 'full_name', 'username', 'avatar', 'avatar_settings', 'email', 'mobile')
                ->where('role_name', Role::$organization)
                ->where('status', 'active')
                ->get();
        } else if ($landingComponent->landingBuilderComponent->name == LandingBuilderComponentsNames::INSTRUCTORS) {
            $data['instructors'] = User::query()->select('id', 'full_name', 'username', 'avatar', 'avatar_settings', 'email', 'mobile')
                ->where('role_name', Role::$teacher)
                ->where('status', 'active')
                ->get();
        } else if ($landingComponent->landingBuilderComponent->name == LandingBuilderComponentsNames::MULTI_TAB_COURSES) {
            $data['categories'] = Category::query()
                ->where('parent_id', null)
                ->with([
                    'subCategories'
                ])
                ->orderBy('order', 'asc')
                ->get();
        }

        return $data;
    }

    public function update(Request $request, $landingId, $componentId)
    {
        $this->authorize("admin_landing_builder");

        $landingItem = Landing::query()->where('id', $landingId)
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
            $landingComponent = LandingComponent::query()->where('landing_id', $landingId)
                ->where('id', $componentId)
                ->first();

            if (!empty($landingComponent)) {
                $componentName = $landingComponent->landingBuilderComponent->name;
                $locale = $request->get('locale', app()->getLocale());

                $this->uploadDestination = "landing_builder/landing_{$landingItem->id}/{$landingComponent->id}";
                $newContents = $request->all(['contents']);
                $newContentData = $this->makeContentDataByName($request, $newContents);
                $newContentData = (!empty($newContentData['contents']) and is_array($newContentData['contents'])) ? $newContentData['contents'] : [];

                $oldContents = [];
                if (!empty($landingComponent->translate($locale)) and !empty($landingComponent->translate($locale)->content)) {
                    $oldContents = json_decode($landingComponent->translate($locale)->content, true);
                }

                $contentsData = $this->handleOldAndNewContentData($oldContents, $newContentData);

                $landingComponent->update([
                    'enable' => ($request->get('enable') == "on")
                ]);

                LandingComponentTranslation::updateOrCreate([
                    'landing_component_id' => $landingComponent->id,
                    'locale' => mb_strtolower($locale)
                ], [
                    'content' => json_encode($contentsData),
                ]);

                $toastData = [
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.component_updated_successful'),
                    'status' => 'success'
                ];

                return redirect(getLandingBuilderUrl("/{$landingItem->id}/components/{$landingComponent->id}/edit"))->with(['toast' => $toastData]);
            }
        }

        abort(404);
    }


    public function duplicate($landingId, $componentId)
    {
        $this->authorize("admin_landing_builder");

        $landingItem = Landing::query()->where('id', $landingId)
            ->first();

        if (!empty($landingItem)) {
            $landingComponent = LandingComponent::query()->where('landing_id', $landingId)
                ->where('id', $componentId)
                ->first();

            if (!empty($landingComponent)) {
                $order = LandingComponent::query()->where('landing_id', $landingId)->count();

                $newComponent = LandingComponent::query()->create([
                    'landing_id' => $landingComponent->landing_id,
                    'component_id' => $landingComponent->component_id,
                    'preview' => $landingComponent->preview,
                    'enable' => $landingComponent->enable,
                    'order' => $order + 1,
                ]);

                // Translations
                $landingComponentTranslations = LandingComponentTranslation::query()->where('landing_component_id', $landingComponent->id)->get();

                foreach ($landingComponentTranslations as $landingComponentTranslation) {
                    LandingComponentTranslation::updateOrCreate([
                        'landing_component_id' => $newComponent->id,
                        'locale' => $landingComponentTranslation->locale,
                    ], [
                        'content' => $landingComponentTranslation->content,
                    ]);
                }

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.landing_component_duplicated_successful'),
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function clearContent($landingId, $componentId)
    {
        $this->authorize("admin_landing_builder");

        $landingItem = Landing::query()->where('id', $landingId)
            ->first();

        if (!empty($landingItem)) {
            $landingComponent = LandingComponent::query()->where('landing_id', $landingId)
                ->where('id', $componentId)
                ->first();

            if (!empty($landingComponent)) {
                $landingComponent->update([
                    'enable' => false
                ]);

                LandingComponentTranslation::query()->where('landing_component_id', $landingComponent->id)
                    ->update([
                        'content' => json_encode([])
                    ]);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.landing_component_contents_cleared_successful'),
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function disable($landingId, $componentId)
    {
        $this->authorize("admin_landing_builder");

        $landingItem = Landing::query()->where('id', $landingId)
            ->first();

        if (!empty($landingItem)) {
            $landingComponent = LandingComponent::query()->where('landing_id', $landingId)
                ->where('id', $componentId)
                ->first();

            if (!empty($landingComponent)) {
                $landingComponent->update([
                    'enable' => false
                ]);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.landing_component_disabled_successful'),
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function enable($landingId, $componentId)
    {
        $this->authorize("admin_landing_builder");

        $landingItem = Landing::query()->where('id', $landingId)
            ->first();

        if (!empty($landingItem)) {
            $landingComponent = LandingComponent::query()->where('landing_id', $landingId)
                ->where('id', $componentId)
                ->first();

            if (!empty($landingComponent)) {
                $landingComponent->update([
                    'enable' => true
                ]);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.landing_component_enabled_successful'),
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function delete($landingId, $componentId)
    {
        $this->authorize("admin_landing_builder");

        $landingItem = Landing::query()->where('id', $landingId)
            ->first();

        if (!empty($landingItem)) {
            $landingComponent = LandingComponent::query()->where('landing_id', $landingId)
                ->where('id', $componentId)
                ->first();

            if (!empty($landingComponent)) {
                $uploadDestination = "landing_builder/landing_{$landingItem->id}/{$landingComponent->id}";
                $landingComponent->delete();

                Storage::deleteDirectory($uploadDestination);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.landing_component_deleted_successful'),
                ]);
            }
        }

        return response()->json([], 422);
    }
}
