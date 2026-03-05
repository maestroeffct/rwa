<?php

namespace App\Mixins\Events;

use App\Models\EventTicket;
use App\Models\EventTicketSold;
use App\Models\OrderItem;
use App\Models\Sale;

class EventTicketSoldMixins
{

    public function makeTicket(OrderItem $orderItem, Sale $sale)
    {
        $eventTicket = $orderItem->eventTicket;
        $quantity = $orderItem->quantity ?? 1;
        $eventTicketPrice = $eventTicket->getPriceWithDiscount();

        for ($i = 0; $i < $quantity; $i++) {
            $code = $this->generateTicketCode();

            EventTicketSold::query()->create([
                'user_id' => $orderItem->user_id,
                'sale_id' => $sale->id,
                'event_ticket_id' => $eventTicket->id,
                'code' => $code,
                'paid_amount' => $eventTicketPrice,
                'paid_at' => $sale->created_at,
            ]);

            // Notification
            $notifyOptions = [
                '[event_title]' => $eventTicket->event->title,
                '[ticket_title]' => $eventTicket->title,
                '[ticket_code]' => $code,
                '[amount]' => handlePrice($eventTicketPrice),
                '[u.name]' => $sale->buyer->full_name,
            ];

            sendNotification('new_purchase_ticket_for_students', $notifyOptions, $orderItem->user_id);
            sendNotification('new_sale_ticket_for_provider', $notifyOptions, $sale->seller_id);
        }
    }

    public function generateTicketCode()
    {
        $settings = getEventsSettings();
        $format = $settings['ticket_code_format'] ?? 'both';
        $num = $settings['number_of_characters_ticket_code'] ?? 10;

        if ($format == 'numerical') {
            $code = $this->makeRandomString($num, true, false);
        } else if ($format == 'textual') {
            $code = $this->makeRandomString($num, false, true);
        } else {
            $code = $this->makeRandomString($num);
        }

        $check = EventTicketSold::query()->where('code', $code)->first();

        if (!empty($check)) {
            $code = $this->generateTicketCode();
        }

        return $code;
    }

    private function makeRandomString($length, $includeNumeric = true, $includeChar = true)
    {
        $keyspace = ($includeNumeric ? '0123456789' : '') . ($includeChar ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '');
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;

        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[rand(0, $max)];
        }

        return ($includeNumeric and !$includeChar) ? (int)$str : $str;
    }


}
