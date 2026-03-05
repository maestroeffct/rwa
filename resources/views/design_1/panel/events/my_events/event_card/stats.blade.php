<div class="d-grid grid-columns-3 grid-columns-lg-3 gap-24 mt-16">
    {{-- Students --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-ticket class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ $event->getSoldTicketsCount() }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.sold_tickets') }}</span>
        </div>
    </div>
    {{-- Sales --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-money-3 class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ handlePrice($event->getAllSales()->sum('paid_amount')) }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('panel.sales') }}</span>
        </div>
    </div>
    {{-- Views --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-eye class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ shortNumbers($event->visits()->count()) }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.views') }}</span>
        </div>
    </div>
    {{-- Date --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-video-circle class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ !empty($event->start_date) ? dateTimeFormat($event->start_date, 'j M Y') : '-' }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.event_date') }}</span>
        </div>
    </div>

    {{-- Date --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-video-circle class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ ($event->duration > 0) ? (convertMinutesToHourAndMinute($event->duration) . ' ' .trans('home.hours')) : '-' }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('public.duration') }}</span>
        </div>
    </div>
    {{-- Ticket Types --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-trend-up class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ $event->tickets_count }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.ticket_types') }}</span>
        </div>
    </div>
</div>
