@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/persian-datepicker/persian-datepicker.min.css"/>
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-3">
            <div class="bg-white p-16 rounded-24">
                <div class="pb-6 border-bottom-gray-100">
                    <h3 class="font-14 font-weight-bold text-dark">{{ trans('update.select_a_date') }}</h3>
                    <p class="font-12 text-gray-500 mt-4">{{ trans('update.select_a_date_from_the_calendar_and_check_events') }}</p>
                </div>

                <div class="dashboard-events-calendar mt-20">
                    <input type="hidden" id="inlineEventsCalender" value="">
                    <div id="dashboardEventsCalendar"></div>
                </div>

            </div>
        </div>

        <div class="js-day-events-card col-12 col-lg-6 mt-20 mt-lg-0">
            @include('design_1.panel.events.day_events')
        </div>

        <div class="col-12 col-lg-3 mt-20 mt-lg-0">
            {{-- Upcoming Events --}}
            <div class="bg-white p-16 rounded-24">
                <div class="pb-6 border-bottom-gray-100">
                    <h3 class="d-flex align-items-center font-14 font-weight-bold text-dark">{{ trans('update.upcoming_events') }}</h3>
                    <p class="font-12 text-gray-500 mt-4">{{ trans('update.check_upcoming_events_and_add_them_to_reminder') }}</p>
                </div>

                {{-- Card --}}
                @if(!empty($upcomingEvents) and count($upcomingEvents))
                    @foreach($upcomingEvents as $upcomingEvent)
                        <div class="js-upcoming-event-card d-flex align-items-center p-12 rounded-16 mt-16 bg-gray-100 cursor-pointer" data-day="{{ $upcomingEvent['event_at'] }}">
                            <div class="events-calendar__upcoming-event-date-box d-flex-center flex-column text-center rounded-8 bg-gray-200">
                                <span class="font-weight-bold text-dark">{{ dateTimeFormat($upcomingEvent['event_at'], 'j') }}</span>
                                <span class="font-12 text-gray-400 mt-2">{{ dateTimeFormat($upcomingEvent['event_at'], 'M') }}</span>
                            </div>
                            <div class="ml-8">
                                <div class="">{{ trans("update.{$upcomingEvent['title']}") }}</div>
                                <p class="font-12 text-gray-500 mt-8">{{ $upcomingEvent['subtitle'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>
@endsection

@push("scripts_bottom")
    <script>
        var $eventsWithTimestamp = @json((!empty($eventsWithTimestamp) and count($eventsWithTimestamp)) ? $eventsWithTimestamp : []);
    </script>

    <script src="/assets/default/vendors/persian-datepicker/persian-date.js"></script>
    <script src="/assets/default/vendors/persian-datepicker/persian-datepicker.js"></script>


    <script src="/assets/design_1/js/panel/events_calendar.min.js"></script>
@endpush
