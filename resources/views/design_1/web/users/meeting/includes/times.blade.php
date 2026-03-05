<div class="p-16 rounded-16 border-gray-200 w-100 h-100">


    <div class="js-before-select-day-card {{ (!empty($selectedDate) and !empty($selectedDateTimes) and count($selectedDateTimes)) ? 'd-none' : 'd-flex' }} align-items-center justify-content-center w-100 h-100">
        <div class="p-12 rounded-12 bg-warning-20 text-warning">{{ trans('update.to_book_a_meeting_please_select_a_day_from_the_calendar') }}</div>
    </div>

    <div class="js-times-container flex-column w-100 h-100 {{ (!empty($selectedDate) and !empty($selectedDateTimes) and count($selectedDateTimes)) ? 'd-flex' : 'd-none' }}" data-user="{{ $instructor->username }}">
        <input type="hidden" name="date" value="{{ !empty($selectedDate) ? $selectedDate : '' }}">

        <div class="d-flex align-items-center">
            <div class="d-flex-center size-32 rounded-8 bg-gray-100">
                <x-iconsax-lin-clock-1 class="icons text-gray-500" width="16px" height="16px"/>
            </div>
            <div class="ml-4 font-weight-bold">{{ trans('update.available_times_for') }} <span class="js-selected-date">{{ dateTimeFormat($selectedDate, 'j M Y') }}</span></div>
        </div>

        <div class="js-loading-img d-none align-items-center justify-content-center h-75">
            <img src="/assets/default/img/loading.gif" width="80" height="80">
        </div>

        <div class="js-times-body d-flex flex-column flex-1">
            <div id="availableTimes" class="d-flex flex-wrap align-items-center gap-16 mt-24 mb-16">
                @if(!empty($selectedDateTimes) and count($selectedDateTimes))
                    @foreach($selectedDateTimes as $selectedDateTime)
                        @php
                            $selectedDateTimeClock = explode('-', $selectedDateTime->time);
                        @endphp
                        <div class="profile-available-times-item">
                            <input type="radio" name="time" id="availableTime_{{ $selectedDateTime->id }}" value="{{ $selectedDateTime->id }}" data-type="{{ $selectedDateTime->meeting_type }}" {{ ($selectedDateTime->can_reserve ? '' : 'disabled') }} {{ (!empty($selectedTime) and $selectedDateTime->id == $selectedTime->id) ? 'checked' : '' }}>
                            <label for="availableTime_{{ $selectedDateTime->id }}" class="d-flex align-items-center p-12 rounded-12 font-14 font-weight-bold {{ ($selectedDateTime->can_reserve ? '' : 'unavailable') }}">
                                <span class="">{{ $selectedDateTimeClock[0] }}</span>
                                <div class="time-center-divider position-relative d-flex-center size-16 rounded-circle mx-24">
                                    <x-iconsax-bul-teacher class="icons" width="12px" height="12px"/>
                                </div>
                                <span class="">{{ $selectedDateTimeClock[1] }}</span>
                            </label>

                            <input type="hidden" class="js-time-description" value="{{ $selectedDateTime->description }}"/>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="js-time-description-card {{ (!empty($selectedTime) and !empty($selectedTime->description)) ? '' : 'd-none' }} mb-16 p-12 rounded-12 bg-gray-100 border-gray-200 font-12 text-gray-500">
                {!! !empty($selectedTime) ? $selectedTime->description : '' !!}
            </div>

            <div class="d-none js-finalize-reserve mb-16">

            </div>

        </div>

    </div>
</div>
