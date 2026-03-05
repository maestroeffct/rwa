<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EventsListsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $events;

    public function __construct($events)
    {
        $this->events = $events;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->events;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('update.event'),
            trans('update.provider'),
            trans('update.event_type'),
            trans('admin/main.price'),
            trans('admin/main.capacity'),
            trans('update.sold_tickets'),
            trans('admin/main.start_date'),
            trans('admin/main.created_at'),
            trans('admin/main.status'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($event): array
    {
        $getMinAndMaxPrice = $event->getMinAndMaxPrice();
        $eventSoldTicketsCount = $event->getSoldTicketsCount();


        if ($getMinAndMaxPrice['min'] == $getMinAndMaxPrice['max']) {
            $priceText = ($getMinAndMaxPrice['min'] > 0) ? handlePrice($getMinAndMaxPrice['min'], true, true, false, null, true) : trans('update.free');
        } else {
            $priceText = ($getMinAndMaxPrice['min'] > 0) ? handlePrice($getMinAndMaxPrice['min'], true, true, false, null, true) : trans('update.free');
            $priceText .= " - ";
            $priceText .= ($getMinAndMaxPrice['max'] > 0) ? handlePrice($getMinAndMaxPrice['max'], true, true, false, null, true) : trans('update.free');
        }

        if (!is_null($event->capacity)) {
            $capacityText = $event->capacity;
            $capacityText .= " (" . trans('update.n_remaining', ['count' => $event->getAvailableCapacity()]) . ")";
        } else {
            $capacityText = trans('update.unlimited');
        }

        $soldText = $eventSoldTicketsCount;
        if($eventSoldTicketsCount > 0) {
            $soldText .= " (" . handlePrice($event->getAllSales()->sum('paid_amount')) . ")";
        }

        return [
            $event->title,
            $event->creator->full_name,
            trans("update.{$event->type}"),
            $priceText,
            $capacityText,
            $soldText,
            (!empty($event->start_date)) ? dateTimeFormat($event->start_date, 'j M Y H:i') : '-',
            dateTimeFormat($event->created_at, 'j M Y H:i'),
            trans("update.{$event->status}"),
        ];
    }
}
