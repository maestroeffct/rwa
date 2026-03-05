@if(!empty($upcomingLiveSessions) and count($upcomingLiveSessions))
    <div class="mt-28">
        <h3 class="font-16 font-weight-bold">{{ trans('update.upcoming_live_sessions') }}</h3>
        <p class="mt-4 font-12 text-gray-500">{{ trans('update.check_upcoming_live_sessions_and_join_them_on_time') }}</p>


        <div class="position-relative mt-16">
            <div class="swiper-container js-make-swiper live-sessions-swiper pb-24"
                 data-item="live-sessions-swiper"
                 data-autoplay="true"
                 data-breakpoints="1440:3,769:2,320:1"
            >
                <div class="swiper-wrapper py-8">
                    @foreach($upcomingLiveSessions as $upcomingLiveSession)
                        <div class="swiper-slide">
                            <div class="upcoming-live-sessions-card d-flex align-items-center position-relative bg-white rounded-16 p-20">
                                <div class="d-flex-center size-64 rounded-12 bg-primary">
                                    <x-iconsax-bul-video class="icons text-white" width="32px" height="32px"/>
                                </div>

                                <div class="ml-8 flex-1">
                                    <div class="d-flex align-items-end justify-content-between w-100">
                                        <div class="">
                                            <h6 class="font-14 font-weight-bold">{{ $upcomingLiveSession->title }}</h6>
                                            <p class="text-ellipsis mt-4 font-12 text-gray-500">{{ truncate($upcomingLiveSession->webinar->title, 28) }}</p>
                                        </div>

                                        @if($upcomingLiveSession->date > time() and checkTimestampInToday($upcomingLiveSession->date))
                                            <div class="js-next-session-info d-flex align-items-center ml-8 cursor-pointer" data-webinar-id="{{ $upcomingLiveSession->webinar_id }}">
                                                <span class="font-12 text-primary mr-4">{{ trans('update.join_now') }}</span>
                                                <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                                            </div>
                                        @else
                                            <a href="{{ $upcomingLiveSession->addToCalendarLink() }}" target="_blank" class="d-flex align-items-center ml-8 cursor-pointer">
                                                <span class="font-12 text-primary mr-4">{{ trans('update.add_to_reminder') }}</span>
                                                <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                                            </a>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center mt-12">

                                        <div class="d-flex align-items-center mr-16">
                                            <x-iconsax-lin-timer-1 class="icons text-gray-400" width="16px" height="16px"/>
                                            <span class="ml-4 font-12 text-gray-400">{{ $upcomingLiveSession->duration }} {{ trans('public.min') }}</span>
                                        </div>

                                        @if($upcomingLiveSession->date > time() and checkTimestampInToday($upcomingLiveSession->date))
                                            <div class="d-flex align-items-center mr-16">
                                                <x-iconsax-lin-calendar-2 class="icons text-warning" width="16px" height="16px"/>
                                                <span class="ml-4 font-12 text-warning">{{ trans('update.today') }} {{ dateTimeFormat($upcomingLiveSession->date, 'H:i', false, true, ($upcomingLiveSession->webinar ? $upcomingLiveSession->webinar->timezone : null)) }}</span>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center mr-16">
                                                <x-iconsax-lin-calendar-2 class="icons text-gray-400" width="16px" height="16px"/>
                                                <span class="ml-4 font-12 text-gray-400">{{ dateTimeFormat($upcomingLiveSession->date, 'j M Y H:i', false, true, ($upcomingLiveSession->webinar ? $upcomingLiveSession->webinar->timezone : null)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endif
