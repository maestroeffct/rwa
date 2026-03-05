<?php

namespace App\Http\Controllers\Panel;

use App\Enums\MorphTypesEnum;
use App\Enums\UploadSource;
use App\Http\Controllers\Admin\traits\SpecificLocationsTrait;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventFilterOption;
use App\Models\EventTicketSold;
use App\Models\Sale;
use App\Models\Tag;
use App\Models\Translation\EventTranslation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MyPurchaseEventsController extends Controller
{

    private function getMyPurchaseTicketIds($user)
    {
        return Sale::query()->where('buyer_id', $user->id)
            ->whereNotNull('event_ticket_id')
            ->whereNull('refund_at')
            ->where('access_to_purchased_item', true)
            ->pluck('event_ticket_id')
            ->toArray();
    }

    public function index(Request $request)
    {
        $this->authorize("panel_events_my_purchases");

        $user = auth()->user();
        $purchasedTicketIds = $this->getMyPurchaseTicketIds($user);

        $query = Event::query()
            ->whereHas('tickets', function ($query) use ($purchasedTicketIds) {
                $query->whereIn('id', $purchasedTicketIds);
            })
            ->where('status', 'publish');

        $copyQuery = deepClone($query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $topStats = $this->handleTopStats($copyQuery);


        $data = [
            'pageTitle' => trans('update.my_events'),
        ];
        $data = array_merge($data, $topStats);
        $data = array_merge($data, $getListData);

        return view('design_1.panel.events.my_purchases.events.index', $data);
    }

    private function handleTopStats(Builder $query): array
    {
        $time = time();

        $totalEventsCount = deepClone($query)->count();

        $totalEndedEvents = deepClone($query)
            ->where(function (Builder $query) use ($time) {
                $query->whereNotNull("end_date");
                $query->where('end_date', "<", $time);
            })
            ->count();

        $totalOpenEvents = deepClone($query)
            ->where(function (Builder $query) use ($time) {
                $query->whereNull("end_date");
                $query->orWhere('end_date', ">", $time);
            })
            ->count();

        $totalDurationEvents = deepClone($query)->sum('duration');

        return [
            'totalEventsCount' => $totalEventsCount,
            'totalEndedEvents' => $totalEndedEvents,
            'totalOpenEvents' => $totalOpenEvents,
            'totalDurationEvents' => $totalDurationEvents,
        ];
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $events = $query
            ->with([
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                },
                'specificLocation',
            ])
            ->withCount([
                'speakers' => function ($query) {
                    $query->where('enable', true);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();


        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $events, $total, $count);
        }

        return [
            'events' => $events,
            'pagination' => $this->makePagination($request, $events, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $events, $total, $count)
    {
        $html = "";

        foreach ($events as $eventRow) {
            $html .= (string)view()->make("design_1.panel.events.my_purchases.events.event_card.index", ['event' => $eventRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $events, $total, $count, true)
        ]);
    }


    public function joinToSessionModal($eventId)
    {
        $user = auth()->user();

        $event = Event::query()
            ->where('id', $eventId)
            ->where('status', 'publish')
            ->first();

        if (!empty($event) and $event->checkUserHasBought($user) and $event->type == "online" and !empty($event->session)) {
            $html = (string)view()->make("design_1.panel.events.modals.join_modal", [
                'event' => $event,
            ]);

            return response()->json([
                'code' => 200,
                'html' => $html,
                'password' => $event->session->api_secret ?? '-',
                'url' => "/panel/events/my-purchases/{$event->id}/join-to-session"
            ]);
        }

        return response()->json([], 422);
    }

    public function joinToSession($eventId)
    {
        $user = auth()->user();

        $event = Event::query()
            ->where('id', $eventId)
            ->where('status', 'publish')
            ->first();

        if (!empty($event) and $event->checkUserHasBought($user) and $event->type == "online" and !empty($event->session)) {
            return redirect($event->session->getJoinLink());
        }

        abort(403);
    }

    public function invoice(Request $request, $eventId)
    {
        $this->authorize("panel_events_my_purchases");

        $user = auth()->user();

        $event = Event::query()
            ->where('id', $eventId)
            ->where('status', 'publish')
            ->first();

        if (!empty($event) and $event->checkUserHasBought($user)) {
            $purchasedTickets = EventTicketSold::query()->select('*', DB::raw("count(event_ticket_id) as quantity"))
                ->whereHas('eventTicket', function ($query) use ($event) {
                    $query->where('event_id', $event->id);
                })
                ->whereHas('sale', function ($query) {
                    $query->whereNull('refund_at');
                })
                ->with([
                    'sale',
                    'eventTicket',
                    'user' => function ($query) {
                        $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'mobile', 'email');
                    }
                ])
                ->groupBy('event_ticket_id')
                ->orderBy('paid_at', 'desc')
                ->get();

            if ($purchasedTickets->isNotEmpty()) {

                $saleSubtotal = 0;
                $saleTaxAmount = 0;
                $saleDiscountAmount = 0;
                $saleTotalAmount = 0;

                foreach ($purchasedTickets as $purchasedTicket) {
                    $saleSubtotal += $purchasedTicket->sale->amount;
                    $saleTaxAmount += $purchasedTicket->sale->tax;
                    $saleDiscountAmount += $purchasedTicket->sale->discount;
                    $saleTotalAmount += $purchasedTicket->sale->total_amount;
                }

                $data = [
                    'pageTitle' => trans('update.my_event_invoice'),
                    'event' => $event,
                    'seller' => $event->creator,
                    'buyer' => $user,
                    'purchasedTickets' => $purchasedTickets,
                    'firstPurchasedTicket' => $purchasedTickets->first(),
                    'salePrices' => [
                        'saleSubtotal' => $saleSubtotal,
                        'saleTaxAmount' => $saleTaxAmount,
                        'saleDiscountAmount' => $saleDiscountAmount,
                        'saleTotalAmount' => $saleTotalAmount,
                    ]
                ];

                return view('design_1.panel.events.invoice.index', $data);
            }
        }

        abort(404);
    }

}
