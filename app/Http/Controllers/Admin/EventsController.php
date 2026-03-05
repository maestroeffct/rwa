<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MorphTypesEnum;
use App\Exports\EventsListsExport;
use App\Http\Controllers\Admin\traits\ProductBadgeTrait;
use App\Http\Controllers\Admin\traits\SpecificLocationsTrait;
use App\Http\Controllers\Controller;
use App\Mail\SendNotifications;
use App\Mixins\Events\EventNotificationsMixins;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventFilterOption;
use App\Models\Notification;
use App\Models\Tag;
use App\Models\Translation\EventTranslation;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EventsController extends Controller
{
    use ProductBadgeTrait, SpecificLocationsTrait;

    public function index(Request $request)
    {
        $this->authorize("admin_events_lists");

        $query = Event::query()
            ->withMin('tickets', 'price')
            ->withMax('tickets', 'price');

        $topStats = $this->handleTopStats(deepClone($query));

        $events = $this->handleFilters($request, $query)
            ->with([
                'category',
                'creator' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'mobile', 'email', 'username', 'avatar', 'avatar_settings', 'bio', 'about', 'verified');
                },
            ])
            ->paginate(10);

        $data = [
            'pageTitle' => trans('update.events'),
            'events' => $events,
        ];
        $data = array_merge($data, $topStats);

        return view('admin.events.lists.index', $data);

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

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $search = $request->get('search');
        $instructorIds = $request->get('instructor_ids', []);
        $type = $request->get('type');
        $status = $request->get('status');
        $sort = $request->get('sort');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($search)) {
            $query->whereTranslationLike('title', '%' . $search . '%');
        }

        if (!empty($instructorIds) and count($instructorIds)) {
            $query->whereIn('creator_id', $instructorIds);
        }

        if (!empty($type)) {
            $query->where('type', $type);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'create_date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'create_date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'highest_base_price':
                    $query->orderBy('tickets_max_price', 'desc');
                    break;
                case 'lowest_base_price':
                    $query->orderBy('tickets_min_price', 'asc');
                    break;
                case 'best_sellers':
                    $query->selectRaw('COUNT(event_tickets_sold.id) as total_sales')
                        ->leftJoin('event_tickets', 'event_tickets.event_id', '=', 'events.id')
                        ->leftJoin('event_tickets_sold', 'event_tickets_sold.event_ticket_id', '=', 'event_tickets.id') // change to ->join to get only have a sold tickets
                        ->groupBy('events.id')
                        ->orderBy('total_sales', 'desc');
                    break;
                case 'top_rated':
                    $query->select("events.*", DB::raw('avg(rates) as rates'))
                        ->leftJoin('webinar_reviews', function ($join) {
                            $join->on("events.id", '=', "webinar_reviews.event_id");
                            $join->where('webinar_reviews.status', 'active');
                        })
                        //->whereNotNull('rates')
                        ->groupBy('events.id')
                        ->orderBy('rates', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    private function getCreatePageData(): array
    {
        $categories = Category::where('parent_id', null)->get();

        return [
            'categories' => $categories,
        ];
    }

    public function create(Request $request)
    {
        $this->authorize("admin_events_create");

        $data = [
            'pageTitle' => trans('update.new_event')
        ];
        $data = array_merge($data, $this->getCreatePageData());

        return view('admin.events.create.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("admin_events_create");

        $this->validate($request, [
            'type' => 'required|in:in_person,online',
            'title' => 'required|max:255',
            'slug' => 'nullable|max:255|unique:events,slug',
            'subtitle' => 'required|max:255',
            'creator_id' => 'required|exists:users,id',
            'thumbnail' => 'required|max:255',
            'cover_image' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'seo_description' => 'required|string',
            'summary' => 'required|string',
            'description' => 'required|string',
        ]);

        $storeData = $this->makeStoreData($request);
        $event = Event::query()->create($storeData);

        $this->handleStoreExtraData($request, $event);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.event_created_successfully'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/events/{$event->id}/edit"))->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $id)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->where('id', $id)
            ->with([
                'tickets' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'speakers' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'prerequisites' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'faqs' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'extraDescriptions' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
            ])
            ->first();

        if (!empty($event)) {
            $locale = mb_strtolower($request->get('locale', getDefaultLocale()));

            $data = [
                'pageTitle' => trans('update.edit_event'),
                'event' => $event,
                'locale' => $locale,
            ];
            $data = array_merge($data, $this->getCreatePageData());

            return view('admin.events.create.index', $data);
        }

        abort(404);
    }

    public function update(Request $request, $id)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->findOrFail($id);

        $this->validate($request, [
            'type' => 'required|in:in_person,online',
            'title' => 'required|max:255',
            'slug' => 'nullable|max:255|unique:events,slug,' . $event->id,
            'subtitle' => 'required|max:255',
            'creator_id' => 'required|exists:users,id',
            'thumbnail' => 'required|max:255',
            'cover_image' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'seo_description' => 'required|string',
            'summary' => 'required|string',
            'description' => 'required|string',
        ]);

        $storeData = $this->makeStoreData($request, $event);
        $event->update($storeData);

        $this->handleStoreExtraData($request, $event);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.event_updated_successfully'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/events/{$event->id}/edit"))->with(['toast' => $toastData]);
    }

    private function makeStoreData(Request $request, $event = null): array
    {
        $data = $request->all();

        if (empty($data['timezone'])) {
            $data['timezone'] = getTimezone();
        }


        return [
            'type' => $data['type'],
            'slug' => !empty($data['slug']) ? $data['slug'] : Event::makeSlug($data['title']),
            'creator_id' => $data['creator_id'],
            'category_id' => $data['category_id'],
            'thumbnail' => $data['thumbnail'] ?? null,
            'cover_image' => $data['cover_image'] ?? null,
            'icon' => $data['icon'] ?? null,
            'video_demo_source' => (!empty($data['video_demo']) and !empty($data['video_demo_source'])) ? $data['video_demo_source'] : null,
            'video_demo' => $data['video_demo'] ?? null,
            'sales_count_number' => !empty($data['sales_count_number']) ? $data['sales_count_number'] : null,
            'capacity' => !empty($data['capacity']) ? $data['capacity'] : null,
            'purchase_limit_count' => !empty($data['purchase_limit_count']) ? $data['purchase_limit_count'] : null,
            'duration' => !empty($data['duration']) ? $data['duration'] : null,
            'start_date' => !empty($data['start_date']) ? convertTimeToUTCzone($data['start_date'], $data['timezone'])->getTimestamp() : null,
            'end_date' => !empty($data['end_date']) ? convertTimeToUTCzone($data['end_date'], $data['timezone'])->getTimestamp() : null,
            'sales_end_date' => !empty($data['sales_end_date']) ? convertTimeToUTCzone($data['sales_end_date'], $data['timezone'])->getTimestamp() : null,
            'enable_countdown' => (!empty($data['enable_countdown']) and $data['enable_countdown'] == "on"),
            'countdown_time_reference' => $data['countdown_time_reference'] ?? null,
            'timezone' => $data['timezone'],
            'support' => (!empty($data['support']) and $data['support'] == "on"),
            'certificate' => (!empty($data['certificate']) and $data['certificate'] == "on"),
            'private' => (!empty($data['private']) and $data['private'] == "on"),
            'message_for_reviewer' => $data['message_for_reviewer'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'created_at' => !empty($event) ? $event->created_at : time(),
            'updated_at' => time(),
        ];
    }

    private function handleStoreExtraData(Request $request, $event)
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

        // Product Badge
        $this->handleProductBadges($event, $data);

        // Handle Specific Location
        $locationData = ($event->type == "in_person" and !empty($data['specificLocation']) and is_array($data['specificLocation'])) ? $data['specificLocation'] : [];
        $this->updateOrCreateSpecificLocation($event->id, MorphTypesEnum::EVENT, $locationData);
    }

    public function delete(Request $request, $id)
    {
        $this->authorize("admin_events_delete");
        $event = Event::query()->findOrFail($id);

        $event->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.event_deleted_successfully'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/events"))->with(['toast' => $toastData]);
    }

    public function changeStatus($id, $status)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->findOrFail($id);

        $newStatus = $event->status;

        switch ($status) {
            case 'publish':
                $newStatus = 'publish';
                break;
            case 'reject':
                $newStatus = 'rejected';
                break;
            case 'unpublish':
                $newStatus = 'unpublish';
                break;
            case 'cancel':
                $newStatus = 'canceled';
                break;
        }

        $event->update([
            'status' => $newStatus
        ]);

        $eventNotificationsMixins = (new EventNotificationsMixins());

        if ($newStatus == "canceled") {
            $eventNotificationsMixins->sendCanceledEventNotification($event);
        }

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.event_status_changed_successfully'),
            'status' => 'success'
        ];
        return redirect()->back()->with(['toast' => $toastData]);
    }

    public function search(Request $request)
    {
        $term = $request->get('term');

        $events = Event::select('id')
            ->whereTranslationLike('title', "%$term%")
            ->get();

        $result = [];
        foreach ($events as $item) {
            $result[] = [
                'id' => $item->id,
                'title' => $item->title,
            ];
        }

        return response()->json($result, 200);
    }

    public function exportExcel(Request $request)
    {
        $this->authorize("admin_events_lists_export_excel");

        $query = Event::query()
            ->withMin('tickets', 'price')
            ->withMax('tickets', 'price');

        $events = $this->handleFilters($request, $query)
            ->with([
                'category',
                'creator' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio', 'about', 'verified', 'cover_img', 'profile_secondary_image');
                },
            ])
            ->get();

        $export = new EventsListsExport($events);
        return Excel::download($export, 'events.xlsx');
    }

    public function notificationToStudents($id)
    {
        $this->authorize('admin_event_send_notification');

        $event = Event::findOrFail($id);

        $data = [
            'pageTitle' => trans('notification.send_notification'),
            'event' => $event
        ];

        return view('admin.events.send_notification.index', $data);
    }

    public function sendNotificationToStudents(Request $request, $id)
    {
        $this->authorize('admin_event_send_notification');

        $this->validate($request, [
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        $data = $request->all();

        $event = Event::findOrFail($id);
        $studentsIds = $event->getAllStudentsIds();

        if (count($studentsIds)) {
            foreach ($studentsIds as $studentId) {
                $student = User::query()->select('id', 'full_name', 'role_name', 'role_id', 'mobile', 'email', 'username')
                    ->where('id', $studentId)
                    ->first();

                if (!empty($student)) {
                    Notification::query()->create([
                        'user_id' => $student->id,
                        'group_id' => null,
                        'sender_id' => auth()->id(),
                        'title' => $data['title'],
                        'message' => $data['message'],
                        'sender' => Notification::$AdminSender,
                        'type' => 'single',
                        'created_at' => time()
                    ]);

                    if (!empty($student->email) and env('APP_ENV') == 'production') {
                        \Mail::to($student->email)->send(new SendNotifications(['title' => $data['title'], 'message' => $data['message']]));
                    }
                }
            }

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.the_notification_was_successfully_sent_to_n_students', ['count' => count($studentsIds)]),
                'status' => 'success'
            ];

            return redirect()->back()->with(['toast' => $toastData]);
        }

        $toastData = [
            'title' => trans('public.request_failed'),
            'msg' => trans('update.this_event_has_no_students'),
            'status' => 'error'
        ];

        return redirect()->back()->with(['toast' => $toastData]);
    }

}
