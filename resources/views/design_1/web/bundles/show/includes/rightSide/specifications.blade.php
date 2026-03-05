<div class="course-right-side-section position-relative mt-28">
    <div class="course-right-side-section__mask"></div>

    <div class="position-relative card-before-line bg-white rounded-24 p-16 z-index-2">
        <h4 class="font-14 font-weight-bold">{{ trans('update.bundle_specifications') }}</h4>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center font-14 text-gray-500">
                <x-iconsax-lin-category class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4">{{ trans('update.courses') }}</span>
            </div>

            <span class="">{{ $bundle->bundleWebinars->count() }}</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center font-14 text-gray-500">
                <x-iconsax-lin-category class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4">{{ trans('update.sections') }}</span>
            </div>

            <span class="">{{ $bundle->getAllChaptersCount() }}</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center font-14 text-gray-500">
                <x-iconsax-lin-note-2 class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4">{{ trans('update.lessons') }}</span>
            </div>

            <span class="">{{ $bundle->getAllLessonsCount() }}</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center font-14 text-gray-500">
                <x-iconsax-lin-clock class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4">{{ trans('public.duration') }}</span>
            </div>

            <span class="">{{ convertMinutesToHourAndMinute($bundle->getBundleDuration()) }} {{ trans('home.hours') }}</span>
        </div>

        @if(!empty($bundle->access_days))
            <div class="d-flex align-items-center justify-content-between mt-16">
                <div class="d-flex align-items-center font-14 text-gray-500">
                    <x-iconsax-lin-security-time class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.access_duration') }}</span>
                </div>

                <span class="">{{ trans('update.n_day', ['day' => $bundle->access_days]) }}</span>
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center font-14 text-gray-500">
                <x-iconsax-lin-teacher class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4">{{ trans('public.students') }}</span>
            </div>

            <span class="">{{ $bundle->sales_count }}</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center font-14 text-gray-500">
                <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4">{{ trans('update.created_date') }}</span>
            </div>

            <span class="">{{ dateTimeFormat($bundle->created_at, 'j M Y') }}</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center font-14 text-gray-500">
                <x-iconsax-lin-calendar-tick class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4">{{ trans('update.updated_date') }}</span>
            </div>

            <span class="">{{ dateTimeFormat($bundle->updated_at, 'j M Y') }}</span>
        </div>

    </div>
</div>
