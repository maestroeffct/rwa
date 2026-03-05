<div class="bg-white py-16 rounded-16">
    <h3 class="font-14 font-weight-bold px-16">{{ trans('update.course_summary') }}</h3>

    <div class="row align-items-center mt-16 px-16 ">

        {{-- Students --}}
        <div class="col-6 col-lg-2 d-flex align-items-center">
            <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                <x-iconsax-lin-teacher class="icons text-primary" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h5 class="font-16 font-weight-bold">{{ $studentsCount }}</h5>
                <p class="text-gray-500 mt-8">{{ trans('public.students') }}</p>
            </div>
        </div>

        {{-- Chapters --}}
        <div class="col-6 col-lg-2 d-flex align-items-center">
            <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                <x-iconsax-lin-category class="icons text-primary" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h5 class="font-16 font-weight-bold">{{ $course->chapters->count() }}</h5>
                <p class="text-gray-500 mt-8">{{ trans('public.chapters') }}</p>
            </div>
        </div>

        {{-- Lessons --}}
        <div class="col-6 col-lg-2 d-flex align-items-center">
            <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                <x-iconsax-lin-note-2 class="icons text-primary" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h5 class="font-16 font-weight-bold">{{ $course->getAllLessonsCount() }}</h5>
                <p class="text-gray-500 mt-8">{{ trans('update.lessons') }}</p>
            </div>
        </div>

        {{-- Comments --}}
        <div class="col-6 col-lg-2 d-flex align-items-center">
            <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                <x-iconsax-lin-message class="icons text-primary" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h5 class="font-16 font-weight-bold">{{ $commentsCount }}</h5>
                <p class="text-gray-500 mt-8">{{ trans('panel.comments') }}</p>
            </div>
        </div>

        {{-- Pending Assignments --}}
        <div class="col-6 col-lg-2 d-flex align-items-center mt-16 mt-lg-0">
            <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                <x-iconsax-lin-clipboard-text class="icons text-primary" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h5 class="font-16 font-weight-bold">{{ $pendingAssignmentsCount }}</h5>
                <p class="text-gray-500 mt-8">{{ trans('update.pending_assignments') }}</p>
            </div>
        </div>

        {{-- Pending Quizzes --}}
        <div class="col-6 col-lg-2 d-flex align-items-center mt-16 mt-lg-0">
            <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                <x-iconsax-lin-clipboard-tick class="icons text-primary" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h5 class="font-16 font-weight-bold">{{ $pendingQuizzesCount }}</h5>
                <p class="text-gray-500 mt-8">{{ trans('update.pending_quizzes') }}</p>
            </div>
        </div>

    </div>

    {{-- Course Performance --}}
    <div class="mt-24 pt-16 px-16 border-top-gray-100">
        <h4 class="font-14 font-weight-bold">{{ trans('update.course_performance') }}</h4>

        <div class="row">
            {{-- Sales --}}
            <div class="col-6 col-lg-3 mt-16">
                <div class="bg-white p-16 rounded-16 border-gray-200">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8">{{ trans('panel.sales') }}</span>

                        <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                            <x-iconsax-bul-moneys class="icons text-primary" width="24px" height="24px"/>
                        </div>
                    </div>

                    <h5 class="font-24 mt-12 line-height-1">{{ (!empty($salesAmount) and $salesAmount > 0) ? handlePrice($salesAmount) : 0 }}</h5>
                </div>
            </div>

            {{-- Visits --}}
            <div class="col-6 col-lg-3 mt-16">
                <div class="bg-white p-16 rounded-16 border-gray-200">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8">{{ trans('update.visits') }}</span>

                        <div class="size-48 d-flex-center bg-accent-30 rounded-12">
                            <x-iconsax-bul-frame-2 class="icons text-accent" width="24px" height="24px"/>
                        </div>
                    </div>

                    <h5 class="font-24 mt-12 line-height-1">{{ $visitsCount }}</h5>
                </div>
            </div>

            {{-- Watch Time --}}
            <div class="col-6 col-lg-3 mt-16">
                <div class="bg-white p-16 rounded-16 border-gray-200">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8">{{ trans('update.watch_time') }}</span>

                        <div class="size-48 d-flex-center bg-success-30 rounded-12">
                            <x-iconsax-bul-clock-1 class="icons text-success" width="24px" height="24px"/>
                        </div>
                    </div>

                    <h5 class="font-24 mt-12 line-height-1">{{ ($courseWatchTimeMinutes > 0) ? convertMinutesToHourAndMinute($courseWatchTimeMinutes) : 0 }} {{ trans('home.hours') }}</h5>
                </div>
            </div>

            {{-- Average Student Progress --}}
            <div class="col-6 col-lg-3 mt-16">
                <div class="bg-white p-16 rounded-16 border-gray-200">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8">{{ trans('update.average_student_progress') }}</span>

                        <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                            <x-iconsax-bul-activity class="icons text-warning" width="24px" height="24px"/>
                        </div>
                    </div>

                    <h5 class="font-24 mt-12 line-height-1">{{ $course->getAverageLearning() }}%</h5>
                </div>
            </div>

        </div>
    </div>
</div>
