<div class="d-grid grid-columns-3 grid-columns-lg-3 gap-24 mt-16">
    {{-- Event Type --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-ticket class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ trans("update.{$event->type}") }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.event_type') }}</span>
        </div>
    </div>
    {{-- Tickets --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-money-3 class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ $event->getSoldTicketsCount() }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.tickets') }}</span>
        </div>
    </div>

    @if($event->type == "in_person" and !empty($event->specificLocation))
        @php
            $specificLocationTitle = $event->specificLocation->getFullAddress(false, false, true, false, false)
        @endphp

        {{-- Location --}}
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-36 bg-white rounded-circle">
                <x-iconsax-lin-location class="icons text-gray-400" width="20px" height="20px"/>
            </div>
            <div class="ml-8 font-12">
                <span class="d-block font-weight-bold text-dark">{{ $specificLocationTitle }}</span>
                <span class="d-block mt-2 text-gray-500">{{ trans('update.location') }}</span>
            </div>
        </div>
    @else
        {{-- Speakers --}}
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-36 bg-white rounded-circle">
                <x-iconsax-lin-eye class="icons text-gray-400" width="20px" height="20px"/>
            </div>
            <div class="ml-8 font-12">
                <span class="d-block font-weight-bold text-dark">{{ $event->speakers_count }}</span>
                <span class="d-block mt-2 text-gray-500">{{ trans('update.speakers') }}</span>
            </div>
        </div>
    @endif

    {{-- Start Date --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-calendar-2 class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ !empty($event->start_date) ? dateTimeFormat($event->start_date, 'j M Y') : '-' }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('public.start_date') }}</span>
        </div>
    </div>

    {{-- End Date --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-calendar-remove class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ !empty($event->end_date) ? dateTimeFormat($event->end_date, 'j M Y') : '-' }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('webinars.end_date') }}</span>
        </div>
    </div>

    {{-- Duration --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-video-circle class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ ($event->duration > 0) ? (convertMinutesToHourAndMinute($event->duration) . ' ' .trans('home.hours')) : '-' }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('public.duration') }}</span>
        </div>
    </div>

</div>
