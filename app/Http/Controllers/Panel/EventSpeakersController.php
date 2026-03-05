<?php

namespace App\Http\Controllers\Panel;


use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSpeaker;
use App\Models\Translation\EventSpeakerTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventSpeakersController extends Controller
{

    public function store(Request $request, $eventId)
    {
        $user = auth()->user();
        $event = Event::query()->where('id', $eventId)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($event)) {
            $data = $request->get('ajax')['new'];

            $validator = Validator::make($data, [
                'name' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return response([
                    'code' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $storeData = $this->makeStoreData($data, $event);
            $speaker = EventSpeaker::query()->create($storeData);

            $this->handleStoreExtraData($request, $data, $speaker, $event);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.event_speaker_created_successfully'),
            ]);
        }

        return response()->json([], 422);
    }

    public function update(Request $request, $eventId, $id)
    {
        $user = auth()->user();
        $event = Event::query()->where('id', $eventId)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($event)) {
            $speaker = EventSpeaker::query()->where('id', $id)
                ->where('event_id', $eventId)
                ->first();

            if (!empty($speaker)) {
                $data = $request->get('ajax')[$id];

                $validator = Validator::make($data, [
                    'name' => 'required|max:255',
                ]);

                if ($validator->fails()) {
                    return response([
                        'code' => 422,
                        'errors' => $validator->errors(),
                    ], 422);
                }

                $storeData = $this->makeStoreData($data, $event, $speaker);
                $speaker->update($storeData);

                $this->handleStoreExtraData($request, $data, $speaker, $event, $id);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.event_speaker_updated_successfully'),
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function delete($eventId, $id)
    {
        $user = auth()->user();
        $event = Event::query()->where('id', $eventId)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($event)) {
            $speaker = EventSpeaker::query()->where('id', $id)
                ->where('event_id', $eventId)
                ->first();

            if (!empty($speaker)) {
                $speaker->delete();

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.event_speaker_deleted_successfully'),
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function orderItems(Request $request, $eventId)
    {
        $user = auth()->user();
        $event = Event::query()->where('id', $eventId)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($event)) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'items' => 'required',
            ]);

            if ($validator->fails()) {
                return response([
                    'code' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $itemIds = explode(',', $data['items']);

            if (!is_array($itemIds) and !empty($itemIds)) {
                $itemIds = [$itemIds];
            }

            if (!empty($itemIds) and is_array($itemIds) and count($itemIds)) {
                foreach ($itemIds as $order => $id) {
                    EventSpeaker::query()->where('id', $id)
                        ->where('event_id', $eventId)
                        ->update([
                            'order' => ($order + 1)
                        ]);
                }
            }

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.event_speaker_updated_successfully'),
            ]);
        }

        return response()->json([], 422);
    }

    private function makeStoreData($data, $event, $speaker = null): array
    {
        $order = !empty($speaker) ? $speaker->order : EventSpeaker::query()->where('event_id', $event->id)->count() + 1;

        return [
            'event_id' => $event->id,
            'link' => !empty($data['link']) ? $data['link'] : null,
            'order' => $order,
            'enable' => (!empty($data['enable']) and $data['enable'] == 'on'),
            'created_at' => !empty($speaker) ? $speaker->created_at : time(),
        ];
    }

    private function handleStoreExtraData(Request $request, $data, $speaker, $event, $requestKey = null)
    {
        $locale = $request->get('locale', getDefaultLocale());

        EventSpeakerTranslation::query()->updateOrCreate([
            'event_speaker_id' => $speaker->id,
            'locale' => mb_strtolower($locale),
        ], [
            'name' => $data['name'],
            'job' => $data['job'] ?? null,
            'description' => $data['description'] ?? null,
        ]);


        $imagePath = $speaker->image ?? null;
        $imageFileUpload = !empty($requestKey) ? $request->file("ajax.{$requestKey}.image") : $request->file('ajax.new.image');

        if (!empty($imageFileUpload)) {
            if (!empty($speaker->image)) {
                $this->removeFile($speaker->image);
            }

            $imagePath = $this->uploadFile($imageFileUpload, "events/{$event->id}/speakers/{$speaker->id}", 'image', $event->creator_id);
        }

        $speaker->update([
            'image' => $imagePath,
        ]);
    }
}
