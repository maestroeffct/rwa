<?php

namespace App\Http\Controllers\Panel;

use App\Enums\MorphTypesEnum;
use App\Enums\UploadSource;
use App\Http\Controllers\Admin\traits\SpecificLocationsTrait;
use App\Http\Controllers\Controller;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventFilterOption;
use App\Models\Tag;
use App\Models\Translation\EventTranslation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventsController extends Controller
{
    use SpecificLocationsTrait;

    public function index(Request $request)
    {
        $this->authorize("panel_events_lists");

        $user = auth()->user();

        $query = Event::query()->where('creator_id', $user->id);

        $copyQuery = deepClone($query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $topStats = $this->handleTopStats($copyQuery);


        $data = [
            'pageTitle' => trans('update.my_events'),
        ];
        $data = array_merge($data, $topStats);
        $data = array_merge($data, $getListData);

        return view('design_1.panel.events.my_events.index', $data);
    }

    private function handleTopStats(Builder $query): array
    {
        $time = time();

        $totalEventsCount = deepClone($query)->count();

        $totalEndedEvents = deepClone($query)
            ->where(function (Builder $query) use ($time) {
                $query->whereNotNull("end_date");
                $query->where('end_date', "<", $time);
            })
            ->count();

        $totalScheduledEvents = deepClone($query)
            ->where('start_date', ">", $time)
            ->count();

        $totalDraftEvents = deepClone($query)->where('status', 'draft')->count();

        return [
            'totalEventsCount' => $totalEventsCount,
            'totalEndedEvents' => $totalEndedEvents,
            'totalScheduledEvents' => $totalScheduledEvents,
            'totalDraftEvents' => $totalDraftEvents,
        ];
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $events = $query
            ->with([
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                },
            ])
            ->withCount([
                'tickets' => function ($query) {
                    $query->where('enable', true);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        /*foreach ($events as $event) {

        }*/

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $events, $total, $count);
        }

        return [
            'events' => $events,
            'pagination' => $this->makePagination($request, $events, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $events, $total, $count)
    {
        $html = "";

        foreach ($events as $eventRow) {
            $html .= (string)view()->make("design_1.panel.events.my_events.event_card.index", ['event' => $eventRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $events, $total, $count, true)
        ]);
    }

    private function getCreateWizardStepCount()
    {
        $steps = 6;

        if (!empty(getGeneralOptionsSettings('direct_publication_of_events'))) {
            $steps -= 1;
        }

        return $steps;
    }

    public function create()
    {
        $this->authorize("panel_events_create");

        $user = auth()->user();

        $userPackage = new UserPackage($user);
        $userEventsCountLimited = $userPackage->checkPackageLimit('events_count');

        if ($userEventsCountLimited) {
            session()->put('registration_package_limited', $userEventsCountLimited);

            return redirect()->back();
        }

        $isOrganization = $user->isOrganization();
        $stepCount = $this->getCreateWizardStepCount();

        $data = [
            'pageTitle' => trans('update.new_event'),
            'currentStep' => 1,
            'stepCount' => $stepCount,
            'isOrganization' => $isOrganization,
        ];

        return view('design_1.panel.events.create.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("panel_events_create");

        $user = auth()->user();

        $userPackage = new UserPackage($user);
        $userEventsCountLimited = $userPackage->checkPackageLimit('events_count');

        if ($userEventsCountLimited) {
            session()->put('registration_package_limited', $userEventsCountLimited);

            return redirect()->back();
        }


        $this->validate($request, [
            'locale' => 'required',
            'type' => 'required|in:in_person,online',
            'title' => 'required|max:255',
            'subtitle' => 'required|max:255',
            'thumbnail' => 'required',
            'cover_image' => 'required',
            'seo_description' => 'required|string',
            'summary' => 'required|string',
            'description' => 'required|string',
        ]);

        $data = $request->all();

        $event = Event::create([
            'type' => $data['type'],
            'slug' => Event::makeSlug($data['title']),
            'creator_id' => $user->id,
            'status' => ((!empty($data['draft']) and $data['draft'] == 1) or (!empty($data['get_next']) and $data['get_next'] == 1)) ? 'draft' : 'pending',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        // Handle Image and Video
        $event = $this->storeEventMedia($request, $event);

        $this->handleTranslatesData($request, $event, $user);

        $notifyOptions = [
            '[u.name]' => $user->full_name,
            '[item_title]' => $event->title,
            '[content_type]' => trans('update.event'),
        ];
        sendNotification("new_item_created", $notifyOptions, 1);


        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.event_created_successfully'),
            'status' => 'success'
        ];
        return redirect("/panel/events/{$event->id}/step/2")->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $eventId, $step = 1)
    {
        $this->authorize("panel_events_create");

        $stepCount = $this->getCreateWizardStepCount();

        if ($step > $stepCount) {
            return redirect("/panel/events/{$eventId}/step/{$stepCount}");
        }


        $user = auth()->user();

        $eventQuery = Event::query()->where('id', $eventId)->where('creator_id', $user->id);

        if ($step == 3) {
            $eventQuery->with([
                'tickets' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
            ]);
        } else if ($step == 4) {
            $eventQuery->with([
                'prerequisites' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
            ]);
        } else if ($step == 5) {
            $eventQuery->with([
                'speakers' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'faqs' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'specificLocation'
            ]);
        }

        $event = $eventQuery->first();

        if (!empty($event)) {
            $locale = $request->get('locale', app()->getLocale());
            $isOrganization = $user->isOrganization();
            $stepCount = $this->getCreateWizardStepCount();


            $data = [
                'pageTitle' => trans('public.edit') . ' | ' . $event->title,
                'currentStep' => $step,
                'stepCount' => $stepCount,
                'isOrganization' => $isOrganization,
                'locale' => mb_strtolower($locale),
                'event' => $event,
            ];


            if ($step == 2) {
                $data['eventTags'] = $event->tags->pluck('title')->toArray();

                $eventCategoryFilters = !empty($event->category) ? $event->category->filters : [];

                if (empty($event->category) and !empty($request->old('category_id'))) {
                    $category = Category::where('id', $request->old('category_id'))->first();

                    if (!empty($category)) {
                        $eventCategoryFilters = $category->filters;
                    }
                }

                $data['eventCategoryFilters'] = $eventCategoryFilters;
            }

            return view('design_1.panel.events.create.index', $data);
        }

        abort(404);
    }

    public function update(Request $request, $eventId)
    {
        $this->authorize("panel_events_create");

        $user = auth()->user();

        $event = Event::query()->where('id', $eventId)
            ->where('creator_id', $user->id)
            ->first();

        if (empty($event)) {
            abort(404);
        }

        $data = $request->all();
        $rules = [];
        $currentStep = $data['current_step'];
        $getStep = $data['get_step'];
        $getNextStep = (!empty($data['get_next']) and $data['get_next'] == 1);
        $isDraft = (!empty($data['draft']) and $data['draft'] == 1);
        $stepCount = $this->getCreateWizardStepCount();

        if ($currentStep == 1) {
            $rules = [
                'type' => 'required|in:in_person,online',
                'title' => 'required|max:255',
                'subtitle' => 'required|max:255',
                'seo_description' => 'required|string',
                'summary' => 'required|string',
                'description' => 'required|string',
            ];
        } else if ($currentStep == 2) {
            $rules = [
                'category_id' => 'required|exists:categories,id',
                'capacity' => 'nullable|numeric',
                'duration' => 'nullable|numeric',
                'start_date' => 'required',
                'end_date' => 'required',
                'sales_end_date' => 'required',
            ];
        }

        $this->validate($request, $rules);

        $directPublication = !empty(getGeneralOptionsSettings('direct_publication_of_events'));
        $eventRulesRequired = false;

        if (!$directPublication and (($currentStep == $stepCount and !$getNextStep and !$isDraft) or (!$getNextStep and !$isDraft))) {
            $eventRulesRequired = empty($data['rules']);
        }

        $status = ($isDraft or $eventRulesRequired) ? 'draft' : 'pending';

        if ($directPublication and !$getNextStep and !$isDraft) {
            $status = 'publish';
        }

        $data['status'] = $status;

        if ($currentStep == 1) {
            // Handle Image and Video
            $event = $this->storeEventMedia($request, $event);

            // Translates
            $this->handleTranslatesData($request, $event, $user);
        } else if ($currentStep == 2) {
            if (empty($data['timezone'])) {
                $data['timezone'] = getTimezone();
            }

            $data['start_date'] = !empty($data['start_date']) ? convertTimeToUTCzone($data['start_date'], $data['timezone'])->getTimestamp() : null;
            $data['end_date'] = !empty($data['end_date']) ? convertTimeToUTCzone($data['end_date'], $data['timezone'])->getTimestamp() : null;
            $data['sales_end_date'] = !empty($data['sales_end_date']) ? convertTimeToUTCzone($data['sales_end_date'], $data['timezone'])->getTimestamp() : null;
            $data['enable_countdown'] = (!empty($data['enable_countdown']) and $data['enable_countdown'] == "on");
            $data['support'] = (!empty($data['support']) and $data['support'] == "on");
            $data['certificate'] = (!empty($data['certificate']) and $data['certificate'] == "on");
            $data['private'] = (!empty($data['private']) and $data['private'] == "on");


            // Filters & Tags
            $this->handleCategoryFiltersAndTags($request, $event);
        } else if ($currentStep == 5) {

            // Handle Specific Location
            $locationData = ($event->type == "in_person" and !empty($data['specificLocation']) and is_array($data['specificLocation'])) ? $data['specificLocation'] : [];
            $this->updateOrCreateSpecificLocation($event->id, MorphTypesEnum::EVENT, $locationData);

            // Handle Company Logos
            $webinarExtraDescriptionController = (new WebinarExtraDescriptionController());
            $webinarExtraDescriptionController->storeCompanyLogos($request, 'event_id', $event->id, 'events');
        }

        unset($data['_token'],
            $data['current_step'],
            $data['draft'],
            $data['get_step'],
            $data['get_next'],
            $data['tags'],
            $data['filters'],
            $data['ajax'],
            $data['locale'],
            $data['title'],
            $data['subtitle'],
            $data['seo_description'],
            $data['summary'],
            $data['description'],
        );

        $event->update($data);

        $url = '/panel/events';

        if ($getNextStep) {
            $nextStep = (!empty($getStep) and $getStep > 0) ? $getStep : $currentStep + 1;

            $url = '/panel/events/' . $event->id . '/step/' . (($nextStep <= $stepCount) ? $nextStep : $stepCount);
        }

        if ($status != "publish" and !$getNextStep and !$isDraft and !$eventRulesRequired) {
            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[item_title]' => $event->title,
                '[content_type]' => trans('update.event'),
            ];
            sendNotification("content_review_request", $notifyOptions, 1);
        }

        if ($eventRulesRequired) {
            $url = "/panel/events/{$event->id}/step/{$stepCount}";

            return redirect($url)->withErrors(['rules' => trans('validation.required', ['attribute' => 'rules'])]);
        }

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.event_updated_successfully'),
            'status' => 'success'
        ];
        return redirect($url)->with(['toast' => $toastData]);
    }

    public function delete(Request $request, $eventId)
    {
        $this->authorize("panel_events_create");

        if (!canDeleteContentDirectly()) {
            if ($request->ajax()) {
                return response()->json([], 422);
            } else {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('update.it_is_not_possible_to_delete_the_content_directly'),
                    'status' => 'error'
                ];
                return redirect()->back()->with(['toast' => $toastData]);
            }
        }

        $user = auth()->user();

        $event = Event::where('id', $eventId)
            ->where('author_id', $user->id)
            ->first();

        if (!empty($event)) {
            $event->delete();
        }

        return response()->json([
            'code' => 200,
        ]);
    }

    private function handleTranslatesData(Request $request, $event, $user)
    {
        $data = $request->all();

        EventTranslation::query()->updateOrCreate([
            'event_id' => $event->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'subtitle' => $data['subtitle'],
            'seo_description' => $data['seo_description'] ?? null,
            'summary' => $data['summary'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
    }

    private function handleCategoryFiltersAndTags(Request $request, $event)
    {
        // Category Filter Options
        EventFilterOption::query()->where('event_id', $event->id)->delete();

        $filters = $request->get('filters', null);
        if (!empty($filters) and is_array($filters)) {
            foreach ($filters as $filter) {
                EventFilterOption::query()->create([
                    'event_id' => $event->id,
                    'filter_option_id' => $filter
                ]);
            }
        }

        // Tags
        Tag::query()->where('event_id', $event->id)->delete();

        if (!empty($request->get('tags'))) {
            $tags = explode(',', $request->get('tags'));

            foreach ($tags as $tag) {
                Tag::query()->create([
                    'event_id' => $event->id,
                    'title' => $tag,
                ]);
            }
        }
    }

    protected function storeEventMedia(Request $request, $event)
    {
        $thumbnail = $event->thumbnail ?? null;
        $imageCover = $event->cover_image ?? null;
        $iconPath = $event->icon ?? null;
        $videoDemoSource = $event->video_demo_source ?? null;
        $videoDemo = $event->video_demo ?? null;


        if (!empty($request->file('thumbnail'))) {
            $thumbnail = $this->uploadFile($request->file('thumbnail'), "events/{$event->id}", 'thumbnail', $event->creator_id);
        }

        if (!empty($request->file('cover_image'))) {
            $imageCover = $this->uploadFile($request->file('cover_image'), "events/{$event->id}", 'cover_image', $event->creator_id);
        }

        if (!empty($request->file('icon'))) {
            $iconPath = $this->uploadFile($request->file('icon'), "events/{$event->id}", 'icon', $event->creator_id);
        }


        if (in_array($request->get('video_demo_source'), UploadSource::urlPathItems) and !empty($request->get('demo_video_path'))) {
            $videoDemoSource = $request->get('video_demo_source');
            $videoDemo = $request->get('demo_video_path');
        } elseif ($request->get('video_demo_source') == UploadSource::UPLOAD and !empty($request->file('demo_video_local'))) {
            $videoDemoSource = UploadSource::UPLOAD;
            $videoDemo = $this->uploadFile($request->file('demo_video_local'), "events/{$event->id}", 'video', $event->creator_id);
        } elseif ($request->get('video_demo_source') == UploadSource::S3 and !empty($request->file('demo_video_local'))) {
            $videoDemoSource = UploadSource::S3;
            $videoDemo = $this->uploadFile($request->file('demo_video_local'), "events/{$event->id}", 'video', $event->creator_id, 'minio');
        } elseif ($request->get('video_demo_source') == UploadSource::SECURE_HOST and !empty($request->file('demo_video_local'))) {
            $videoDemoSource = UploadSource::SECURE_HOST;
            $videoDemo = $this->uploadFile($request->file('demo_video_local'), "events/{$event->id}", "event_{$event->id}_video_demo", $event->creator_id, 'bunny');
        }

        $event->update([
            'thumbnail' => $thumbnail,
            'cover_image' => $imageCover,
            'icon' => $iconPath,
            'video_demo_source' => $videoDemoSource,
            'video_demo' => $videoDemo,
        ]);

        return $event;
    }

    public function getContentItemByLocale(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'item_id' => 'required',
            'locale' => 'required',
            'relation' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth()->user();

        $event = Event::query()->where('id', $id)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($event)) {

            $itemId = $data['item_id'];
            $locale = $data['locale'];
            $relation = $data['relation'];

            if (!empty($event->$relation)) {
                $item = $event->$relation->where('id', $itemId)->first();

                if (!empty($item)) {
                    foreach ($item->translatedAttributes as $attribute) {
                        try {
                            $item->$attribute = $item->translate(mb_strtolower($locale))->$attribute;
                        } catch (\Exception $e) {
                            $item->$attribute = null;
                        }
                    }

                    return response()->json([
                        'item' => $item
                    ], 200);
                }
            }
        }

        abort(403);
    }

    public function search(Request $request)
    {
        $user = auth()->user();

        if (!$user->isTeacher() and !$user->isOrganization()) {
            return response('', 422);
        }

        $term = $request->get('term', null);
        $eventId = $request->get('event_id', null);
        $option = $request->get('option', null);

        if (!empty($term)) {
            $query = Event::query()->select('id', 'creator_id')
                ->whereTranslationLike('title', '%' . $term . '%')
                ->where('id', '<>', $eventId)
                ->with([
                    'creator' => function ($query) {
                        $query->select('id', 'full_name');
                    }
                ]);
            //->where('creator_id', $user->id)
            //->get();

            $events = $query->get();

            $result = [];

            foreach ($events as $event) {
                $result[] = [
                    'id' => $event->id,
                    'title' => $event->title . ' - ' . $event->creator->full_name,
                ];
            }

            return response()->json($result, 200);
        }

        return response('', 422);
    }

}
