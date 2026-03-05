<?php

namespace App\Http\Controllers\Panel;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Panel\Traits\EventTicketDetailsTrait;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventTicketSold;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MyPurchaseEventTicketsController extends Controller
{
    use EventTicketDetailsTrait;

    private function getMyPurchaseTicketIds($user)
    {
        return Sale::query()->where('buyer_id', $user->id)
            ->whereNotNull('event_ticket_id')
            ->whereNull('refund_at')
            ->where('access_to_purchased_item', true)
            ->pluck('event_ticket_id')
            ->toArray();
    }

    public function index(Request $request, $eventId)
    {
        $user = auth()->user();
        $purchasedTicketIds = $this->getMyPurchaseTicketIds($user);

        $event = Event::query()->where('id', $eventId)
            ->whereHas('tickets', function ($query) use ($purchasedTicketIds) {
                $query->whereIn('id', $purchasedTicketIds);
            })
            ->first();

        if (!empty($event)) {
            $query = EventTicketSold::query()->where('user_id', $user->id)
                ->whereHas('eventTicket', function ($query) use ($event) {
                    $query->where('event_id', $event->id);
                });

            $copyQuery = deepClone($query);
            $query = $this->handleFilters($request, $query);
            $getListData = $this->getListsData($request, $query, $event);

            if ($request->ajax()) {
                return $getListData;
            }

            $allTicketsIds = deepClone($copyQuery)->pluck('event_ticket_id')->toArray();
            $allTickets = EventTicket::query()->whereIn('id', $allTicketsIds)->get();


            $data = [
                'pageTitle' => trans('update.my_tickets'),
                'event' => $event,
                'allTickets' => $allTickets,
            ];
            $data = array_merge($data, $getListData);

            return view('design_1.panel.events.my_purchases.purchased_tickets.index', $data);
        }

        abort(404);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $search = $request->get('search');
        $ticket_id = $request->get('ticket_id');
        $sort = $request->get('sort');

        if (!empty($search)) {
            $query->where('code', 'like', '%' . $search . '%');
        }

        if (!empty($ticket_id)) {
            $query->where('event_ticket_id', $ticket_id);
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'amount_asc':
                    $query->orderBy('paid_amount', 'asc');
                    break;
                case 'amount_desc':
                    $query->orderBy('paid_amount', 'desc');
                    break;
                case 'purchase_date_asc':
                    $query->orderBy('paid_at', 'asc');
                    break;
                case 'purchase_date_desc':
                    $query->orderBy('paid_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('paid_at', 'desc');
        }

        return $query;
    }

    private function getListsData(Request $request, Builder $query, $event)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $purchasedTickets = $query
            ->with([
                'sale',
                'eventTicket',
            ])
            ->orderBy('paid_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $purchasedTickets, $total, $count, $event);
        }

        return [
            'purchasedTickets' => $purchasedTickets,
            'pagination' => $this->makePagination($request, $purchasedTickets, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $purchasedTickets, $total, $count, $event)
    {
        $html = "";

        foreach ($purchasedTickets as $ticketRow) {
            $html .= (string)view()->make("design_1.panel.events.my_purchases.purchased_tickets.table_items", [
                'purchasedTicket' => $ticketRow,
                'event' => $event,
            ]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $purchasedTickets, $total, $count, true)
        ]);
    }

    public function details($eventId, $eventTicketId)
    {
        $user = auth()->user();
        $purchasedTicket = EventTicketSold::query()->where('user_id', $user->id)
            ->where('event_ticket_id', $eventTicketId)
            ->whereHas('eventTicket', function ($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })
            ->whereHas('sale', function ($query) use ($user) {
                $query->where('buyer_id', $user->id);
                $query->whereNull('refund_at');
            })
            ->first();

        if (!empty($purchasedTicket) and !empty($purchasedTicket->eventTicket)) {
            return $this->handleShowTicketDetails($purchasedTicket);
        }

        abort(404);
    }


}
