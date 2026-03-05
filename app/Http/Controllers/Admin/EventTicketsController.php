<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Translation\EventTicketTranslation;
use Illuminate\Http\Request;

class EventTicketsController extends Controller
{

    public function getForm(Request $request, $eventId)
    {
        $this->authorize("admin_events_tickets");
        $event = Event::query()->findOrFail($eventId);

        $locale = mb_strtolower($request->get('locale', getDefaultLocale()));

        $html = (string)view()->make('admin.events.create.contents.tickets.form', [
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
        $this->authorize("admin_events_tickets");

        $event = Event::query()->findOrFail($eventId);

        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required|string',
        ]);

        $storeData = $this->makeStoreData($request, $event);
        $ticket = EventTicket::query()->create($storeData);

        $this->handleStoreExtraData($request, $ticket);

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans('update.event_ticket_created_successfully'),
        ]);
    }

    public function edit(Request $request, $eventId, $id)
    {
        $this->authorize("admin_events_tickets");
        $event = Event::query()->findOrFail($eventId);
        $ticket = EventTicket::query()->where('id', $id)
            ->where('event_id', $eventId)
            ->first();

        if (!empty($ticket)) {
            $locale = mb_strtolower($request->get('locale', getDefaultLocale()));

            $html = (string)view()->make('admin.events.create.contents.tickets.form', [
                'event' => $event,
                'ticket' => $ticket,
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
        $this->authorize("admin_events_tickets");
        $event = Event::query()->findOrFail($eventId);
        $ticket = EventTicket::query()->where('id', $id)
            ->where('event_id', $eventId)
            ->first();

        if (!empty($ticket)) {
            $this->validate($request, [
                'title' => 'required|max:255',
                'description' => 'required|string',
            ]);

            $storeData = $this->makeStoreData($request, $event, $ticket);
            $ticket->update($storeData);

            $this->handleStoreExtraData($request, $ticket);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.event_ticket_updated_successfully'),
            ]);
        }

        return response()->json([], 422);
    }

    public function delete(Request $request, $eventId, $id)
    {
        $this->authorize("admin_events_tickets");
        $event = Event::query()->findOrFail($eventId);
        $ticket = EventTicket::query()->where('id', $id)
            ->where('event_id', $event->id)
            ->first();

        if (!empty($ticket)) {
            $ticket->delete();

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.event_ticket_deleted_successfully'),
                'status' => 'success'
            ];
            return redirect()->back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    private function makeStoreData(Request $request, $event, $ticket = null): array
    {
        $data = $request->all();
        $discountDateRange = !empty($data['discount_date_range']) ? explode('-', $data['discount_date_range']) : null;

        $fromDate = (!empty($discountDateRange)) ? convertTimeToUTCzone($discountDateRange[0])->getTimestamp() : null;
        $toDate = (!empty($discountDateRange)) ? convertTimeToUTCzone($discountDateRange[1])->getTimestamp() : null;

        $order = !empty($ticket) ? $ticket->order : EventTicket::query()->where('event_id', $event->id)->count() + 1;


        return [
            'event_id' => $event->id,
            'price' => !empty($data['price']) ? convertPriceToDefaultCurrency($data['price']) : null,
            'icon' => !empty($data['icon']) ? $data['icon'] : null,
            'capacity' => !empty($data['capacity']) ? $data['capacity'] : null,
            'point' => !empty($data['point']) ? $data['point'] : null,
            'discount' => !empty($data['discount']) ? $data['discount'] : null,
            'discount_start_at' => $fromDate,
            'discount_end_at' => $toDate,
            'order' => $order,
            'enable' => (!empty($data['enable']) and $data['enable'] == 'on'),
            'created_at' => !empty($ticket) ? $ticket->created_at : time(),
        ];
    }

    private function handleStoreExtraData(Request $request, $ticket)
    {
        $data = $request->all();

        EventTicketTranslation::query()->updateOrCreate([
            'event_ticket_id' => $ticket->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'options' => !empty($data['options']) ? $data['options'] : null,
        ]);


    }
}
