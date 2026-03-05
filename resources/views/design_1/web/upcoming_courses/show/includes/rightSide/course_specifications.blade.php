<div class="course-right-side-section position-relative mt-28">
    <div class="course-right-side-section__mask"></div>

    <div class="position-relative card-before-line bg-white rounded-24 p-16 z-index-2">
        <h4 class="font-14 font-weight-bold">{{ trans('update.course_specifications') }}</h4>

        @if(!empty($upcomingCourse->sections))
            <div class="d-flex align-items-center justify-content-between mt-16">
                <div class="d-flex align-items-center font-14 text-gray-500">
                    <x-iconsax-lin-category class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.sections') }}</span>
                </div>

                <span class="">{{ $upcomingCourse->sections }}</span>
            </div>
        @endif

        @if(!empty($upcomingCourse->parts))
            <div class="d-flex align-items-center justify-content-between mt-16">
                <div class="d-flex align-items-center font-14 text-gray-500">
                    <x-iconsax-lin-note-2 class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.lessons') }}</span>
                </div>

                <span class="">{{ $upcomingCourse->parts }}</span>
            </div>
        @endif

        @if(!empty($upcomingCourse->duration))
            <div class="d-flex align-items-center justify-content-between mt-16">
                <div class="d-flex align-items-center font-14 text-gray-500">
                    <x-iconsax-lin-clock class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('public.duration') }}</span>
                </div>

                <span class="">{{ convertMinutesToHourAndMinute($upcomingCourse->duration) }} {{ trans('home.hours') }}</span>
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center font-14 text-gray-500">
                <x-iconsax-lin-tag class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4">{{ trans('public.price') }}</span>
            </div>

            <span class="">{{ (!empty($upcomingCourse->price) and $upcomingCourse->price > 0) ? handlePrice($upcomingCourse->price) : trans('public.free') }}</span>
        </div>

        @if(!empty($upcomingCourse->capacity))
            <div class="d-flex align-items-center justify-content-between mt-16">
                <div class="d-flex align-items-center font-14 text-gray-500">
                    <x-iconsax-lin-clock class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('public.capacity') }}</span>
                </div>

                <span class="">{{ $upcomingCourse->capacity }}</span>
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center font-14 text-gray-500">
                <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4">{{ trans('update.created_date') }}</span>
            </div>

            <span class="">{{ dateTimeFormat($upcomingCourse->created_at, 'j M Y') }}</span>
        </div>

        @if(!empty($upcomingCourse->publish_date))
            <div class="d-flex align-items-center justify-content-between mt-16">
                <div class="d-flex align-items-center font-14 text-gray-500">
                    <x-iconsax-lin-calendar-tick class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.published_date') }}</span>
                </div>

                <span class="">{{ dateTimeFormat($upcomingCourse->publish_date, 'j M Y') }}</span>
            </div>
        @endif

        @if(!empty($upcomingCourse->course_progress))
            <div class="d-flex align-items-center justify-content-between mt-16">
                <div class="d-flex align-items-center font-14 text-gray-500">
                    <x-iconsax-lin-status-up class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.course_progress') }}</span>
                </div>

                <span class="">{{ $upcomingCourse->course_progress }}%</span>
            </div>

            <div class="upcoming-right-side-progress d-flex rounded-4 bg-gray-100 mt-12">
                <span class="h-100 rounded-4 {{ ($upcomingCourse->course_progress < 50) ? 'bg-warning' : 'bg-success' }} " style="width: {{ $upcomingCourse->course_progress }}%"></span>
            </div>
        @endif

    </div>
</div>
