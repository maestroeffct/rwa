@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
@endpush


<div class="d-inline-flex-center px-8 py-4 bg-gray-100 rounded-32 text-gray-500">{{ trans('update.step') }} 2/2</div>

<div class="d-flex align-items-center mt-8 gap-4">
    <h3 class="font-24">{{ trans('update.meeting_details') }}</h3>
    <div class="size-24">
        <img src="/assets/design_1/img/meeting/meeting_details.png" alt="meeting_details" class="img-cover">
    </div>
</div>

<div class="mt-12 text-gray-500">{{ trans('update.pick_a_day_from_the_calendar_and_select_an_available_time_slot') }}</div>

{{-- Stats --}}
@include('design_1.web.users.meeting.includes.stats')

{{-- Selected Time --}}
<div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-dashed border-gray-300 mt-16">
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-48 bg-primary-20 rounded-12">
            <x-iconsax-bul-clock-1 class="icons text-primary" width="24px" height="24px"/>
        </div>
        <div class="ml-8">
            <h5 class="font-14">{{ trans('update.selected_time') }}</h5>
            <p class="mt-4 font-12 text-gray-500">{{ dateTimeFormat($selectedDate, 'l j M Y') }}</p>
        </div>
    </div>

    @php
        $selectedDateTimeClock = explode('-', $selectedTime->time);
    @endphp

    <div class="profile-available-times-item">
        <label class="d-flex align-items-center p-12 rounded-12 font-14 font-weight-bold cursor-default border-0">
            <span class="">{{ $selectedDateTimeClock[0] }}</span>
            <div class="time-center-divider position-relative d-flex-center size-16 rounded-circle mx-24">
                <x-iconsax-bul-teacher class="icons" width="12px" height="12px"/>
            </div>
            <span class="">{{ $selectedDateTimeClock[1] }}</span>
        </label>
    </div>
</div>

<form action="{{ (!$meeting->disabled) ? ($instructor->getMeetingReservationUrl() . '/reserve') : '' }}" class="js-meeting-book-form" method="post" data-amount-path="{{ $instructor->getMeetingReservationUrl() }}/get-amount">
    <input type="hidden" name="time" value="{{ $selectedTime->id }}">
    <input type="hidden" name="day" value="{{ dateTimeFormat($selectedDate, 'Y-m-d', false) }}">

    <div class="row">
        <div class="col-12 col-lg-6 mt-20">
            <div class="">
                <h5 class="font-12">{{ trans('update.meeting_type') }}</h5>
                <div class="d-flex align-items-center gap-4 p-4 border-gray-300 mt-8 rounded-12">
                    <div class="meeting-book__input-button flex-1">
                        <input type="radio" name="meeting_type" id="meetingTypeOnline" value="online" checked>
                        <label for="meetingTypeOnline" class="d-flex-center cursor-pointer">{{ trans('update.online') }}</label>
                    </div>

                    <div class="meeting-book__input-button flex-1">
                        <input type="radio" name="meeting_type" id="meetingTypeInPerson" value="in_person" {{ (!$meeting->in_person) ? 'disabled' : '' }}>
                        <label for="meetingTypeInPerson" class="d-flex-center gap-4 cursor-pointer">
                            {{ trans('update.in_person') }}

                            @if(!$meeting->in_person)
                                <span class="font-12">({{ trans('update.not_available') }})</span>
                            @endif
                        </label>
                    </div>
                </div>
            </div>

            @if($meeting->group_meeting)
                <div class="js-meeting-population-card mt-20">
                    <h5 class="font-12">{{ trans('update.meeting_population') }}</h5>
                    <div class="d-flex align-items-center gap-4 p-4 border-gray-300 mt-8 rounded-12">
                        <div class="meeting-book__input-button flex-1">
                            <input type="radio" name="meeting_population" id="meeting_population_individual" value="individual" checked>
                            <label for="meeting_population_individual" class="d-flex-center cursor-pointer">{{ trans('update.individual') }}</label>
                        </div>

                        <div class="meeting-book__input-button flex-1">
                            <input type="radio" name="meeting_population" id="meeting_population_group" value="online">
                            <label for="meeting_population_group" class="d-flex-center cursor-pointer">{{ trans('update.group') }}</label>
                        </div>
                    </div>
                </div>

                <div class="js-group-participants-card mt-20 d-none">
                    <h5 class="font-12">{{ trans('update.participants') }}</h5>

                    <div class="meeting-book__participants-range form-group mb-0 mt-8">
                        <input type="hidden" id="online_group_max_student" value="{{ $meeting->online_group_max_student }}">
                        <input type="hidden" id="in_person_group_max_student" value="{{ $meeting->in_person_group_max_student }}">

                        <div
                            class="range"
                            id="studentCountRange"
                            data-minLimit="1"
                        >
                            <input type="hidden" name="student_count" value="1">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>


                    <ul class="js-online-group-amount-hints list-style-disc d-none mt-16">
                        <li class="font-12 text-gray-500">{{ trans('update.online') }} {{ trans('update.group_meeting_hourly_rate_per_student',['amount' => handlePrice($meeting->online_group_amount, true, true, false, null, true)]) }}</li>
                        <li class="font-12 text-gray-500 mt-4">{{ trans('update.group_meeting_student_count_hint',['min' => $meeting->online_group_min_student, 'max' => $meeting->online_group_max_student]) }}</li>
                        <li class="font-12 text-gray-500 mt-4">{{ trans('update.group_meeting_max_student_count_hint',['max' => $meeting->online_group_max_student]) }}</li>
                    </ul>

                    @if($meeting->in_person)
                        <ul class="js-in-person-group-amount-hints list-style-disc d-none mt-16">
                            <li class="font-12 text-gray-500">{{ trans('update.in_person') }} {{ trans('update.group_meeting_hourly_rate_per_student',['amount' => handlePrice($meeting->in_person_group_amount, true, true, false, null, true)]) }}</li>
                            <li class="font-12 text-gray-500 mt-4">{{ trans('update.group_meeting_student_count_hint',['min' => $meeting->in_person_group_min_student, 'max' => $meeting->in_person_group_max_student]) }}</li>
                            <li class="font-12 text-gray-500 mt-4">{{ trans('update.group_meeting_max_student_count_hint',['max' => $meeting->in_person_group_max_student]) }}</li>
                        </ul>
                    @endif

                </div>
            @endif

        </div>

        <div class="col-12 col-lg-6 mt-20">
            <h5 class="font-12">{{ trans('update.message_to_mentor') }}</h5>

            <textarea name="description" class="form-control mt-8" rows="18" placeholder="{{ trans('update.reserve_time_description_placeholder') }}"></textarea>
        </div>
    </div>

    <div class="js-meeting-book-amount-and-checkout-card d-flex align-items-center justify-content-between mt-20">
        <div class="">
            <div class="js-meeting-amount font-24 font-weight-bold text-primary"></div>
            <p class="mt-2 text-gray-500">{{ trans('update.your_meeting_charge') }}</p>
        </div>

        <div class="d-flex align-items-center gap-24">
            <a href="{{ $instructor->getMeetingReservationUrl() }}?date={{ request()->get('date') }}&time={{ request()->get('time') }}" class="btn btn-transparent">{{ trans('update.back') }}</a>

            <button type="button" class="js-checkout-meeting btn btn-lg btn-primary">{{ trans('cart.checkout') }}</button>
        </div>
    </div>
</form>

@push('scripts_bottom')
    <script src="/assets/vendors/wrunner-html-range-slider-with-2-handles/js/wrunner-jquery.js"></script>
@endpush
