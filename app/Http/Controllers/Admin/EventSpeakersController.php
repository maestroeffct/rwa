<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSpeaker;
use App\Models\Translation\EventSpeakerTranslation;
use Illuminate\Http\Request;

class EventSpeakersController extends Controller
{

    public function getForm(Request $request, $eventId)
    {
        $this->authorize("admin_events_speakers");
        $event = Event::query()->findOrFail($eventId);

        $locale = mb_strtolower($request->get('locale', getDefaultLocale()));

        $html = (string)view()->make('admin.events.create.contents.speakers.form', [
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
        $this->authorize("admin_events_speakers");

        $event = Event::query()->findOrFail($eventId);

        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $storeData = $this->makeStoreData($request, $event);
        $speaker = EventSpeaker::query()->create($storeData);

        $this->handleStoreExtraData($request, $speaker);

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans('update.event_speaker_created_successfully'),
        ]);
    }

    public function edit(Request $request, $eventId, $id)
    {
        $this->authorize("admin_events_speakers");
        $event = Event::query()->findOrFail($eventId);
        $speaker = EventSpeaker::query()->where('id', $id)
            ->where('event_id', $eventId)
            ->first();

        if (!empty($speaker)) {
            $locale = mb_strtolower($request->get('locale', getDefaultLocale()));

            $html = (string)view()->make('admin.events.create.contents.speakers.form', [
                'event' => $event,
                'speaker' => $speaker,
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
        $this->authorize("admin_events_speakers");
        $event = Event::query()->findOrFail($eventId);
        $speaker = EventSpeaker::query()->where('id', $id)
            ->where('event_id', $eventId)
            ->first();

        if (!empty($speaker)) {
            $this->validate($request, [
                'name' => 'required|max:255',
            ]);

            $storeData = $this->makeStoreData($request, $event, $speaker);
            $speaker->update($storeData);

            $this->handleStoreExtraData($request, $speaker);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.event_speaker_updated_successfully'),
            ]);
        }

        return response()->json([], 422);
    }

    public function delete(Request $request, $eventId, $id)
    {
        $this->authorize("admin_events_speakers");
        $event = Event::query()->findOrFail($eventId);
        $speaker = EventSpeaker::query()->where('id', $id)
            ->where('event_id', $event->id)
            ->first();

        if (!empty($speaker)) {
            $speaker->delete();

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.event_speaker_deleted_successfully'),
                'status' => 'success'
            ];
            return redirect()->back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    private function makeStoreData(Request $request, $event, $speaker = null): array
    {
        $data = $request->all();

        $order = !empty($speaker) ? $speaker->order : EventSpeaker::query()->where('event_id', $event->id)->count() + 1;

        return [
            'event_id' => $event->id,
            'image' => !empty($data['image']) ? $data['image'] : null,
            'link' => !empty($data['link']) ? $data['link'] : null,
            'order' => $order,
            'enable' => (!empty($data['enable']) and $data['enable'] == 'on'),
            'created_at' => !empty($speaker) ? $speaker->created_at : time(),
        ];
    }

    private function handleStoreExtraData(Request $request, $speaker)
    {
        $data = $request->all();

        EventSpeakerTranslation::query()->updateOrCreate([
            'event_speaker_id' => $speaker->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'name' => $data['name'],
            'job' => $data['job'] ?? null,
            'description' => $data['description'] ?? null,
        ]);


    }
}
