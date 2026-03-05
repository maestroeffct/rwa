<div id="learningPageSidebar" class="learning-page__sidebar">
    <div class="learning-page__sidebar-header px-16 border-bottom-gray-200">
        {{-- Course Tools --}}
        <div class="d-block d-lg-none">
            @include('design_1.web.courses.learning_page.includes.top_header.course_tools')
        </div>


        <div class="js-toggle-show-learning-page-sidebar-drawer cursor-pointer">
            <x-iconsax-lin-add class="icons close-icon text-gray-500" width="28px" height="28px"/>
        </div>
    </div>

    <div class="learning-page__sidebar-content py-12" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>

        <div class="px-12">
            {{-- User & Progress --}}
            <div class="card-with-mask position-relative mb-24">
                <div class="mask-8-white bg-primary-20"></div>
                <div class="position-relative z-index-2 bg-primary p-8 pb-12 rounded-16">
                    <div class="d-flex align-items-center justify-content-between bg-white rounded-12">
                        <div class="d-flex align-items-center p-12">
                            <div class="size-40 rounded-circle">
                                <img src="{{ $user->getAvatar(40) }}" alt="{{ $user->full_name }}" class="img-cover rounded-circle">
                            </div>
                            <div class="ml-8">
                                <h4 class="font-14 text-dark">{{ $user->full_name }}</h4>
                                <p class="mt-2 font-12 text-gray-500">{{ $user->role->caption }}</p>
                            </div>
                        </div>

                        <div class="d-flex-center size-40 p-6">
                            <x-iconsax-bul-teacher class="icons sidebar-teacher-icon" width="40px" height="40px"/>
                        </div>
                    </div>

                    @php
                        $percent = $course->getProgress(true);
                    @endphp

                    <div class="learning-progress d-flex align-items-center mt-12 rounded-4 bg-gray-100 p-2">
                        <div class="js-course-learning-progress-bar-percent learning-progress__bar rounded-4 bg-primary" style="width: {{ $percent }}%"></div>
                    </div>
                    <div class="d-flex align-items-center gap-4 mt-4 font-12 text-white opacity-75">
                        <span class="js-course-learning-progress-percent">{{ $percent }}%</span>
                        <span class="">{{ trans('update.study_progress') }}</span>
                    </div>

                </div>
            </div>

            {{-- Course Expire Alert --}}
            @if(!empty($course->access_days) and !empty($saleItem))
                @php
                    $courseExpired = $course->getExpiredAccessDays($saleItem->created_at, $saleItem->gift_id)
                @endphp

                <div class="d-flex align-items-center bg-warning-10 border-warning rounded-12 p-12 mt-16">
                    <x-iconsax-bul-danger class="icons text-warning" width="24px" height="24px"/>
                    <span class="font-12 text-warning ml-4">{!! trans('update.course_expires_on_date', ['date' => dateTimeFormat($courseExpired, 'j M Y')]) !!}</span>
                </div>
            @endif

            {{-- Course Start Alert --}}
            @if(!empty($course->start_date))
                <div class="d-flex align-items-center bg-gray-100 border-gray-300 rounded-12 p-12 mt-16">
                    <x-iconsax-bul-clock-1 class="icons text-gray-500" width="24px" height="24px"/>
                    <span class="font-12 text-gray-500 ml-4">{!! trans('update.course_will_be_started_on_date', ['date' => dateTimeFormat($course->start_date, 'j M Y')]) !!}</span>
                </div>
            @endif
        </div>

        {{-- Tabs --}}
        <div class="mt-16 pt-12 px-12 border-top-gray-100">

            <div class="custom-tabs">
                <div class="position-relative sidebar-tabs d-flex align-items-center justify-content-between gap-4 p-8 bg-gray-100 rounded-30">
                    <div class="navbar-item d-flex-center cursor-pointer py-8 px-24 rounded-20 active" data-tab-toggle data-tab-href="#contentTab">
                        <span class="">{{ trans('update.content') }}</span>
                    </div>

                    <div class="navbar-item d-flex-center cursor-pointer py-8 px-24 rounded-20" data-tab-toggle data-tab-href="#quizzesTab">
                        <span class="">{{ trans('quiz.quizzes') }}</span>
                    </div>

                    <div class="navbar-item d-flex-center cursor-pointer py-8 px-24 rounded-20" data-tab-toggle data-tab-href="#certificatesTab">
                        <span class="">{{ trans('panel.certificates') }}</span>
                    </div>
                </div>

                <div class="custom-tabs-body mt-12">
                    <div class="custom-tabs-content active" id="contentTab">
                        @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents')
                    </div>

                    <div class="custom-tabs-content" id="quizzesTab">
                        @include('design_1.web.courses.learning_page.includes.sidebar.tabs.quizzes')
                    </div>

                    <div class="custom-tabs-content" id="certificatesTab">
                        @include('design_1.web.courses.learning_page.includes.sidebar.tabs.certificates')
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
