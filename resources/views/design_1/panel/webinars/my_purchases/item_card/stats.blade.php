<div class="d-grid grid-columns-2 grid-lg-columns-3 gap-24 mt-16">
    {{-- Type --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-teacher class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark"> {{ (!empty($sale->bundle)) ? trans('update.bundle') : trans('webinars.' . $sale->webinar->type) }} @if(!empty($sale->gift_id))
                    ({{ trans('update.gift') }})
                @endif</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('public.type') }}</span>
        </div>
    </div>
    {{-- Hrs. Activity --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-video-circle class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ convertMinutesToHourAndMinute($saleItem->getTimeSpentOnCourse('min')) }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.hrs_activity') }}</span>
        </div>
    </div>
    {{-- Enroll Date --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-money-3 class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ dateTimeFormat($sale->created_at, 'j M Y') }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.enroll_date') }}</span>
        </div>
    </div>
    {{-- Duration --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-clock-1 class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ !empty($sale->bundle) ? convertMinutesToHourAndMinute($saleItem->getBundleDuration()) : convertMinutesToHourAndMinute($saleItem->duration) }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('public.duration') }}</span>
        </div>
    </div>

    @if(!empty($sale->bundle))
        {{-- Courses --}}
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-36 bg-white rounded-circle">
                <x-iconsax-lin-trend-up class="icons text-gray-400" width="20px" height="20px"/>
            </div>
            <div class="ml-8 font-12">
                <span class="d-block font-weight-bold text-dark">{{ $saleItem->bundleWebinars()->count() }}</span>
                <span class="d-block mt-2 text-gray-500">{{ trans('update.courses') }}</span>
            </div>
        </div>
    @else
        {{-- Assignments --}}
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-36 bg-white rounded-circle">
                <x-iconsax-lin-trend-up class="icons text-gray-400" width="20px" height="20px"/>
            </div>
            <div class="ml-8 font-12">
                <span class="d-block font-weight-bold text-dark">{{ $saleItem->getAllAssignmentsCount() }}</span>
                <span class="d-block mt-2 text-gray-500">{{ trans('update.assignments') }}</span>
            </div>
        </div>
    @endif

    {{-- Lessons --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-note-2 class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ $saleItem->getAllLessonsCount() }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.lessons') }}</span>
        </div>
    </div>
</div>
