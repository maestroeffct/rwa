<div class="d-grid grid-columns-2 grid-columns-lg-3 gap-24 mt-16">
    {{-- Students --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-teacher class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ count($course->sales) }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('public.students') }}</span>
        </div>
    </div>
    {{-- Sales --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-money-3 class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ handlePrice($course->sales->sum('amount')) }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('panel.sales') }}</span>
        </div>
    </div>
    {{-- Views --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-eye class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ shortNumbers($course->visits()->count()) }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.views') }}</span>
        </div>
    </div>
    {{-- Hrs. Activity --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-video-circle class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ convertMinutesToHourAndMinute($course->getTimeSpentOnCourse('min')) }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.hrs_activity') }}</span>
        </div>
    </div>
    {{-- Av. Learning --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-trend-up class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ $course->getAverageLearning() }}%</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.avg_learning') }}</span>
        </div>
    </div>
    {{-- Lessons --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-note-2 class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ $course->getAllLessonsCount() }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.lessons') }}</span>
        </div>
    </div>
</div>
