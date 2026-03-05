<div class="instructor-dashboard__hello-box position-relative mt-54  ">
    <div class="instructor-dashboard__hello-box-bg rounded-24 {{ $authUser->isOrganization() ? 'organ-hello-box' : '' }}"></div>

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
                    @if($authUser->isOrganization())
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                                    <x-iconsax-bul-document class="icons text-white" width="24px" height="24px"/>
                                </div>
                                <div class="ml-8">
                                    <span class="d-block font-weight-bold text-white">{{ $helloBox['instructorsCount'] }}</span>
                                    <span class="d-block mt-4 text-white opacity-75 text-ellipsis">{{ trans('update.instructors') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                                    <x-iconsax-bul-box-1 class="icons text-white" width="24px" height="24px"/>
                                </div>
                                <div class="ml-8">
                                    <span class="d-block font-weight-bold text-white">{{ $helloBox['studentsCount'] }}</span>
                                    <span class="d-block mt-4 text-white opacity-75 text-ellipsis">{{ trans('update.students') }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                                    <x-iconsax-bul-document class="icons text-white" width="24px" height="24px"/>
                                </div>
                                <div class="ml-8">
                                    <span class="d-block font-weight-bold text-white">{{ $helloBox['productsCount'] }}</span>
                                    <span class="d-block mt-4 text-white opacity-75 text-ellipsis">{{ trans('update.products') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                                    <x-iconsax-bul-box-1 class="icons text-white" width="24px" height="24px"/>
                                </div>
                                <div class="ml-8">
                                    <span class="d-block font-weight-bold text-white">{{ $helloBox['bundlesCount'] }}</span>
                                    <span class="d-block mt-4 text-white opacity-75 text-ellipsis">{{ trans('update.bundles') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-lg-6 position-relative d-none d-lg-block">
                <div class="hello-box-user-vector d-flex justify-content-end">
                    <img src="/assets/design_1/img/panel/dashboard/{{ $authUser->isOrganization() ? 'organ' : 'instructor' }}/hello-box-user-vector.png" alt="" class="img-fluid">
                </div>
            </div>
        </div>

        @if(!empty($helloBox['coursesCount']) and !empty($helloBox['manageCourses']) and count($helloBox['manageCourses']))
            <div class="mt-52">
                <h4 class="font-14 font-weight-bold text-white">{{ trans('update.manage_courses') }}</h4>

                <div class="row mt-12">
                    @foreach($helloBox['manageCourses'] as $manageCourse)
                        <div class="instructor-dashboard__hello-box-course-col col-12 col-lg-6 mt-12">
                            <a href="{{ $manageCourse->getUrl() }}" target="_blank" class="d-block">
                                <div class="card-with-mask position-relative">
                                    <div class="mask-8-white"></div>
                                    <div class="position-relative z-index-2 bg-white py-16 rounded-16">
                                        <div class="d-flex align-items-center px-16 mb-16">
                                            <div class="size-40 bg-gray-100 rounded-8">
                                                <img src="{{ $manageCourse->getIcon() }}" alt="" class="img-cover rounded-8">
                                            </div>
                                            <div class="ml-8">
                                                <h6 class="font-12 text-dark text-ellipsis">{{ truncate($manageCourse->title, 30) }}</h6>
                                                <div class="font-12 mt-4 text-gray-500">{{ !empty($manageCourse->category) ? $manageCourse->category->title : trans('update.no_category') }}</div>
                                            </div>
                                        </div>

                                        @php
                                            $manageCourseProgress = $manageCourse->getAverageLearning();
                                        @endphp

                                        <div class="mb-12 px-16">
                                            <div class="progress-card d-flex bg-gray-100">
                                                <div class="progress-bar bg-primary" style="width: {{ $manageCourseProgress }}%"></div>
                                            </div>

                                            <div class="d-flex align-items-center gap-4 mt-8 font-12">
                                                <span class="font-weight-bold text-dark">{{ $manageCourseProgress }}%</span>
                                                <span class="text-gray-500">{{ trans('update.average_learning') }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between px-16 pt-16 border-top-gray-200">
                                            <div class="d-flex align-items-center gap-4 font-12">
                                                <x-iconsax-bul-video-play class="icons text-gray-500" width="20px" height="20px"/>
                                                <span class="font-weight-bold text-dark">{{ count($manageCourse->getStudentsIds()) }}</span>
                                                <span class="text-gray-500">{{ trans('public.students') }}</span>
                                            </div>

                                            <div class="d-flex align-items-center text-gray-500 gap-4">
                                                <span class="font-12 font-weight-bold">{{ trans('update.view_details') }}</span>
                                                <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            {{-- If Empty --}}
            <div class="row mt-108">
                @if($authUser->isOrganization())
                    <div class="col-12 col-lg-6">
                        <a href="/panel/manage/instructors/new">
                            <div class="card-with-mask position-relative">
                                <div class="mask-8-white"></div>

                                <div class="position-relative z-index-2 d-flex align-items-center p-24 rounded-16 bg-white">
                                    <div class="size-28">
                                        <x-iconsax-bul-briefcase class="icons text-primary" width="28px" height="28px"/>
                                    </div>
                                    <div class="ml-8">
                                        <h6 class="font-14 text-dark">{{ trans('update.create_an_instructor') }}</h6>
                                        <p class="mt-8 font-12 text-gray-500">{{ trans('update.and_let_them_create_courses') }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-lg-6">
                        <a href="/panel/manage/students/new">
                            <div class="card-with-mask position-relative">
                                <div class="mask-8-white"></div>

                                <div class="position-relative z-index-2 d-flex align-items-center p-24 rounded-16 bg-white">
                                    <div class="size-28">
                                        <x-iconsax-bul-teacher class="icons text-primary" width="28px" height="28px"/>
                                    </div>
                                    <div class="ml-8">
                                        <h6 class="font-14 text-dark">{{ trans('update.create_an_student') }}</h6>
                                        <p class="mt-8 font-12 text-gray-500">{{ trans('update.and_let_them_enjoy_learning') }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @else
                    <div class="col-12 col-lg-6">
                        <div class="card-with-mask position-relative">
                            <div class="mask-8-white"></div>

                            <div class="position-relative z-index-2 d-flex align-items-center p-16 rounded-16 bg-white">
                                <div class="">
                                    <h6 class="font-14 text-dark">{{ trans('update.create_your_first_course') }}</h6>
                                    <p class="mt-8 font-12 text-gray-500">{{ trans('update.start_making_money_today') }}</p>
                                </div>

                                <div class="instructor-dashboard__hello-box-money-image">
                                    <img src="/assets/design_1/img/panel/dashboard/instructor/money.png" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
