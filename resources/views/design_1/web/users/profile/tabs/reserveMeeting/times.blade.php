<div class="p-16 rounded-16 border-gray-200 w-100 h-100">

    <div class="js-before-select-day-card d-flex-center flex-column w-100 h-100 text-center">
        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
            <x-iconsax-bul-calendar-2 class="icons text-primary" width="32px" height="32px"/>
        </div>
        <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.select_a_date') }}</h3>
        <p class="mt-4 font-12 text-gray-500">{!! trans('update.select_a_date_from_the_calendar_to_book_a_meeting') !!}</p>
    </div>


    <div class="js-times-container d-none flex-column w-100 h-100" data-user="{{ $user->username }}">
        <input type="hidden" name="date" >

        <div class="d-flex align-items-center">
            <div class="d-flex-center size-32 rounded-8 bg-gray-100">
                <x-iconsax-lin-clock-1 class="icons text-gray-500" width="16px" height="16px"/>
            </div>
            <div class="ml-4 font-weight-bold">{{ trans('update.available_times_for') }} <span class="js-selected-date"></span></div>
        </div>

        <div class="js-loading-img d-none align-items-center justify-content-center h-75">
            <img src="/assets/default/img/loading.gif" width="80" height="80">
        </div>

        <div class="js-times-body d-flex flex-column flex-1">
            <div id="availableTimes" class="d-flex flex-wrap align-items-center gap-16 mt-24 mb-16">

            </div>

            <div class="js-time-description-card d-none mb-16 p-12 rounded-12 bg-gray-100 border-gray-200 font-12 text-gray-500">

            </div>

            <div class="d-none js-finalize-reserve mb-16">

            </div>

            <div class="js-reserve-btn d-none mt-auto">
                <button type="button" class="js-proceed-to-booking-btn btn btn-lg btn-primary btn-block">{{ trans('update.proceed_with_booking') }}</button>
            </div>

        </div>

    </div>
</div>
