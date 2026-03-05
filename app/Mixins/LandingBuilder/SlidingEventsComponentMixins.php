<?php

namespace App\Mixins\LandingBuilder;


use App\Models\Event;
use App\Models\EventTicketSold;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class SlidingEventsComponentMixins
{

    public function getEventsBySource($source = 'newest_events', $count = 8)
    {
        $query = Event::query()->select('events.*')
            ->where('events.status', 'publish');


        if ($source == 'top_selling_events') {
            $query->selectRaw('COUNT(event_tickets_sold.id) as total_sales')
                ->leftJoin('event_tickets', 'event_tickets.event_id', '=', 'events.id')
                ->join('event_tickets_sold', 'event_tickets_sold.event_ticket_id', '=', 'event_tickets.id')
                ->groupBy('events.id')
                ->orderBy('total_sales', 'desc');

        } elseif ($source == 'best_rated_events') {
            $query->join('webinar_reviews', function ($join) {
                $join->on("webinar_reviews.event_id", '=', "events.id");
                $join->where('webinar_reviews.status', 'active');
            })
                ->groupBy('events.id')
                ->select("events.*", DB::raw('avg(rates) as rates'))
                ->orderBy('rates', 'desc');

        } else {
            // newest_events
            $query->orderBy('created_at', 'desc');
        }


        return $query->limit($count)->get();
    }

}
