@if($user->hasMeeting())
    @push('styles_top')
        <link rel="stylesheet" href="/assets/default/vendors/persian-datepicker/persian-datepicker.min.css"/>

        <link rel="stylesheet" href="{{ getDesign1StylePath("profile_reserve_meeting") }}">
    @endpush

    <form action="{{ $user->getMeetingReservationUrl() }}/overview" method="get">

        <div class="mt-16 text-gray-500">{{ trans('update.please_pick_a_day_from_the_calendar_and_select_an_available_time_slot_you_will_be_redirected_to_the_meeting_booking_process') }}</div>

        @include('design_1.web.users.profile.tabs.reserveMeeting.top_stats')

        <div class="row mt-16">
            <div class="col-12 col-lg-5">
                @include('design_1.web.users.profile.tabs.reserveMeeting.calendar')
            </div>

            <div class="col-12 col-lg-7 mt-20 mt-lg-0">
                @include('design_1.web.users.profile.tabs.reserveMeeting.times', ['instructor' => $user])
            </div>
        </div>

    </form>

    @if(
           !empty($instructorDiscounts) and
           count($instructorDiscounts)
       )
        <div class="">
            @include('design_1.web.instructor_discounts.cards', ['allDiscounts' => $instructorDiscounts, 'discountCardClassName' => "user-profile-discount-card mt-16"])
        </div>
    @endif

    @if(!empty($userMeetingPackages) and $userMeetingPackages->isNotEmpty())
        <div class="mt-16 ">
            <h4 class="font-16">{{ trans('update.meeting_packages') }}</h4>
            <p class="font-12 text-gray-500 mt-4">{{ trans('update.book_multiple_sessions_in_one_package') }}</p>
        </div>

        <div class="row">
            @include('design_1.web.meeting_packages.components.cards.grids.index',['meetingPackages' => $userMeetingPackages, 'gridCardClassName' => "card-with-border col-12 col-md-6 col-lg-4 mt-12"])
        </div>
    @endif

    @push('scripts_bottom2')
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

@else
    @include('design_1.panel.includes.no-result',[
        'file_name' => 'profile_meeting.svg',
        'title' => trans('update.user_profile_not_have_meeting'),
        'hint' => trans('update.user_profile_not_have_meeting_hint'),
        'extraClass' => 'mt-0',
    ])
@endif

