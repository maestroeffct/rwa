<?php

namespace App\Http\Controllers\Panel\Traits;

use App\Models\EventTicketSold;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

trait EventTicketDetailsTrait
{

    public function handleShowTicketDetails(EventTicketSold $purchasedTicket)
    {
        $showQrCode = !empty(getEventsSettings("qr_status"));

        $data = [
            'pageTitle' => trans('update.view_ticket'),
            'purchasedTicket' => $purchasedTicket,
            'event' => $purchasedTicket->eventTicket->event,
            'showQrCode' => $showQrCode,
            'qrCode' => $showQrCode ? $this->makeQrCode($purchasedTicket->code) : null,
        ];

        return view('design_1.panel.events.view_ticket.index', $data);
    }

    private function makeQrCode($code)
    {
        $size = 64;

        $url = url("/events/validation?code={$code}");
        return QrCode::size($size)->generate($url)->toHtml();
    }

}
