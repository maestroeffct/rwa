@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
@endpush

<div class="">

    <div class="d-inline-flex-center py-4 px-8 bg-gray-100 rounded-32 text-gray-500">{{ trans('update.step') }} 4/4</div>
    <h3 class="mt-8 font-24 font-weight-bold">{{ trans('update.meeting_time') }} ⏰</h3>

    <div class="d-flex align-items-center w-100 mt-4">
        <div class="instructor-finder-wizard__progress rounded-4 bg-gray-100 flex-1 mr-8">
            <div class="progressbar rounded-4 bg-success" style="width: 100%"></div>
        </div>

        <div class="d-flex-center size-32 bg-success rounded-circle">
            <x-iconsax-lin-teacher class="icons text-white" width="16px" height="16px"/>
        </div>
    </div>

    <div class="mt-32 text-gray-500">{{ trans('update.what_time_do_you_prefer_for_learning?') }}</div>

    <div class="form-group mt-20">
        <div class="d-flex align-items-center">
            <div class="custom-switch mr-8">
                <input id="flexibleDate" type="checkbox" name="flexible_date" class="custom-control-input" value="1">
                <label class="custom-control-label cursor-pointer" for="flexibleDate"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="flexibleDate">{{ trans('update.im_flexible') }}</label>
            </div>
        </div>
    </div>


    <div id="dateTimeCard" class="mt-20">
        <div class="font-12 font-weight-bold">{{ trans('update.week_days') }}</div>

        @php
            $days = ['saturday', 'sunday','monday','tuesday','wednesday','thursday','friday'];
        @endphp

        <div class="d-flex align-items-center flex-wrap gap-12 mt-16">
            @foreach($days as $day)
                <div class="instructor-finder-wizard__week-day custom-input-button custom-input-button-with-active-bg  position-relative">
                    <input type="radio" class="" name="day[]" id="day_{{ $day }}" value="{{ $day }}">
                    <label for="day_{{ $day }}" class="position-relative d-inline-flex-center py-12 px-16 rounded-8 text-center text-gray-500 ">
                        {{ trans('panel.'.$day) }}
                    </label>
                </div>
            @endforeach
        </div>


        <div class="font-12 font-weight-bold mt-20">{{ trans('update.meeting_timeframe') }}</div>

        <div
            class="range mb-20"
            id="timeRange"
            data-minLimit="0"
            data-maxLimit="23"
        >
            <input type="hidden" name="min_time" value="0">
            <input type="hidden" name="max_time" value="23">

        </div>


    </div>

</div>

@push('scripts_bottom')
    <script src="/assets/vendors/wrunner-html-range-slider-with-2-handles/js/wrunner-jquery.js"></script>
@endpush
