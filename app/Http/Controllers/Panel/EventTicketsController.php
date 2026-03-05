<?php

namespace App\Http\Controllers\Panel;


use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Translation\EventTicketTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventTicketsController extends Controller
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
                'title' => 'required|max:255',
                'description' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response([
                    'code' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $storeData = $this->makeStoreData($data, $event);
            $ticket = EventTicket::query()->create($storeData);

            $this->handleStoreExtraData($request, $data, $ticket);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.event_ticket_created_successfully'),
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
            $ticket = EventTicket::query()->where('id', $id)
                ->where('event_id', $eventId)
                ->first();

            if (!empty($ticket)) {
                $data = $request->get('ajax')[$id];

                $validator = Validator::make($data, [
                    'title' => 'required|max:255',
                    'description' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return response([
                        'code' => 422,
                        'errors' => $validator->errors(),
                    ], 422);
                }

                $storeData = $this->makeStoreData($data, $event, $ticket);
                $ticket->update($storeData);

                $this->handleStoreExtraData($request, $data, $ticket);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.event_ticket_updated_successfully'),
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
            $ticket = EventTicket::query()->where('id', $id)
                ->where('event_id', $eventId)
                ->first();

            if (!empty($ticket)) {
                $ticket->delete();

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.event_ticket_deleted_successfully'),
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
                    EventTicket::query()->where('id', $id)
                        ->where('event_id', $eventId)
                        ->update([
                            'order' => ($order + 1)
                        ]);
                }
            }

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.event_ticket_updated_successfully'),
            ]);
        }

        return response()->json([], 422);
    }

    private function makeStoreData($data, $event, $ticket = null): array
    {
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

    private function handleStoreExtraData(Request $request, $data, $ticket)
    {
        $locale = $request->get('locale', getDefaultLocale());

        EventTicketTranslation::query()->updateOrCreate([
            'event_ticket_id' => $ticket->id,
            'locale' => mb_strtolower($locale),
        ], [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'options' => !empty($data['options']) ? $data['options'] : null,
        ]);

    }
}
