<div class="bg-white p-16 rounded-24">
    <h4 class="font-14 text-dark">{{ trans('update.events_calendar') }}</h4>
    <p class="font-12 text-gray-500 mt-4">{{ trans('update.manage_your_activities_on_the_calendar') }}</p>

    <div class="dashboard-events-calendar mt-20">
        <input type="hidden" id="inlineEventsCalender" value="">
        <div id="dashboardEventsCalendar"></div>
    </div>

    {{-- If Have Upcoming Events --}}

    <div class="d-flex align-items-center justify-content-between mt-20">
        <div class="">
            <h4 class="font-14 text-dark">{{ trans('update.upcoming_events') }}</h4>
            <p class="font-12 text-gray-500 mt-4">{{ !empty($totalEvents) ? $totalEvents : 0 }} {{ trans('update.total_events') }}</p>
        </div>

        <a href="/panel/events-calender" target="_blank" class="d-flex-center size-40 rounded-circle border-gray-200 bg-hover-gray-100">
            <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
        </a>
    </div>

    @if(!empty($upcomingEvents) and count($upcomingEvents))
        @foreach($upcomingEvents as $upcomingEventName => $upcomingEvent)
            <a href="/panel/events-calender?date={{ $upcomingEvent['event_at'] }}" target="_blank" class="d-flex align-items-center mt-16 p-12 rounded-16 bg-gray-100 text-dark">
                <div class="dashboard-events-calendar__day-box d-flex-center flex-column text-center rounded-8 bg-gray-200">
                    <span class="font-weight-bold text-dark">{{ dateTimeFormat($upcomingEvent['event_at'], 'j') }}</span>
                    <span class="font-12 text-gray-400 mt-2">{{ dateTimeFormat($upcomingEvent['event_at'], 'D') }}</span>
                </div>
                <div class="ml-8">
                    <div class="">{{ trans("update.{$upcomingEvent['title']}") }}</div>
                    <p class="font-12 text-gray-500 mt-4">{{ $upcomingEvent['subtitle'] }}</p>
                </div>
            </a>
        @endforeach

    @else
        {{-- If Empty --}}
        <div class="d-flex-center flex-column text-center mt-20 border-dashed border-gray-200 bg-gray-100 p-32 rounded-16">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <x-iconsax-bul-calendar-2 class="icons text-primary" width="24px" height="24px"/>
            </div>
            <h5 class="font-14 text-dark mt-12">{{ trans('update.no_events!') }}</h5>
            <div class="mt-4 font-12 text-gray-500">{{ trans('update.there_are_no_events_on_your_website') }}</div>
        </div>
    @endif
</div>
