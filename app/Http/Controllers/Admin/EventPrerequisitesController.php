<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Prerequisite;
use Illuminate\Http\Request;

class EventPrerequisitesController extends Controller
{

    public function getForm(Request $request, $eventId)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->findOrFail($eventId);

        $locale = mb_strtolower($request->get('locale', getDefaultLocale()));

        $html = (string)view()->make('admin.events.create.contents.prerequisites.form', [
            'event' => $event,
            'locale' => $locale,
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
            'prerequisite' => 'required|unique:prerequisites,prerequisite_id,null,id,event_id,' . $eventId,
        ]);

        $storeData = $this->makeStoreData($request, $event);
        $prerequisite = Prerequisite::query()->create($storeData);

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans('update.event_prerequisite_created_successfully'),
        ]);
    }

    public function edit(Request $request, $eventId, $id)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->findOrFail($eventId);
        $prerequisite = Prerequisite::query()->where('id', $id)
            ->where('event_id', $eventId)
            ->first();

        if (!empty($prerequisite)) {
            $locale = mb_strtolower($request->get('locale', getDefaultLocale()));

            $html = (string)view()->make('admin.events.create.contents.prerequisites.form', [
                'event' => $event,
                'prerequisite' => $prerequisite,
                'locale' => $locale,
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
        $prerequisite = Prerequisite::query()->where('id', $id)
            ->where('event_id', $eventId)
            ->first();

        if (!empty($prerequisite)) {
            $this->validate($request, [
                'prerequisite' => "required|unique:prerequisites,prerequisite_id,{$id},id,event_id,{$eventId}",
            ]);

            $storeData = $this->makeStoreData($request, $event, $prerequisite);
            $prerequisite->update($storeData);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.event_prerequisite_updated_successfully'),
            ]);
        }

        return response()->json([], 422);
    }

    public function delete(Request $request, $eventId, $id)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->findOrFail($eventId);
        $prerequisite = Prerequisite::query()->where('id', $id)
            ->where('event_id', $event->id)
            ->first();

        if (!empty($prerequisite)) {
            $prerequisite->delete();

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.event_prerequisite_deleted_successfully'),
                'status' => 'success'
            ];
            return redirect()->back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    private function makeStoreData(Request $request, $event, $prerequisite = null): array
    {
        $data = $request->all();

        return [
            'webinar_id' => null,
            'event_id' => $event->id,
            'prerequisite_id' => $data['prerequisite'],
            'required' => (!empty($data['required']) and $data['required'] == 'on'),
            'created_at' => !empty($prerequisite) ? $prerequisite->created_at : time(),
        ];
    }


}
