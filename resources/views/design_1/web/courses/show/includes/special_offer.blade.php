<div class="course-special-offer-card ">
    <div class="position-relative w-100 h-100">
        <div class="course-special-offer-card__mask"></div>
        <div class="position-relative d-flex align-items-center justify-content-between bg-white p-16 pl-12 rounded-24 w-100 h-100 z-index-2">
            <div class="d-flex align-items-center">
                <x-iconsax-bul-receipt-disscount class="icons text-primary" width="32px" height="32px"/>
                <div class="ml-8">
                    <span class="d-block font-24 font-weight-bold text-dark">{{ $activeSpecialOffer->percent }}%</span>
                    <span class="d-block font-14 text-gray-500">{{ trans('panel.special_offer') }}</span>
                </div>
            </div>

            @php
                $remainingTimes = $activeSpecialOffer->getRemainingTimes()
            @endphp

            <div id="offerCountDown" class="time-counter-down d-flex flex-column justify-content-center p-12 rounded-8 border-gray-200"
                 data-day="{{ $remainingTimes['day'] }}"
                 data-hour="{{ $remainingTimes['hour'] }}"
                 data-minute="{{ $remainingTimes['minute'] }}"
                 data-second="{{ $remainingTimes['second'] }}">

                <div class="d-flex align-items-center font-14 font-weight-bold w-100">
                    <span class="days">0</span>
                    <span class="mx-4">:</span>
                    <span class="hours">0</span>
                    <span class="mx-4">:</span>
                    <span class="minutes">0</span>
                    <span class="mx-4">:</span>
                    <span class="seconds">0</span>
                </div>

                <div class="d-flex align-items-center font-8 mt-4 text-gray-500 w-100">
                    <span class="mr-8">{{ trans('public.day') }}</span>
                    <span class="pl-4 mr-8">{{ trans('update.hr') }}</span>
                    <span class="pl-4 mr-8">{{ trans('public.min') }}</span>
                    <span class="pl-4">{{ trans('public.sec') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
