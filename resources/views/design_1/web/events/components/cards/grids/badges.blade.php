@php
    $maximumTicketsDiscount = $event->getMaximumTicketsDiscount();
    $currentTime = time();
@endphp

<div class="event-grid-card-1__badges d-flex flex-wrap gap-8">
    @if(!empty($maximumTicketsDiscount))
        <div class="d-flex-center badge bg-accent">
            <x-iconsax-bul-discount-shape class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.percent_off', ['percent' => $maximumTicketsDiscount]) }}</span>
        </div>
    @elseif(!empty($event->start_date) and $event->start_date > $currentTime)
        <div class="d-flex-center badge bg-info">
            <x-iconsax-bul-calendar-2 class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.scheduled') }}</span>
        </div>
    @elseif(!empty($event->end_date) and $event->end_date < $currentTime)
        <div class="d-flex-center badge bg-success">
            <x-iconsax-bul-tick-circle class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.completed') }}</span>
        </div>
    @elseif($event->checkIsSoldOutAllTickets())
        <div class="d-flex-center badge bg-secondary">
            <x-iconsax-bul-moneys class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.sold_out') }}</span>
        </div>
    @else
        <div class="d-flex-center badge bg-warning">
            <x-iconsax-bul-calendar-tick class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.ongoing') }}</span>
        </div>
    @endif
</div>
