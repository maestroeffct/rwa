<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EventSoldTicketsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Panel\Traits\EventTicketDetailsTrait;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventTicketSold;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EventSoldTicketsController extends Controller
{
    use EventTicketDetailsTrait;

    public function index(Request $request, $eventId = null)
    {
        $this->authorize("admin_events_sold_tickets");

        $selectedEvent = Event::find($eventId);

        $query = EventTicketSold::query()
            ->whereHas('eventTicket', function ($query) use ($selectedEvent) {
                if (!empty($selectedEvent)) {
                    $query->where('event_id', $selectedEvent->id);
                }
            })
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            });

        $purchasedTickets = $this->handleFilters($request, $query)
            ->with([
                'sale',
                'eventTicket',
                'user' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'mobile', 'email');
                }
            ])
            ->paginate(10);

        $pageBaseUrl = "/events/sold-tickets";

        if (!empty($selectedEvent)) {
            $allTickets = $selectedEvent->tickets;

            $pageBaseUrl = "/events/{$selectedEvent->id}/sold-tickets";
        } else {
            $ticketIds = EventTicketSold::query()->pluck('event_ticket_id')->unique()->toArray();
            $allTickets = EventTicket::query()->whereIn('id', $ticketIds)->get();
        }


        $data = [
            'pageTitle' => !empty($selectedEvent) ? ("“{$selectedEvent->title}” " . trans('update.tickets')) : trans('update.sold_tickets'),
            'selectedEvent' => $selectedEvent,
            'purchasedTickets' => $purchasedTickets,
            'allTickets' => $allTickets,
            'pageBaseUrl' => getAdminPanelUrl($pageBaseUrl),
        ];

        return view('admin.events.sold_tickets.index', $data);
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

    public function details(Request $request, $eventTicketSoldId)
    {
        $this->authorize("admin_events_sold_tickets");

        $purchasedTicket = EventTicketSold::findOrFail($eventTicketSoldId);

        return $this->handleShowTicketDetails($purchasedTicket);
    }

    public function exportExcel(Request $request, $eventId = null)
    {
        $this->authorize("admin_events_lists_export_excel");

        $selectedEvent = Event::find($eventId);

        $query = EventTicketSold::query()
            ->whereHas('eventTicket', function ($query) use ($selectedEvent) {
                if (!empty($selectedEvent)) {
                    $query->where('event_id', $selectedEvent->id);
                }
            })
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            });

        $purchasedTickets = $this->handleFilters($request, $query)
            ->with([
                'sale',
                'eventTicket',
                'user' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'mobile', 'email');
                }
            ])
            ->get();

        $export = new EventSoldTicketsExport($purchasedTickets, $selectedEvent);
        return Excel::download($export, 'sold_tickets.xlsx');
    }
}
