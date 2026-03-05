<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\WebinarExtraDescription;
use App\Models\Translation\WebinarExtraDescriptionTranslation;
use Illuminate\Http\Request;

class EventExtraDescriptionController extends Controller
{

    public function getForm(Request $request, $eventId)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->findOrFail($eventId);

        $locale = mb_strtolower($request->get('locale', getDefaultLocale()));
        $type = $request->get('type', \App\Models\WebinarExtraDescription::$LEARNING_MATERIALS);

        $html = (string)view()->make('admin.events.create.contents.extra_description.form', [
            'event' => $event,
            'locale' => $locale,
            'type' => $type,
        ]);

        return response()->json([
            'code' => 200,
            'html' => $html,
        ]);
    }


    public function store(Request $request, $eventId)
    {
        $this->authorize("admin_events_create");

        $event = Event::query()->findOrFail($eventId);

        $this->validate($request, [
            'type' => 'required|in:' . implode(',', WebinarExtraDescription::$types),
            'value' => 'required',
        ]);

        $storeData = $this->makeStoreData($request, $event);
        $extraDescription = WebinarExtraDescription::query()->create($storeData);

        $this->handleStoreExtraData($request, $extraDescription);

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans("update.event_{$extraDescription->type}_created_successfully"),
        ]);
    }

    public function edit(Request $request, $eventId, $id)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->findOrFail($eventId);
        $extraDescription = WebinarExtraDescription::query()->where('id', $id)
            ->where('event_id', $eventId)
            ->first();

        if (!empty($extraDescription)) {
            $locale = mb_strtolower($request->get('locale', getDefaultLocale()));

            $html = (string)view()->make('admin.events.create.contents.extra_description.form', [
                'event' => $event,
                'extraDescription' => $extraDescription,
                'locale' => $locale,
                'type' => $extraDescription->type,
            ]);

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 422);
    }

    public function update(Request $request, $eventId, $id)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->findOrFail($eventId);
        $extraDescription = WebinarExtraDescription::query()->where('id', $id)
            ->where('event_id', $eventId)
            ->first();

        if (!empty($extraDescription)) {
            $this->validate($request, [
                'type' => 'required|in:' . implode(',', WebinarExtraDescription::$types),
                'value' => 'required',
            ]);

            $storeData = $this->makeStoreData($request, $event, $extraDescription);
            $extraDescription->update($storeData);

            $this->handleStoreExtraData($request, $extraDescription);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans("update.event_{$extraDescription->type}_updated_successfully"),
            ]);
        }

        return response()->json([], 422);
    }

    public function delete(Request $request, $eventId, $id)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->findOrFail($eventId);
        $extraDescription = WebinarExtraDescription::query()->where('id', $id)
            ->where('event_id', $event->id)
            ->first();

        if (!empty($extraDescription)) {
            $extraDescription->delete();

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans("update.event_{$extraDescription->type}_deleted_successfully"),
                'status' => 'success'
            ];
            return redirect()->back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    private function makeStoreData(Request $request, $event, $extraDescription = null): array
    {
        $data = $request->all();

        if (empty($data['locale'])) {
            $data['locale'] = getDefaultLocale();
        }

        $order = WebinarExtraDescription::query()->where('event_id', $event->id)
                ->where('type', $data['type'])
                ->count() + 1;

        return [
            'creator_id' => $event->creator_id,
            'event_id' => $event->id,
            'type' => $data['type'],
            'order' => $order,
            'created_at' => !empty($extraDescription) ? $extraDescription->created_at : time(),
        ];
    }

    private function handleStoreExtraData(Request $request, $extraDescription)
    {
        $data = $request->all();
        if (empty($data['locale'])) {
            $data['locale'] = getDefaultLocale();
        }

        WebinarExtraDescriptionTranslation::query()->updateOrCreate([
            'webinar_extra_description_id' => $extraDescription->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'value' => $data['value'],
        ]);


    }
}
