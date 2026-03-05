<div class="bg-white p-16 rounded-24 w-100">
    <h4 class="font-14 font-weight-bold text-dark">{{ trans('update.courses_overview') }}</h4>

    @if(!empty($coursesOverview['courses']) and count($coursesOverview['courses']))
        <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-16 mt-16">
            {{-- Live Courses --}}
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12">{{ trans('update.live_classes') }}</span>
                    <span class="font-24 font-weight-bold mt-16 text-dark">{{ $coursesOverview['totalLiveCourses'] }}</span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                    <x-iconsax-bul-video-play class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>

            {{-- Video Courses --}}
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12">{{ trans('update.video_courses') }}</span>
                    <span class="font-24 font-weight-bold mt-16 text-dark">{{ $coursesOverview['totalVideoCourses'] }}</span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-success-40">
                    <x-iconsax-bul-box-1 class="icons text-success" width="24px" height="24px"/>
                </div>
            </div>

            {{-- Text Courses --}}
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12">{{ trans('update.text_courses') }}</span>
                    <span class="font-24 font-weight-bold mt-16 text-dark">{{ $coursesOverview['totalTextCourses'] }}</span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-warning-40">
                    <x-iconsax-bul-video class="icons text-warning" width="24px" height="24px"/>
                </div>
            </div>
        </div>

        {{-- Course Card --}}
        @foreach($coursesOverview['courses'] as $overviewBoxCourse)
            <a href="{{ $overviewBoxCourse->getLearningPageUrl() }}" target="_blank" class="d-block">
                <div class="row align-items-center mt-24">
                    <div class="col-12 col-lg-5">
                        <div class="d-flex align-items-center flex-1">
                            <div class="size-48 rounded-12 bg-gray-100">
                                <img src="{{ $overviewBoxCourse->getIcon() }}" alt="" class="img-cover rounded-12">
                            </div>
                            <div class="ml-8">
                                <h5 class="font-14 text-dark text-ellipsis d-none d-lg-block">{{ truncate($overviewBoxCourse->title, 28) }}</h5>
                                <h5 class="font-14 text-dark text-ellipsis d-block d-lg-none">{{ truncate($overviewBoxCourse->title, 45) }}</h5>
                                <span class="d-block mt-4 font-12 text-gray-500">{{ !empty($overviewBoxCourse->category) ? $overviewBoxCourse->category->title : trans('update.no_category') }}</span>
                            </div>
                        </div>
                    </div>

                    @php
                        $overviewBoxCourseProgress = $overviewBoxCourse->getAverageLearning();
                    @endphp

                    <div class="col-lg-4 d-none d-lg-block">
                        <div class="d-flex align-items-center gap-4 font-12">
                            <span class="font-weight-bold text-dark">{{ $overviewBoxCourseProgress }}%</span>
                            <span class="text-gray-500">{{ trans('update.avg_learning') }}</span>
                        </div>

                        <div class="progress-card d-flex bg-gray-100 mt-8">
                            <div class="progress-bar bg-primary" style="width: {{ $overviewBoxCourseProgress }}%"></div>
                        </div>
                    </div>

                    <div class="col-lg-3 d-none d-lg-block">
                        <div class="d-flex align-items-center justify-content-end">
                            {{-- Quiz --}}
                            @if(!empty($overviewBoxCourse->sales_count) and $overviewBoxCourse->sales_count > 0)
                                <div class="d-flex align-items-center gap-4">
                                    <x-iconsax-bul-teacher class="icons text-gray-500" width="20px" height="20px"/>
                                    <span class="font-12 text-gray-500">{{ $overviewBoxCourse->sales_count }}</span>
                                </div>
                            @endif

                            @if(!empty($overviewBoxCourse->sales_count) and !empty($overviewBoxCourse->sales_amount))
                                <div class="size-4 bg-gray-300 rounded-circle mx-12"></div>
                            @endif

                            {{-- Assignment --}}
                            @if(!empty($overviewBoxCourse->sales_amount))
                                <div class="d-flex align-items-center gap-4">
                                    <x-iconsax-bul-moneys class="icons text-gray-500" width="20px" height="20px"/>
                                    <span class="font-12 text-gray-500">{{ handlePrice($overviewBoxCourse->sales_amount) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        @endforeach

    @else
        {{-- If Empty --}}
        <div class="d-flex-center flex-column text-center bg-gray-100 border-dashed border-gray-200 rounded-16 mt-16 p-60">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <x-iconsax-bul-video-play class="icons text-primary" width="24px" height="24px"/>
            </div>

            <h4 class="mt-12 font-14 text-dark">{{ trans('update.no_course!') }}</h4>
            <div class="font-12 text-gray-500 mt-4">{{ trans('update.instructor_dashboard_courses_overview_no_courses_hint') }}</div>

            <div class="d-flex align-items-center gap-8 mt-28 p-8 rounded-16 bg-white">
                <a href="/panel/courses/new" target="_blank" class="text-decoration-none w-100">
                    <div class="btn btn-xlg border-dashed border-gray-200 rounded-16 bg-white bg-hover-gray-100 w-100">
                        <x-iconsax-bul-play-add class="icons text-primary" width="24px" height="24px"/>
                        <span class="ml-8 text-dark">{{ trans('update.create_a_course') }}</span>
                    </div>
                </a>

                <a href="/classes" target="_blank" class="btn btn-xlg rounded-16 bg-transparent bg-hover-gray-100">
                    <span class="mr-8 text-dark">{{ trans('update.get_inspiration') }}</span>
                    <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="20px" height="20px"/>
                </a>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="">
                <h5 class="font-14 text-dark">{{ trans('update.have_a_question?') }}</h5>
                <div class="mt-2 font-12 text-gray-500">{{ trans('update.contact_platform_support_and_get_help') }}</div>
            </div>

            <a href="/contact" target="_blank" class="d-flex-center size-40 bg-white border-gray-200 rounded-circle bg-hover-gray-100">
                <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
            </a>
        </div>

    @endif

</div>
