<div class="event-right-side-section position-relative">
    <div class="event-right-side-section__mask"></div>

    <div class="position-relative bg-white rounded-24 pb-24 z-index-2">

        {{-- Thumbnail --}}
        <div class="event-right-side__thumbnail position-relative bg-gray-200">
            <img src="{{ $event->thumbnail }}" class="img-cover" alt="{{ $event->title }}">

            @if($event->video_demo)
                <div id="eventDemoVideoBtn" class="has-video-icon d-flex-center size-64 rounded-circle cursor-pointer"
                     data-video-path="{{ $event->video_demo_source == 'upload' ?  url($event->video_demo) : $event->video_demo }}"
                     data-video-source="{{ $event->video_demo_source }}"
                     data-thumbnail="{{ $event->thumbnail }}"
                >
                    <x-iconsax-bol-play class="icons text-white" width="24px" height="24px"/>
                </div>
            @endif
        </div>

        {{-- Price --}}
        <div class="event-right-side__price-card d-flex-center gap-4 font-24 font-weight-bold text-dark mt-20">
            <span class="circle-1"></span>
            <span class="circle-2"></span>

            @php
                $getMinAndMaxPrice = $event->getMinAndMaxPrice();
            @endphp

            @if($getMinAndMaxPrice['min'] == $getMinAndMaxPrice['max'])
                <span class="">{{ handlePrice($getMinAndMaxPrice['min'], true, true, false, null, true) }}</span>
            @else
                <span class="">{{ ($getMinAndMaxPrice['min'] > 0) ? handlePrice($getMinAndMaxPrice['min'], true, true, false, null, true) : trans('update.free') }}</span>
                -
                <span class="">{{ ($getMinAndMaxPrice['max'] > 0) ? handlePrice($getMinAndMaxPrice['max'], true, true, false, null, true) : trans('update.free') }}</span>
            @endif
        </div>

        <div class="px-16">
            {{-- Actions --}}
            <button type="button" class="js-scroll-to-event-tickets-btn btn btn-primary btn-block btn-xlg mt-20">{{ trans('update.attend_event') }}</button>

            {{-- Countdown --}}
            @if($event->enable_countdown)
                @php
                    $eventCountdownTimes = $event->getCountdownTimes();
                @endphp

                @if(!empty($eventCountdownTimes))
                    @php
                        $eventCountdownTimesString = time2string($eventCountdownTimes);
                    @endphp

                    <div class="d-flex align-items-center gap-4 p-12 rounded-12 mt-12 border-warning bg-warning-10 text-warning">
                        <x-iconsax-bul-timer class="icons text-warning" width="24px" height="24px"/>
                        @if($event->countdown_time_reference == "sales_end_date")
                            <span class="font-12">{{ trans('update.sales_ends_in') }}</span>
                        @else
                            <span class="font-12">{{ trans('update.event_starts_on') }}</span>
                        @endif

                        <div id="eventCountdownTime" class="time-counter-down d-flex align-items-center gap-4"
                             data-day="{{ $eventCountdownTimesString['day'] }}"
                             data-hour="{{ $eventCountdownTimesString['hour'] }}"
                             data-minute="{{ $eventCountdownTimesString['minute'] }}"
                        >
                            <div class="d-flex align-items-center font-12 font-weight-bold text-warning"><span class="days">0</span>d</div>
                            <div class="d-flex align-items-center font-12 font-weight-bold text-warning"><span class="hours">0</span>h</div>
                            <div class="d-flex align-items-center font-12 font-weight-bold text-warning"><span class="minutes">0</span>m</div>
                        </div>
                    </div>
                @endif
            @endif

            {{-- Guaranty Text --}}
            @if(!empty(getOthersPersonalizationSettings('show_guarantee_text')) and !empty(getGuarantyTextSettings("event_guaranty_text")))
                <div class="mt-14 d-flex align-items-center justify-content-center text-gray-500">
                    <x-iconsax-lin-shield-tick class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4 font-12">{{ getGuarantyTextSettings("event_guaranty_text") }}</span>
                </div>
            @endif

            {{-- This event includes --}}
            <div class="mt-20">
                <h4 class="font-12 font-weight-bold">{{ trans('update.this_event_includes') }}</h4>

                @if($event->certificate)
                    <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                        <x-iconsax-lin-medal class="icons text-gray-500" width="20px" height="20px"/>
                        <span class="ml-4">{{ trans('public.certificate') }}</span>
                    </div>
                @endif

                @if($event->support)
                    <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                        <x-iconsax-lin-message-question class="icons text-gray-500" width="20px" height="20px"/>
                        <span class="ml-4">{{ trans('update.provider_support') }}</span>
                    </div>
                @endif
            </div>

            {{-- Share & Calendar --}}
            <div class="d-flex align-items-center justify-content-around mt-16 p-12 rounded-12 border-dashed border-gray-200">

                @if(!empty($event->start_date))
                    <a href="{{ $event->addToCalendarLink() }}" target="_blank" class="d-flex-center flex-column text-gray-500 font-12">
                        <x-iconsax-lin-notification-bing class="icons text-gray-500" width="20px" height="20px"/>
                        <span class="mt-2">{{ trans('public.add_to_calendar') }}</span>
                    </a>
                @endif

                <div class="js-share-event d-flex-center flex-column text-gray-500 font-12 cursor-pointer" data-path="/events/{{ $event->slug }}/share-modal">
                    <x-iconsax-lin-share class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="mt-2">{{ trans('public.share') }}</span>
                </div>
            </div>

            {{-- Report --}}
            <div class="mt-24 text-center">
                @if(auth()->guest())
                    <a href="/login" class="font-12 text-gray-500">{{ trans('update.report_abuse') }}</a>
                @else
                    <button type="button" class="js-report-event font-12 text-gray-500 btn-transparent" data-path="/events/{{ $event->slug }}/report-modal">{{ trans('update.report_abuse') }}</button>
                @endif
            </div>
        </div>
    </div>

</div>


{{-- Course Specifications --}}
@include("design_1.web.events.show.includes.rightSide.specifications")

{{-- creator --}}
@include("design_1.web.events.show.includes.rightSide.creator", ['userRow' => $event->creator])


{{-- tags --}}
@if($event->tags->count() > 0)
    <div class="event-right-side-section position-relative mt-28">
        <div class="event-right-side-section__mask"></div>

        <div class="position-relative card-before-line bg-white rounded-24 p-16 z-index-2">
            <h4 class="font-14 font-weight-bold">{{ trans('public.tags') }}</h4>

            <div class="d-flex gap-12 flex-wrap mt-16">
                @foreach($event->tags as $tag)
                    <a href="/tags/events/{{ urlencode($tag->title) }}" target="_blank" class="d-flex-center p-10 rounded-8 bg-gray-100 font-12 text-gray-500 text-center">{{ $tag->title }}</a>
                @endforeach
            </div>
        </div>
    </div>
@endif
