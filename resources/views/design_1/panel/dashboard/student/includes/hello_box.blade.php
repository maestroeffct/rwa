<div class="student-dashboard__hello-box position-relative mt-54 w-100">
    <div class="student-dashboard__hello-box-bg rounded-24"></div>

    <div class="position-relative z-index-3 p-16 rounded-24 w-100 h-100">
        <div class="row">
            <div class="col-12 col-lg-6">
                <h1 class="font-24 font-weight-bold text-white text-ellipsis">{{ trans('update.hello_user', ['user' => $authUser->full_name]) }} 👋</h1>
                <p class="mt-8 font-14 text-white opacity-75 text-ellipsis">{{ trans('update.welcome_and_let’s_start_effective_education_today!') }}</p>

                <div class="row mt-24">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                                <x-iconsax-bul-video-play class="icons text-white" width="24px" height="24px"/>
                            </div>
                            <div class="ml-8">
                                <span class="d-block font-weight-bold text-white">{{ $helloBox['coursesCount'] }}</span>
                                <span class="d-block mt-4 text-white opacity-75 text-ellipsis">{{ trans('update.courses') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                                <x-iconsax-bul-video class="icons text-white" width="24px" height="24px"/>
                            </div>
                            <div class="ml-8">
                                <span class="d-block font-weight-bold text-white">{{ $helloBox['meetingsCount'] }}</span>
                                <span class="d-block mt-4 text-white opacity-75 text-ellipsis">{{ trans('panel.meetings') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-24">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                                <x-iconsax-bul-award class="icons text-white" width="24px" height="24px"/>
                            </div>
                            <div class="ml-8">
                                <span class="d-block font-weight-bold text-white">{{ $helloBox['certificatesCount'] }}</span>
                                <span class="d-block mt-4 text-white opacity-75 text-ellipsis">{{ trans('panel.certificates') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                                <x-iconsax-bul-video class="icons text-white" width="24px" height="24px"/>
                            </div>
                            <div class="ml-8">
                                <span class="d-block font-weight-bold text-white">{{ $helloBox['passedQuizCount'] }}</span>
                                <span class="d-block mt-4 text-white opacity-75 text-ellipsis">{{ trans('update.passed_quizzes') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 position-relative d-none d-lg-block">
                <div class="hello-box-user-vector d-flex justify-content-end">
                    <img src="/assets/design_1/img/panel/dashboard/student/hello-box-user-vector.svg" alt="" class="img-fluid">
                </div>
            </div>
        </div>

        <div class="mt-52">
            @if(!empty($helloBox['continueLearningCourses']) and count($helloBox['continueLearningCourses']))
                <h4 class="font-14 font-weight-bold text-white">{{ trans('update.continue_learning') }}</h4>

                <div class="row">
                    @foreach($helloBox['continueLearningCourses'] as $continueLearningCourse)
                        <div class="student-dashboard__hello-box-course-col col-12 col-lg-6 mt-12">
                            <a href="{{ $continueLearningCourse->getLearningPageUrl() }}" target="_blank" class="d-block">
                                <div class="card-with-mask position-relative">
                                    <div class="mask-8-white"></div>
                                    <div class="position-relative z-index-2 bg-white py-16 rounded-16">
                                        <div class="d-flex align-items-center px-16 mb-16">
                                            <div class="size-40 bg-gray-100 rounded-8">
                                                <img src="{{ $continueLearningCourse->getIcon() }}" alt="" class="img-cover rounded-8">
                                            </div>
                                            <div class="ml-8">
                                                <h6 class="font-12 text-dark text-ellipsis">{{ truncate($continueLearningCourse->title, 30) }}</h6>
                                                <div class="font-12 mt-4 text-gray-500">{{ dateTimeFormat($continueLearningCourse->created_at, 'j M Y') }}</div>
                                            </div>
                                        </div>

                                        @php
                                            $continueLearningCourseProgress = $continueLearningCourse->getProgress(true);
                                        @endphp

                                        <div class="mb-12 px-16">
                                            <div class="progress-card d-flex bg-gray-100">
                                                <div class="progress-bar bg-primary" style="width: {{ $continueLearningCourseProgress }}%"></div>
                                            </div>

                                            <div class="d-flex align-items-center gap-4 mt-8 font-12">
                                                <span class="font-weight-bold text-dark">{{ $continueLearningCourseProgress }}%</span>
                                                <span class="text-gray-500">{{ trans('update.completed') }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between px-16 pt-16 border-top-gray-200">
                                            <div class="d-flex align-items-center gap-4 font-12">
                                                <x-iconsax-bul-video-play class="icons text-gray-500" width="20px" height="20px"/>
                                                <span class="font-weight-bold text-dark">{{ $continueLearningCourse->getAllLessonsCount() }}</span>
                                                <span class="text-gray-500">{{ trans('update.lessons') }}</span>
                                            </div>

                                            <div class="d-flex align-items-center text-gray-500 gap-4">
                                                <span class="font-12 font-weight-bold">{{ trans('update.continue_learning') }}</span>
                                                <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @elseif(!empty($helloBox['enrollOnCoursesFromAdmin']) and count($helloBox['enrollOnCoursesFromAdmin']))
                <h4 class="font-14 font-weight-bold text-white">{{ trans('update.enroll_on_course') }}</h4>

                <div class="row mt-12">
                    @foreach($helloBox['enrollOnCoursesFromAdmin'] as $enrollOnCourseFromAdmin)
                        <div class="col-6">
                            <a href="{{ $enrollOnCourseFromAdmin->getUrl() }}" target="_blank" class="d-block">
                                <div class="card-with-mask position-relative">
                                    <div class="mask-8-white"></div>
                                    <div class="position-relative z-index-2 bg-white py-16 rounded-16">
                                        <div class="d-flex align-items-center px-16 mb-16">
                                            <div class="size-40 bg-gray-100 rounded-8">
                                                <img src="{{ $enrollOnCourseFromAdmin->getIcon() }}" alt="" class="img-cover rounded-8">
                                            </div>
                                            <div class="ml-8">
                                                <h6 class="font-12 text-dark text-ellipsis">{{ truncate($enrollOnCourseFromAdmin->title, 30) }}</h6>
                                                <div class="font-12 mt-4 text-gray-500">{{ $enrollOnCourseFromAdmin->teacher->full_name }}</div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between px-16 pt-16 border-top-gray-200">
                                            <div class="d-flex align-items-center gap-4 font-12">
                                                <x-iconsax-bul-video-play class="icons text-gray-500" width="20px" height="20px"/>
                                                <span class="font-weight-bold text-dark">{{ $enrollOnCourseFromAdmin->getAllLessonsCount() }}</span>
                                                <span class="text-gray-500">{{ trans('update.lessons') }}</span>
                                            </div>

                                            <div class="d-flex align-items-center text-gray-500 gap-4">
                                                <span class="font-12 font-weight-bold">{{ trans('update.enroll') }}</span>
                                                <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>
