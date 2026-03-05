<div class="bg-white p-16 rounded-24 mt-24">
    <h4 class="font-14 text-dark">{{ trans('update.upcoming_live_sessions') }}</h4>

    @if(!empty($upcomingLiveSessions['totalSessions']))

        <div class="d-flex align-items-center justify-content-between p-12 rounded-16 bg-gray-100 mt-16">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                    <x-iconsax-bul-video class="icons text-primary" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <span class="d-block font-weight-bold text-dark">{{ $upcomingLiveSessions['totalSessions'] }}</span>
                    <span class="d-block font-12 text-gray-500 mt-4">{{ trans('update.upcoming_live_sessions') }}</span>
                </div>
            </div>

            <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
        </div>

        {{-- Card --}}
        @foreach($upcomingLiveSessions['sessions'] as $upcomingLiveSession)
            <div class="bg-gray-100 rounded-16 p-12 mt-16">
                <div class="d-flex align-items-center">
                    <div class="size-48 rounded-12">
                        <img src="{{ $upcomingLiveSession->webinar->getIcon() }}" alt="" class="rounded-12 img-cover">
                    </div>
                    <div class="ml-8">
                        <h6 class="font-14 text-dark">{{ truncate($upcomingLiveSession->title, 22) }}</h6>
                        <p class="font-12 text-gray-500 mt-4">{{ truncate($upcomingLiveSession->webinar->title, 25) }}</p>
                    </div>
                </div>

                <div class="bg-white py-16 rounded-12 mt-16">
                    <div class="d-flex align-items-center px-16">
                        <div class="size-40 rounded-circle bg-gray-100">
                            <img src="{{ $upcomingLiveSession->creator->getAvatar(40) }}" alt="" class="img-cover rounded-circle">
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-12 text-dark">{{ truncate($upcomingLiveSession->creator->full_name, 29) }}</span>
                            <span class="d-block font-12 text-gray-500 mt-2">{{ $upcomingLiveSession->creator->role->caption }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-16 px-16 pt-16 border-top-gray-100">
                        <div class="d-flex-center bg-gray-200 rounded-12 p-8">
                            <x-iconsax-bul-calendar-2 class="icons text-gray-500" width="24px" height="24px"/>
                            <span class="ml-4 font-12 text-gray-500">{{ dateTimeFormat($upcomingLiveSession->date, 'j M Y H:i') }}</span>
                        </div>

                        <a href="{{ $upcomingLiveSession->webinar->getLearningPageUrl() }}?type=session&item={{ $upcomingLiveSession->id }}" target="_blank" class="d-flex-center size-40 rounded-circle bg-primary">
                            <x-iconsax-lin-arrow-right class="icons text-white" width="16px" height="16px"/>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        {{-- If Empty --}}
        <div class="d-flex-center flex-column text-center mt-20 border-dashed border-gray-200 bg-gray-100 p-32 rounded-16">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <x-iconsax-bul-video class="icons text-primary" width="24px" height="24px"/>
            </div>
            <h5 class="font-14 text-dark mt-12">{{ trans('update.no_live_session!') }}</h5>
            <div class="mt-4 font-12 text-gray-500">{{ trans('update.you_don’t_have_any_upcoming_live_session_you_can_find_your_desired_live_course') }}</div>
        </div>

        @if(!empty($upcomingLiveSessions['offerCourses']) and count($upcomingLiveSessions['offerCourses']))
            @foreach($upcomingLiveSessions['offerCourses'] as $offerCourse)
                <a href="{{ $offerCourse->getUrl() }}" target="_blank" class="">
                    <div class="d-flex align-items-center justify-content-between bg-gray-100 p-12 rounded-16 mt-16">
                        <div class="d-flex align-items-center">
                            <div class="size-48 rounded-8 bg-gray-100">
                                <img src="{{ $offerCourse->getIcon() }}" alt="" class="img-cover rounded-8">
                            </div>
                            <div class="ml-8">
                                <h6 class="font-12 text-dark">{{ truncate($offerCourse->title, 28) }}</h6>

                                @include('design_1.web.components.rate', [
                                    'rate' => $offerCourse->getRate(),
                                    'rateClassName' => 'mt-8',
                                ])
                            </div>
                        </div>

                        <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                </a>
            @endforeach
        @endif

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="">
                <h6 class="font-14 text-dark">{{ trans('update.explore_live_classes') }}</h6>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.find_your_desired_live_class') }}</p>
            </div>

            <a href="/classes?type[]=webinar" target="_blank" class="d-flex-center size-40 bg-white border-gray-200 rounded-circle bg-hover-gray-100">
                <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
            </a>
        </div>
    @endif
</div>
