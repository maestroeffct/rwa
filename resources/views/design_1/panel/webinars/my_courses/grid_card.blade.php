@php
    $nextSession = $webinar->nextSession();

    /*$lastSession = $webinar->lastSession();
    $isProgressing = false;

    if($webinar->start_date <= time() and !empty($lastSession) and $lastSession->date > time()) {
        $isProgressing=true;
    }*/

@endphp

<div class="course-grid-card {{ !empty($isInvitedCoursesPage) ? 'is-invited-course-card' : '' }} position-relative">
    <div class="course-grid-card__image position-relative rounded-16 bg-gray-100">
        <img src="{{ $webinar->getImage() }}" alt="" class="img-cover rounded-16">

        <div class="course-grid-card__actions-box d-flex align-items-start justify-content-between">
            {{-- Badges --}}
            @include("design_1.panel.webinars.my_courses.grid_card.grid_card_badges")

            {{-- Actions --}}
            @include("design_1.panel.webinars.my_courses.grid_card.grid_card_actions")
        </div>

        @if($webinar->isWebinar())
            <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                <x-iconsax-bol-video class="icons text-white" width="24px" height="24px"/>
            </div>
        @elseif($webinar->isTextCourse())
            <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                <x-iconsax-bol-note-2 class="icons text-white" width="24px" height="24px"/>
            </div>
        @elseif($webinar->isCourse())
            <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                <x-iconsax-bol-video-play class="icons text-white" width="24px" height="24px"/>
            </div>
        @endif

        @if(!empty($nextSession) and $nextSession->date > time() and checkTimestampInToday($nextSession->date))
            <div class="has-live-session-today-alert d-flex align-items-center justify-content-between p-12 rounded-12">
                <div class="d-flex align-items-center flex-1">
                    <x-iconsax-bol-video class="icons text-danger" width="16px" height="16px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('update.you_have_a_live_session_today') }}</span>
                </div>

                <div class="js-next-session-info d-flex align-items-center ml-8 cursor-pointer" data-webinar-id="{{ $webinar->id }}">
                    <span class="font-12 text-white">{{ trans('update.join_now') }}</span>
                    <x-iconsax-lin-arrow-right class="icons text-white" width="16px" height="16px"/>
                </div>
            </div>
        @endif
    </div>

    <div class="course-grid-card__body position-relative px-16 pb-12">
        <div class="course-grid-card__content d-flex flex-column bg-white p-12 rounded-16">

            <a href="{{ $webinar->getUrl() }}" target="_blank">
                <h3 class="course-grid-card__title font-14 text-black">{{ $webinar->title }}</h3>
            </a>

            @include("design_1.web.components.rate", [
                    'rate' => round($webinar->getRate(),1),
                    'rateCount' => $webinar->reviews()->where('status', 'active')->count(),
                    'rateClassName' => 'mt-12',
                ])

            <div class="d-grid grid-columns-2 gap-20 mt-16 p-16 rounded-8 border-gray-200 mb-16">

                @if(empty($isInvitedCoursesPage))
                    <div class="d-flex align-items-center font-12 text-gray-500">
                        <x-iconsax-lin-teacher class="icons text-gray-400" width="20px" height="20px"/>
                        <span class="ml-4 font-weight-bold">{{ count($webinar->sales) }}</span>
                        <span class="ml-4">{{ trans('public.students') }}</span>
                    </div>
                @endif

                <div class="d-flex align-items-center font-12 text-gray-500">
                    <x-iconsax-lin-note-2 class="icons text-gray-400" width="20px" height="20px"/>
                    <span class="ml-4 font-weight-bold">{{ $webinar->getAllLessonsCount() }}</span>
                    <span class="ml-4">{{ trans('update.lessons') }}</span>
                </div>

                @if(empty($isInvitedCoursesPage))
                    <div class="d-flex align-items-center font-12 text-gray-500">
                        <x-iconsax-lin-moneys class="icons text-gray-400" width="20px" height="20px"/>
                        <span class="ml-4 font-weight-bold">{{ handlePrice($webinar->sales->sum('amount')) }}</span>
                        <span class="ml-4">{{ trans('panel.sales') }}</span>
                    </div>
                @endif

                <div class="d-flex align-items-center font-12 text-gray-500">
                    <x-iconsax-lin-clock-1 class="icons text-gray-400" width="20px" height="20px"/>
                    <span class="ml-4 font-weight-bold">{{ convertMinutesToHourAndMinute($webinar->duration) }}</span>
                    <span class="ml-4">{{ trans('home.hours') }}</span>
                </div>
            </div>


            <div class="d-flex align-items-center justify-content-between mt-auto">

                @if(!empty($isInvitedCoursesPage))
                    <div class="d-flex align-items-center">
                        <div class="size-32 rounded-circle bg-gray-100">
                            <img src="{{ $webinar->teacher->getAvatar(32) }}" alt="{{ $webinar->teacher->full_name }}" class="img-cover rounded-circle">
                        </div>
                        <div class="ml-8">
                            <h6 class="font-12 text-gray-500">{{ trans('panel.invited_by') }}</h6>
                            <p class="mt-2 font-12 font-weight-bold">{{ $webinar->teacher->full_name }}</p>
                        </div>
                    </div>
                @else
                    {{-- Chart --}}
                    @include("design_1.panel.webinars.my_courses.grid_card.grid_card_progress_chart")
                @endif

                <div class="d-flex align-items-center font-16 font-weight-bold text-success">
                    @if($webinar->price > 0)
                        @if($webinar->bestTicket() < $webinar->price)
                            <span class="">{{ handlePrice($webinar->bestTicket(), true, true, false, null, true) }}</span>
                            <span class="font-14 font-weight-400 text-gray-500 ml-8 text-decoration-line-through">{{ handlePrice($webinar->price, true, true, false, null, true) }}</span>
                        @else
                            <span class="">{{ handlePrice($webinar->price, true, true, false, null, true) }}</span>
                        @endif
                    @else
                        <span class="">{{ trans('public.free') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
