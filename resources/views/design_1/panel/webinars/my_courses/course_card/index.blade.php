@php
    $nextSession = $course->nextSession();

    /*$lastSession = $course->lastSession();
    $isProgressing = false;

    if($course->start_date <= time() and !empty($lastSession) and $lastSession->date > time()) {
        $isProgressing=true;
    }*/

@endphp

<div class="panel-course-card-1 position-relative {{ !empty($isInvitedCoursesPage) ? 'is-invited-course-card' : '' }}">
    <div class="card-mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row gap-12 z-index-2 bg-white p-12 rounded-24">
        <a href="{{ $course->getUrl() }}" target="_blank" class="d-flex flex-column flex-lg-row gap-12 flex-grow-1 text-decoration-none">
            {{-- Image --}}
            <div class="panel-course-card-1__image position-relative rounded-16 bg-gray-100">
                <img src="{{ $course->getImage() }}" alt="" class="img-cover rounded-16">
                {{-- Badges On Image --}}
                @include("design_1.panel.webinars.my_courses.course_card.badges")

                @if($course->isWebinar())
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <x-iconsax-bol-video class="icons text-white" width="24px" height="24px"/>
                    </div>
                @elseif($course->isTextCourse())
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <x-iconsax-bol-note-2 class="icons text-white" width="24px" height="24px"/>
                    </div>
                @elseif($course->isCourse())
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <x-iconsax-bol-video-play class="icons text-white" width="24px" height="24px"/>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="panel-course-card-1__content flex-1 d-flex flex-column">
                <div class="bg-gray-100 p-16 rounded-16 mb-12">
                    <div class="d-flex align-items-start justify-content-between gap-12">
                        <div class="">
                            <h3 class="font-16 text-dark">{{ truncate($course->title, 46) }}</h3>

                            @include("design_1.web.components.rate", [
                                'rate' => round($course->getRate(),1),
                                'rateCount' => $course->reviews()->where('status', 'active')->count(),
                                'rateClassName' => 'mt-8',
                            ])
                        </div>
                    </div>

                    {{-- Stats --}}
                    @include("design_1.panel.webinars.my_courses.course_card.stats")
                    {{-- End Stats --}}
                </div>

                {{-- Progress & Price --}}
                <div class="row align-items-center justify-content-between mt-auto">
                    <div class="col-10">
                        @include("design_1.panel.webinars.my_courses.course_card.progress_and_chart")
                    </div>

                    {{-- Price --}}
                    <div class="col-2 d-flex align-items-center justify-content-end font-16 font-weight-bold text-primary">
                        @if($course->price > 0)
                            @if($course->bestTicket() < $course->price)
                                <span class="">{{ handlePrice($course->bestTicket(), true, true, false, null, true) }}</span>
                                <span class="font-14 font-weight-400 text-gray-500 ml-8 text-decoration-line-through">{{ handlePrice($course->price, true, true, false, null, true) }}</span>
                            @else
                                <span class="">{{ handlePrice($course->price, true, true, false, null, true) }}</span>
                            @endif
                        @else
                            <span class="">{{ trans('public.free') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Actions Dropdown (positioned outside the link) --}}
    <div class="item-card-actions-dropdown-container">
        @include("design_1.panel.webinars.my_courses.course_card.actions_dropdown")
    </div>
</div>
