@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/persian-datepicker/persian-datepicker.min.css"/>
@endpush


<div class="d-inline-flex-center px-8 py-4 bg-gray-100 rounded-32 text-gray-500">{{ trans('update.step') }} 1/2</div>

<div class="d-flex align-items-center mt-8 gap-4">
    <h3 class="font-24">{{ trans('update.meeting_time') }}</h3>
    <div class="size-24">
        <img src="/assets/design_1/img/meeting/alarm_clock.png" alt="alarm_clock" class="img-cover">
    </div>
</div>

<div class="mt-12 text-gray-500">{{ trans('update.pick_a_day_from_the_calendar_and_select_an_available_time_slot') }}</div>

{{-- Stats --}}
@include('design_1.web.users.meeting.includes.stats')

@if($instructor->hasMeeting())
    <form action="{{ $instructor->getMeetingReservationUrl() }}/overview" method="get">
        <div class="row mt-16">
            <div class="col-12 col-lg-5">
                @include('design_1.web.users.meeting.includes.calendar')
            </div>

            <div class="col-12 col-lg-7">
                @include('design_1.web.users.meeting.includes.times')
            </div>
        </div>

        <div class="js-reserve-btn mt-16 {{ (!empty($selectedDate) and !empty($selectedDateTimes) and count($selectedDateTimes)) ? '' : 'd-none' }}">
            <div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-proceed-to-booking-btn btn btn-lg btn-primary ">{{ trans('webinars.next') }}</button>
            </div>
        </div>
    </form>
@else
    <div class="d-flex-center flex-column text-center mt-16 py-128 px-32 rounded-16 border-gray-200">
        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
            <x-iconsax-bul-calendar-remove class="icons text-primary" width="32px" height="32px"/>
        </div>

        <h4 class="mt-12 font-16">{{ trans('update.user_profile_not_have_meeting') }}</h4>
        <p class="mt-4 text-gray-500">{{ trans('update.user_profile_not_have_meeting_hint') }}</p>
    </div>
@endif


@if(!empty($userMeetingPackages) and $userMeetingPackages->isNotEmpty())
    @push('styles_top')
        <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    @endpush

    @push('scripts_bottom')
        <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
        <script src="{{ getDesign1ScriptPath("swiper_slider") }}"></script>
    @endpush

    <div class="mt-16 ">
        <h4 class="font-16">{{ trans('update.meeting_packages') }} ({{ $userMeetingPackages->count() }})</h4>
        <p class="font-12 text-gray-500 mt-4">{{ trans('update.book_multiple_sessions_in_one_package') }}</p>
    </div>

    <div class="position-relative mt-12">
        <div class="swiper-container js-make-swiper meeting-packages-swiper pb-0"
             data-item="meeting-packages-swiper"
             data-autoplay="true"
             data-breakpoints="1440:2.6,769:1.8,320:1.1"
        >
            <div class="swiper-wrapper py-0">
                @include('design_1.web.meeting_packages.components.cards.grids.index',['meetingPackages' => $userMeetingPackages, 'gridCardClassName' => "card-with-border swiper-slide"])
            </div>
        </div>
    </div>
@endif

@push('scripts_bottom')
    <script src="/assets/default/vendors/persian-datepicker/persian-date.js"></script>
    <script src="/assets/default/vendors/persian-datepicker/persian-datepicker.js"></script>

    <script>
        var hasMonthTime = 'true';
        var hasMonthDay = 'true';
        var hasMonthHour = 'true';
        var nextBtnIcon = ``;
        var prevBtnIcon = ``;
        var availableDays = {{ json_encode($times) }};
    </script>

    <script src="{{ getDesign1ScriptPath("profile_reserve_meeting") }}"></script>
@endpush
