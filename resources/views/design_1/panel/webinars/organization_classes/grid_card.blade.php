<a href="{{ $course->getUrl() }}" target="_blank" class="text-decoration-none d-block">
    <div class="panel-course-grid-card {{ !empty($isInvitedCoursesPage) ? 'is-invited-course-card' : '' }} position-relative">
        <div class="panel-course-grid-card__image position-relative rounded-16 bg-gray-100">
            <img src="{{ $course->getImage() }}" alt="" class="img-cover rounded-16">

            @if(!empty($pageSource) and $pageSource == "bundles")
                <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                    <x-iconsax-bol-box class="icons text-white" width="24px" height="24px"/>
                </div>
            @else

                <div class="panel-course-grid-card__actions-box d-flex align-items-start justify-content-between">
                    {{-- Badges --}}
                    @include("design_1.panel.webinars.my_courses.grid_card.grid_card_badges")

                    {{-- Actions --}}
                    {{--@include("design_1.panel.webinars.my_courses.grid_card.grid_card_actions")--}}
                </div>

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
            @endif
        </div>

        <div class="panel-course-grid-card__body position-relative px-16 pb-12">
            <div class="panel-course-grid-card__content is-favorites-card d-flex flex-column bg-white p-12 rounded-16">

                <h3 class="panel-course-grid-card__title font-14 text-dark">{{ $course->title }}</h3>

                @include("design_1.web.components.rate", [
                        'rate' => round($course->getRate(),1),
                        'rateCount' => $course->reviews()->where('status', 'active')->count(),
                        'rateClassName' => 'mt-12',
                    ])

                <div class="d-flex align-items-center my-16 ">
                    <div class="size-32 rounded-circle bg-gray-100">
                        <img src="{{ $course->teacher->getAvatar(32) }}" alt="{{ $course->teacher->full_name }}" class="img-cover rounded-circle">
                    </div>
                    <div class="ml-8">
                        <h6 class="font-12 font-weight-bold text-dark">{{ $course->teacher->full_name }}</h6>
                        <p class="mt-2 font-12 text-gray-500">{{ $course->teacher->bio }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mt-auto border-top-gray-100 pt-12">

                    <div class="d-flex align-items-center font-16 font-weight-bold text-primary flex-1">
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

                    @php
                        $itemDuration = (!empty($pageSource) and $pageSource == "bundles") ? $course->getBundleDuration() : $course->duration;
                    @endphp

                    <div class="d-flex align-items-center">
                        <x-iconsax-lin-clock-1 class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-2 font-12 text-gray-500">{{ convertMinutesToHourAndMinute($itemDuration) }} {{ trans('home.hours') }}</span>
                    </div>

                </div>

            </div>
        </div>
    </div>
</a>
