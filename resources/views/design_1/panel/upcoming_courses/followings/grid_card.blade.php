
<div class="upcoming-panel-course-grid-card position-relative">

    <a href="/panel/upcoming_courses/followings/{{ $upcomingCourse->id }}/delete" class="delete-action is-live-course-icon d-flex-center size-64 rounded-circle position-absolute" style="top: 32%; left: 50%; transform: translate(-50%, -50%); z-index: 10;">
        <x-iconsax-bol-heart class="icons text-danger" width="24px" height="24px"/>
    </a>


    <a href="{{ $upcomingCourse->getUrl() }}" target="_blank" class="text-decoration-none">
    <div class="upcoming-panel-course-grid-card__image position-relative rounded-16 bg-gray-100">
        <img src="{{ $upcomingCourse->getImage() }}" alt="" class="img-cover rounded-16">

        <div class="bottom-content px-12">
            @if($upcomingCourse->status == \App\Models\UpcomingCourse::$pending)
                <div class="d-inline-flex-center mb-8 px-8 py-4 border-warning bg-warning-20 rounded-16">
                    <x-iconsax-lin-calendar-2 class="icons text-warning" width="16px" height="16px"/>
                    <span class="font-12 text-warning ml-4">{{ dateTimeFormat($upcomingCourse->publish_date, 'j M Y H:i') }}</span>
                </div>
            @elseif($upcomingCourse->status == \App\Models\UpcomingCourse::$active)
                <div class="d-inline-flex-center mb-8 px-8 py-4 border-success bg-success-20 rounded-16">
                    <x-iconsax-lin-calendar-2 class="icons text-success" width="16px" height="16px"/>
                    <span class="font-12 text-success ml-4">{{ dateTimeFormat($upcomingCourse->publish_date, 'j M Y H:i') }}</span>
                </div>
            @endif

                <h3 class="upcoming-panel-course-grid-card__title font-14 font-weight-bold text-white">{{ $upcomingCourse->title }}</h3>
        </div>
    </div>

    <div class="upcoming-panel-course-grid-card__body position-relative px-12 pb-12">
        <div class="upcoming-panel-course-grid-card__content d-flex flex-column bg-white p-12 rounded-16">
            <div class="d-grid grid-columns-2 gap-16 p-16 rounded-8 border-gray-200 mb-16">
                <div class="d-flex align-items-center font-12 text-gray-500">
                    <x-iconsax-lin-category class="icons text-gray-400" width="20px" height="20px"/>
                    <span class="ml-4 font-weight-bold">{{ $upcomingCourse->sections }}</span>
                    <span class="ml-4">{{ trans('update.sections') }}</span>
                </div>

                <div class="d-flex align-items-center font-12 text-gray-500">
                    <x-iconsax-lin-note-2 class="icons text-gray-400" width="20px" height="20px"/>
                    <span class="ml-4 font-weight-bold">{{ $upcomingCourse->parts }}</span>
                    <span class="ml-4">{{ trans('public.parts') }}</span>
                </div>

                <div class="d-flex align-items-center font-12 text-gray-500">
                    <x-iconsax-lin-teacher class="icons text-gray-400" width="20px" height="20px"/>
                    <span class="ml-4 font-weight-bold">{{ $upcomingCourse->capacity }}</span>
                    <span class="ml-4">{{ trans('public.capacity') }}</span>
                </div>

                <div class="d-flex align-items-center font-12 text-gray-500">
                    <x-iconsax-lin-clock-1 class="icons text-gray-400" width="20px" height="20px"/>
                    <span class="ml-4 font-weight-bold">{{ convertMinutesToHourAndMinute($upcomingCourse->duration ?? 0) }}</span>
                    <span class="ml-4">{{ trans('home.hours') }}</span>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-auto">
                {{-- Chart --}}
                @include("design_1.panel.upcoming_courses.my_courses.grid_card.grid_card_progress_chart")

                <div class="d-flex align-items-center font-16 font-weight-bold text-success">
                    @if($upcomingCourse->price > 0)
                        <span class="">{{ handlePrice($upcomingCourse->price, true, true, false, null, true) }}</span>
                    @else
                        <span class="">{{ trans('public.free') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </a>
</div>
