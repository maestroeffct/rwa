@if(!empty($topMentorsInstructors) and count($topMentorsInstructors))
    <div class="position-relative bg-primary rounded-24 py-16">
        <div class="instructor-finder__top-mentors-mask"></div>

        <div class="position-relative z-index-2">

            <div class="px-16">
                <h5 class="instructor-finder__filters-title filters-title-white font-14 font-weight-bold text-white">{{ trans('update.top_mentors') }}</h5>
            </div>

            <div class="swiper-container js-make-swiper top-mentors-instructors pb-0"
                 data-item="top-mentors-instructors"
                 data-autoplay="true"
                 data-breakpoints="1440:1,769:1,320:1"
                 data-navigation="true"
            >
                <div class="swiper-button-next instructor-finder__top-mentors-slider-navigation rounded-circle bg-white-20">
                    <x-iconsax-lin-arrow-right class="icons text-white" width="16px" height="16px"/>
                </div>

                <div class="swiper-button-prev instructor-finder__top-mentors-slider-navigation rounded-circle bg-white-20">
                    <x-iconsax-lin-arrow-left class="icons text-white" width="16px" height="16px"/>
                </div>

                <div class="swiper-wrapper py-32">
                    @foreach($topMentorsInstructors as $topMentorInstructor)
                        <div class="swiper-slide">
                            <div class="position-relative d-flex-center flex-column text-center">

                                <a href="{{ $topMentorInstructor->getProfileUrl() }}" class="">
                                    <div class="instructor-finder__top-mentors-avatar size-80 rounded-circle">
                                        <img src="{{ $topMentorInstructor->getAvatar(80) }}" alt="{{ $topMentorInstructor->full_name }}" class="position-relative img-cover rounded-circle">
                                    </div>
                                </a>

                                <a href="{{ $topMentorInstructor->getProfileUrl() }}" class="">
                                    <h6 class="mt-28 font-16 font-weight-bold text-white">{{ $topMentorInstructor->full_name }}</h6>
                                </a>

                                <div class="position-relative mt-16 d-flex align-items-center w-100">
                                    <div class="d-flex-center flex-column text-center flex-1">
                                        <div class="font-14 font-weight-bold text-white">{{ $topMentorInstructor->total_meetings ?? 0 }}</div>
                                        <div class="mt-4 font-12 text-white">{{ trans('panel.total_meetings') }}</div>
                                    </div>

                                    <div class="instructor-finder__top-mentors-divider"></div>

                                    <div class="d-flex-center flex-column text-center flex-1">
                                        <div class="font-14 font-weight-bold text-white">{{ !empty($topMentorInstructor->meeting_hours) ? $topMentorInstructor->meeting_hours : 0 }}</div>
                                        <div class="mt-4 font-12 text-white">{{ trans('update.meeting_hours') }}</div>
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
