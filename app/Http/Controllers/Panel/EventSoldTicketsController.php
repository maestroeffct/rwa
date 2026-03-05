<?php

namespace App\Http\Controllers\Panel;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Panel\Traits\EventTicketDetailsTrait;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventTicketSold;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EventSoldTicketsController extends Controller
{
    use EventTicketDetailsTrait;

    public function index(Request $request, $eventId)
    {
        $this->authorize("panel_events_sold_tickets_lists");

        $user = auth()->user();

        $event = Event::query()->where('id', $eventId)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($event)) {
            $ticketIds = $event->tickets->pluck('id')->toArray();

            $query = EventTicketSold::query()->whereIn('event_ticket_id', $ticketIds);

            $copyQuery = deepClone($query);
            $query = $this->handleFilters($request, $query);
            $getListData = $this->getListsData($request, $query, $event);

            if ($request->ajax()) {
                return $getListData;
            }

            $allTickets = EventTicket::query()->whereIn('id', $ticketIds)->get();
            $topStats = $this->handleTopStats($copyQuery);


            $data = [
                'pageTitle' => trans('update.sold_tickets'),
                'event' => $event,
                'allTickets' => $allTickets,
            ];
            $data = array_merge($data, $topStats);
            $data = array_merge($data, $getListData);

            return view('design_1.panel.events.sold_tickets.index', $data);
        }

        abort(404);
    }

    private function handleTopStats(Builder $query): array
    {

        $totalParticipantsCount = deepClone($query)->groupBy('user_id')->get()->count();
        $soldTicketsCount = deepClone($query)->count();
        $totalSalesAmount = deepClone($query)->sum('paid_amount');
        $totalTicketTypes = deepClone($query)->groupBy('event_ticket_id')->get()->count();

        return [
            'totalParticipantsCount' => $totalParticipantsCount,
            'soldTicketsCount' => $soldTicketsCount,
            'totalSalesAmount' => $totalSalesAmount,
            'totalTicketTypes' => $totalTicketTypes,
        ];
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
                case 'paid_amount_asc':
                    $query->orderBy('paid_amount', 'asc');
                    break;
                case 'paid_amount_desc':
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
                'user' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'mobile', 'email');
                }
            ])
            ->get();

        /*foreach ($purchasedTickets as $event) {

        }*/

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
            $html .= (string)view()->make("design_1.panel.events.sold_tickets.table_items", [
                'purchasedTicket' => $ticketRow,
                'event' => $event,
            ]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $purchasedTickets, $total, $count, true)
        ]);
    }


    public function details($eventId, $eventTicketSoldId)
    {
        $user = auth()->user();

        $event = Event::query()->where('id', $eventId)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($event)) {
            $purchasedTicket = EventTicketSold::query()->where('id', $eventTicketSoldId)
                ->whereHas('eventTicket', function ($query) use ($event) {
                    $query->where('event_id', $event->id);
                })
                ->whereHas('sale', function ($query) {
                    $query->whereNull('refund_at');
                })
                ->first();

            if (!empty($purchasedTicket)) {
                return $this->handleShowTicketDetails($purchasedTicket);
            }
        }

        abort(404);
    }


}
