<div class="bg-white py-16 rounded-24 w-100 mt-24">
    <div class="px-16">
        <h4 class="font-14 font-weight-bold text-dark">{{ trans('update.learning_activity') }}</h4>
    </div>

    @if(!empty($learningActivity['haveLearningActivity']))

        @if(!empty($learningActivity['activityStats']))
            <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-16 mt-16 px-16">
                {{-- Daily Time --}}
                <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                    <div class="d-flex flex-column pt-8">
                        <span class="text-gray-500 font-12">{{ trans('update.daily_time') }}</span>
                        <span class="font-24 font-weight-bold mt-16 text-dark">{{ $learningActivity['activityStats']['today'] }}</span>
                    </div>

                    <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                        <x-iconsax-bul-timer class="icons text-primary" width="24px" height="24px"/>
                    </div>
                </div>

                {{-- Month Time --}}
                <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                    <div class="d-flex flex-column pt-8">
                        <span class="text-gray-500 font-12">{{ trans('update.month_time') }}</span>
                        <span class="font-24 font-weight-bold mt-16 text-dark">{{ $learningActivity['activityStats']['month'] }}</span>
                    </div>

                    <div class="d-flex-center size-48 rounded-12 bg-success-40">
                        <x-iconsax-bul-timer-1 class="icons text-success" width="24px" height="24px"/>
                    </div>
                </div>

                {{-- Total Time --}}
                <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                    <div class="d-flex flex-column pt-8">
                        <span class="text-gray-500 font-12">{{ trans('update.total_time') }}</span>
                        <span class="font-24 font-weight-bold mt-16 text-dark">{{ $learningActivity['activityStats']['year'] }}</span>
                    </div>

                    <div class="d-flex-center size-48 rounded-12 bg-warning-40">
                        <x-iconsax-bul-clock-1 class="icons text-warning" width="24px" height="24px"/>
                    </div>
                </div>
            </div>
        @endif

        {{-- Have Data & Chart --}}
        <div class="px-16 mt-24 pt-24 border-top-gray-100">

            @push('scripts_bottom')
                <script>
                    var learningActivityChartLabels = @json($learningActivity['learningActivityChart']['labels']);
                    var learningActivityChartData = @json($learningActivity['learningActivityChart']['data']);
                </script>
            @endpush

            {{-- Chart --}}
            <div id="learningActivityChart" class="student-dashboard__learning-activity-chart"></div>

            {{-- Continue Learning --}}
            @if(!empty($learningActivity['topActivityCourses']) and count($learningActivity['topActivityCourses']))
                <div class="mt-20">
                    <h5 class="font-14 text-dark">{{ trans('update.top_learnings') }}</h5>

                    <div class="d-grid grid-columns-2 gap-16 mt-16">
                        @foreach($learningActivity['topActivityCourses'] as $topActivityCourse)
                            <a href="{{ $topActivityCourse->getLearningPageUrl() }}" target="_blank" class="text-decoration-none">
                                <div class="d-flex align-items-center rounded-12 bg-gray-100 p-16">
                                    <div class="size-48 rounded-8 bg-gray-100">
                                        <img src="{{ $topActivityCourse->getIcon() }}" alt="" class="img-cover rounded-8">
                                    </div>
                                    <div class="ml-8">
                                        <span class="font-weight-bold text-dark">{{ truncate($topActivityCourse->title, 25) }}</span>
                                        <div class="font-12 text-gray-500 mt-4">{{ $topActivityCourse->total_time_spent }} {{ trans('public.minutes') }}</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    @else
        {{-- No Activity! --}}
        <div class="px-16">

            <div class="d-flex-center flex-column text-center p-60 rounded-16 border-dashed border-gray-200 bg-gray-100 mt-16">
                <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                    <x-iconsax-bul-video-play class="icons text-primary" width="24px" height="24px"/>
                </div>
                <h5 class="mt-12 font-14 text-dark">{{ trans('update.no_activity!') }}</h5>
                <div class="mt-4 font-12 text-gray-500">{{ trans('update.start_learning_today_by_watching_your_purchased_courses_enroll_on_new_courses') }}</div>

                <div class="d-flex align-items-center gap-8 mt-28 p-8 rounded-16 bg-white">
                    <a href="/courses/purchases" class="d-flex-center p-16 rounded-12 border-dashed border-gray-200 bg-white bg-hover-gray-100">
                        <x-iconsax-bul-video-play class="icons text-primary" width="24px" height="24px"/>
                        <span class="text-dark ml-8">{{ trans('update.my_courses') }}</span>
                    </a>

                    <a href="/classes" class="d-flex-center p-16 rounded-12 bg-white bg-hover-gray-100">
                        <span class="text-dark mr-8">{{ trans('update.explore_courses') }}</span>
                        <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
                    </a>
                </div>
            </div>

            {{-- Continue Learning --}}
            @if(!empty($learningActivity['continueLearningCourses']) and count($learningActivity['continueLearningCourses']))
                <div class="mt-20">
                    <h5 class="font-14 text-dark">{{ trans('update.continue_learning') }}</h5>

                    <div class="d-grid grid-columns-auto grid-lg-columns-2 gap-16 mt-16">
                        @foreach($learningActivity['continueLearningCourses'] as $learningActivityContinueLearningCourse)
                            <a href="{{ $learningActivityContinueLearningCourse->getLearningPageUrl() }}" target="_blank" class="text-decoration-none">
                                <div class="d-flex align-items-center rounded-12 bg-gray-100 p-16">
                                    <div class="size-48 rounded-8 bg-gray-100">
                                        <img src="{{ $learningActivityContinueLearningCourse->getIcon() }}" alt="" class="img-cover rounded-8">
                                    </div>
                                    <div class="ml-8">
                                        <span class="font-weight-bold text-dark">{{ $learningActivityContinueLearningCourse->title }}</span>

                                        <div class="progress-card d-flex bg-white mt-8">
                                            <div class="progress-bar bg-primary" style="width: {{ $learningActivityContinueLearningCourse->getProgress(true) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    @endif
</div>
