@if(!empty($advertisingModalSettings))
    <div class="advertising-modal bg-white rounded-32">
        @if(!empty($advertisingModalSettings['image']))
            <div class="advertising-modal__image">
                <img src="{{ $advertisingModalSettings['image'] }}" alt="{{ $advertisingModalSettings['title'] ?? '' }}" class="img-cover">
            </div>
        @endif

        <div class="position-relative pb-76">

            {{-- Progress --}}
            @if(!empty($advertisingModalSettings['autoclose']))
                <div class="advertising-modal__autoclose d-flex">
                    <div class="js-advertising-modal-autoclose-progress autoclose-progress-bar" data-seconds="{{ $advertisingModalSettings['autoclose'] }}"></div>
                </div>
            @endif

            {{-- Icon --}}
            @if(!empty($advertisingModalSettings['icon']))
                <div class="advertising-modal__icon d-flex-center bg-gray-100 rounded-circle">
                    <div class="d-flex-center size-64 rounded-circle">
                        <img src="{{ $advertisingModalSettings['icon'] }}" alt="icon" class="img-cover">
                    </div>
                </div>
            @endif

            <div class="d-flex-center flex-column text-center pt-52 px-48">
                {{-- Title --}}
                @if(!empty($advertisingModalSettings['title']))
                    <h3 class="font-24">{{ $advertisingModalSettings['title'] }}</h3>
                @endif

                {{-- Description --}}
                @if(!empty($advertisingModalSettings['description']))
                    <div class="font-14 mt-16 text-gray-500">{!! $advertisingModalSettings['description'] ?? '' !!}</div>
                @endif
            </div>

            {{-- Countdown --}}
            @if(!empty($advertisingModalSettings['countdown']))
                @php
                    $advertisingModalRemainingTimes = time2string($advertisingModalSettings['countdown'] - time());
                @endphp

                <div class="advertising-modal__countdown position-relative d-flex-center py-32">
                    <div class="advertising-modal__countdown-card position-relative">
                        <div class="mask"></div>
                        <div id="advertisingModalCountdown" class="position-relative d-flex-center flex-column p-20 bg-white rounded-16 border-gray-200 z-index-2 w-100 h-100"
                             data-day="{{ $advertisingModalRemainingTimes['day'] }}"
                             data-hour="{{ $advertisingModalRemainingTimes['hour'] }}"
                             data-minute="{{ $advertisingModalRemainingTimes['minute'] }}"
                             data-second="{{ $advertisingModalRemainingTimes['second'] }}"
                        >
                            <div class="d-flex-center font-24 font-weight-bold w-100">
                                <span class="days">0</span>
                                <span class="mx-4">:</span>
                                <span class="hours">0</span>
                                <span class="mx-4">:</span>
                                <span class="minutes">0</span>
                                <span class="mx-4">:</span>
                                <span class="seconds">0</span>
                            </div>

                            <div class="d-flex-center font-12 mt-8 text-gray-500 w-100">
                                <span class="mr-8">{{ trans('public.day') }}</span>
                                <span class="pl-8 mr-8">{{ trans('update.hr') }}</span>
                                <span class="pl-8 mr-8">{{ trans('public.min') }}</span>
                                <span class="pl-8">{{ trans('public.sec') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(!empty($advertisingModalSettings['button1']) and !empty($advertisingModalSettings['button1']['title']))
                <div class="advertising-modal__button px-2 mb-20">
                    <a href="{{ !empty($advertisingModalSettings['button1']['link']) ? $advertisingModalSettings['button1']['link'] : '#!' }}"
                       class="d-flex align-items-center justify-content-between p-24 bg-primary rounded-32 text-white"
                    >
                        <div class="d-flex flex-column">
                            <span class="font-16 font-weight-bold text-white">{{ $advertisingModalSettings['button1']['title'] }}</span>

                            @if(!empty($advertisingModalSettings['button1']['subtitle']))
                                <span class="font-12 text-white mt-8">{{ $advertisingModalSettings['button1']['subtitle'] }}</span>
                            @endif
                        </div>

                        <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                            <x-iconsax-lin-arrow-right class="icons text-white" width="24px" height="24px"/>
                        </div>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endif
