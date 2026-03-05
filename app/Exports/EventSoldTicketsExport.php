<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EventSoldTicketsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $purchasedTickets;
    protected $selectedEvent;

    public function __construct($purchasedTickets, $selectedEvent = null)
    {
        $this->purchasedTickets = $purchasedTickets;
        $this->selectedEvent = $selectedEvent;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->purchasedTickets;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('update.event'),
            trans('update.participant'),
            trans('update.ticket_type'),
            trans('public.paid_amount'),
            trans('update.ticket_code'),
            trans('update.purchase_date'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($purchasedTicket): array
    {

        return [
            $purchasedTicket->eventTicket->event->title,
            $purchasedTicket->user->full_name,
            $purchasedTicket->eventTicket->title,
            ($purchasedTicket->paid_amount > 0) ? handlePrice($purchasedTicket->paid_amount) : trans('update.free'),
            $purchasedTicket->code,
            dateTimeFormat($purchasedTicket->paid_at, 'j M Y H:i'),
        ];
    }
}
