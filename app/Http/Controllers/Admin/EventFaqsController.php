<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Faq;
use App\Models\Translation\FaqTranslation;
use Illuminate\Http\Request;

class EventFaqsController extends Controller
{

    public function getForm(Request $request, $eventId)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->findOrFail($eventId);

        $locale = mb_strtolower($request->get('locale', getDefaultLocale()));

        $html = (string)view()->make('admin.events.create.contents.faqs.form', [
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
            'title' => 'required|max:255',
            'answer' => 'required',
        ]);

        $storeData = $this->makeStoreData($request, $event);
        $faq = Faq::query()->create($storeData);

        $this->handleStoreExtraData($request, $faq);

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans('update.faq_created_successfully'),
        ]);
    }

    public function edit(Request $request, $eventId, $id)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->findOrFail($eventId);
        $faq = Faq::query()->where('id', $id)
            ->where('event_id', $eventId)
            ->first();

        if (!empty($faq)) {
            $locale = mb_strtolower($request->get('locale', getDefaultLocale()));

            $html = (string)view()->make('admin.events.create.contents.faqs.form', [
                'event' => $event,
                'faq' => $faq,
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
        $faq = Faq::query()->where('id', $id)
            ->where('event_id', $eventId)
            ->first();

        if (!empty($faq)) {
            $this->validate($request, [
                'title' => 'required|max:255',
                'answer' => 'required',
            ]);

            $storeData = $this->makeStoreData($request, $event, $faq);
            $faq->update($storeData);

            $this->handleStoreExtraData($request, $faq);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.faq_updated_successfully'),
            ]);
        }

        return response()->json([], 422);
    }

    public function delete(Request $request, $eventId, $id)
    {
        $this->authorize("admin_events_create");
        $event = Event::query()->findOrFail($eventId);
        $faq = Faq::query()->where('id', $id)
            ->where('event_id', $event->id)
            ->first();

        if (!empty($faq)) {
            $faq->delete();

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.faq_deleted_successfully'),
                'status' => 'success'
            ];
            return redirect()->back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    private function makeStoreData(Request $request, $event, $faq = null): array
    {
        $order = Faq::query()->where('event_id', $event->id)
                ->count() + 1;

        return [
            'creator_id' => $event->creator_id,
            'event_id' => $event->id,
            'order' => $order,
            'created_at' => !empty($faq) ? $faq->created_at : time(),
        ];
    }

    private function handleStoreExtraData(Request $request, $faq)
    {
        $data = $request->all();

        FaqTranslation::query()->updateOrCreate([
            'faq_id' => $faq->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'answer' => $data['answer'],
        ]);


    }
}
