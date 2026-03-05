<div class="event-right-side-section position-relative mt-28">
    <div class="event-right-side-section__mask"></div>

    <div class="position-relative card-before-line bg-white rounded-24 p-16 z-index-2">
        <h4 class="font-14 font-weight-bold">{{ trans('update.event_specifications') }}</h4>

        @if(!empty($event->duration))
            <div class="d-flex align-items-center justify-content-between mt-16">
                <div class="d-flex align-items-center font-14 text-gray-500">
                    <x-iconsax-lin-clock-1 class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('public.duration') }}</span>
                </div>

                <span class="">{{ convertMinutesToHourAndMinute($event->duration) }} {{ trans('home.hours') }}</span>
            </div>
        @endif

        @if(!empty($event->start_date))
            <div class="d-flex align-items-center justify-content-between mt-16">
                <div class="d-flex align-items-center font-14 text-gray-500">
                    <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('public.start_date') }}</span>
                </div>

                <span class="">{{ dateTimeFormat($event->start_date, 'j M Y | H:i') }}</span>
            </div>
        @endif


        @if(!empty($event->end_date))
            <div class="d-flex align-items-center justify-content-between mt-16">
                <div class="d-flex align-items-center font-14 text-gray-500">
                    <x-iconsax-lin-calendar-remove class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('webinars.end_date') }}</span>
                </div>

                <span class="">{{ dateTimeFormat($event->end_date, 'j M Y | H:i') }}</span>
            </div>
        @endif

        @if(!empty($event->sales_end_date))
            <div class="d-flex align-items-center justify-content-between mt-16">
                <div class="d-flex align-items-center font-14 text-gray-500">
                    <x-iconsax-lin-calendar-remove class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.sales_end') }}</span>
                </div>

                <span class="">{{ dateTimeFormat($event->sales_end_date, 'j M Y | H:i') }}</span>
            </div>
        @endif


        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center font-14 text-gray-500">
                <x-iconsax-lin-ticket-2 class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4">{{ trans('update.sold_tickets') }}</span>
            </div>

            <span class="">{{ $event->getSoldTicketsCount() }}</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center font-14 text-gray-500">
                <x-iconsax-lin-people class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4">{{ trans('update.event_type') }}</span>
            </div>

            <span class="">{{ trans("update.{$event->type}") }}</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center font-14 text-gray-500">
                <x-iconsax-lin-ticket class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4">{{ trans('update.ticket_types') }}</span>
            </div>

            <span class="">{{ $event->tickets_count }}</span>
        </div>


    </div>
</div>
