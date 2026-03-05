<div class="bg-white p-16 rounded-24 w-100">
    <h4 class="font-14 font-weight-bold text-dark">{{ trans('update.courses_overview') }}</h4>

    @if(!empty($coursesOverview['courses']) and count($coursesOverview['courses']))
        <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-16 mt-16">
            {{-- Total Courses --}}
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12">{{ trans('update.total_courses') }}</span>
                    <span class="font-24 font-weight-bold mt-16 text-dark">{{ $coursesOverview['totalCourses'] }}</span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                    <x-iconsax-bul-video-play class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>

            {{-- Completed Courses --}}
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12">{{ trans('update.completed_courses') }}</span>
                    <span class="font-24 font-weight-bold mt-16 text-dark">{{ $coursesOverview['completedCourses'] }}</span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-success-40">
                    <x-iconsax-bul-tick-circle class="icons text-success" width="24px" height="24px"/>
                </div>
            </div>

            {{-- Open Courses --}}
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12">{{ trans('update.open_courses') }}</span>
                    <span class="font-24 font-weight-bold mt-16 text-dark">{{ $coursesOverview['openCourses'] }}</span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-warning-40">
                    <x-iconsax-bul-more-circle class="icons text-warning" width="24px" height="24px"/>
                </div>
            </div>
        </div>

        {{-- Course Card --}}
        @foreach($coursesOverview['courses']->take(5) as $overviewBoxCourse)
            <a href="{{ $overviewBoxCourse->getLearningPageUrl() }}" target="_blank" class="d-block">
                <div class="row align-items-center mt-24">
                    <div class="col-12 col-lg-5">
                        <div class="d-flex align-items-center flex-1">
                            <div class="size-48 rounded-12 bg-gray-100">
                                <img src="{{ $overviewBoxCourse->getIcon() }}" alt="" class="img-cover rounded-12">
                            </div>
                            <div class="ml-8">
                                <h5 class="font-14 text-dark text-ellipsis d-none d-lg-block">{{ truncate($overviewBoxCourse->title, 23) }}</h5>
                                <h5 class="font-14 text-dark text-ellipsis d-block d-lg-none">{{ truncate($overviewBoxCourse->title, 45) }}</h5>
                                <span class="d-block mt-4 font-12 text-gray-500">{{ trans('public.by') }} {{ $overviewBoxCourse->teacher->full_name }}</span>
                            </div>
                        </div>
                    </div>

                    @php
                        $overviewBoxCourseProgress = $overviewBoxCourse->getProgress(true);
                        $overviewBoxCourseQuizzes = $overviewBoxCourse->getQuizzesLearningProgressStat();
                        $overviewBoxCourseAssignments = $overviewBoxCourse->getAssignmentsLearningProgressStat();
                    @endphp

                    <div class="col-lg-4 d-none d-lg-block">
                        <div class="d-flex align-items-center gap-4 font-12">
                            <span class="font-weight-bold text-dark">{{ $overviewBoxCourseProgress }}%</span>
                            <span class="text-gray-500">{{ trans('update.progress') }}</span>
                        </div>

                        <div class="progress-card d-flex bg-gray-100 mt-8">
                            <div class="progress-bar bg-primary" style="width: {{ $overviewBoxCourseProgress }}%"></div>
                        </div>
                    </div>

                    <div class="col-lg-3 d-none d-lg-block">
                        <div class="d-flex align-items-center justify-content-end">
                            {{-- Quiz --}}
                            @if(!empty($overviewBoxCourseQuizzes['count']))
                                <div class="d-flex align-items-center gap-4">
                                    <x-iconsax-bul-clipboard-tick class="icons text-gray-500" width="20px" height="20px"/>
                                    <span class="font-12 text-gray-500">{{ $overviewBoxCourseQuizzes['passed'] }}/{{ $overviewBoxCourseQuizzes['count'] }}</span>
                                </div>
                            @endif

                            @if(!empty($overviewBoxCourseQuizzes['count']) and !empty($overviewBoxCourseAssignments['count']))
                                <div class="size-4 bg-gray-300 rounded-circle mx-12"></div>
                            @endif

                            {{-- Assignment --}}
                            @if(!empty($overviewBoxCourseAssignments['count']))
                                <div class="d-flex align-items-center gap-4">
                                    <x-iconsax-bul-clipboard-text class="icons text-gray-500" width="20px" height="20px"/>
                                    <span class="font-12 text-gray-500">{{ $overviewBoxCourseAssignments['passed'] }}/{{ $overviewBoxCourseAssignments['count'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        @endforeach

    @else
        {{-- If Empty --}}
        <div class="bg-gray-100 border-dashed border-gray-200 rounded-16 mt-16">
            <div class="d-flex-center flex-column text-center pt-32 pb-28">
                <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                    <x-iconsax-bul-video-play class="icons text-primary" width="24px" height="24px"/>
                </div>

                <h4 class="mt-12 font-14 text-dark">{{ trans('update.no_course!') }}</h4>
                <div class="font-12 text-gray-500 mt-4">{{ trans('update.you_don’t_have_any_upcoming_live_session_you_can_find_your_desired_live_course') }}</div>
            </div>

            @if(!empty($coursesOverview['overviewCoursesFromAdmin']) and count($coursesOverview['overviewCoursesFromAdmin']))
                <div class="d-grid grid-columns-2 gap-16 px-16 pb-24">
                    @foreach($coursesOverview['overviewCoursesFromAdmin'] as $overviewCourseFromAdmin)
                        <a href="{{ $overviewCourseFromAdmin->getUrl() }}" target="_blank" class="text-decoration-none">
                            <div class="card-with-mask position-relative">
                                <div class="mask-8-white"></div>
                                <div class="position-relative z-index-2 bg-white py-16 rounded-16">
                                    <div class="d-flex align-items-center px-16">
                                        <div class="size-40 rounded-8 bg-gray-100">
                                            <img src="{{ $overviewCourseFromAdmin->getIcon() }}" alt="" class="img-cover rounded-8">
                                        </div>
                                        <div class="ml-8">
                                            <h6 class="font-12 text-dark text-ellipsis">{{ truncate($overviewCourseFromAdmin->title, 23) }}</h6>

                                            @include('design_1.web.components.rate', [
                                                'rate' => $overviewCourseFromAdmin->getRate(),
                                                'rateCount' => $overviewCourseFromAdmin->getRateCount(),
                                                'rateClassName' => 'mt-8',
                                            ])
                                        </div>
                                    </div>

                                    @php
                                        $overviewCourseFromAdminCreator = $overviewCourseFromAdmin->creator;
                                    @endphp

                                    <div class="d-flex align-items-center justify-content-between px-16 pt-16 mt-16 border-top-gray-200">
                                        <div class="d-flex align-items-center">
                                            <div class="size-32 rounded-circle bg-gray-100">
                                                <img src="{{ $overviewCourseFromAdminCreator->getAvatar(32) }}" alt="" class="img-cover rounded-circle">
                                            </div>
                                            <div class="ml-8">
                                                <h5 class="font-12 text-dark">{{ $overviewCourseFromAdminCreator->full_name }}</h5>
                                                <span class="d-block font-12 text-gray-500 mt-2">{{ $overviewCourseFromAdminCreator->role->caption }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center text-gray-500 gap-4">
                                            <span class="font-12 font-weight-bold">{{ trans('update.enroll_now') }}</span>
                                            <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="">
                <h5 class="font-14 text-dark">{{ trans('update.need_more_courses') }}</h5>
                <div class="mt-2 font-12 text-gray-500">{{ trans('update.explore_all_courses_and_find_a_professional_course') }}</div>
            </div>

            <a href="/classes" target="_blank" class="d-flex-center size-40 bg-white border-gray-200 rounded-circle bg-hover-gray-100">
                <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
            </a>
        </div>

    @endif

</div>
