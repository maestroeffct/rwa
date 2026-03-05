<a href="{{ $upcomingCourse->getUrl() }}" target="_blank" class="text-decoration-none d-block">
<div class="upcoming-course-card position-relative">
    <div class="upcoming-course-card__image position-relative rounded-16 bg-gray-100">
        <img src="{{ $upcomingCourse->getImage() }}" alt="" class="img-cover rounded-16">

        <div class="bottom-content px-12">
            @if($upcomingCourse->status == \App\Models\UpcomingCourse::$pending)
                    <div class="d-inline-flex align-items-center mb-8 px-8 py-4 border-warning bg-warning-20 rounded-16">
                    <x-iconsax-lin-calendar-2 class="icons text-warning" width="16px" height="16px"/>
                    <span class="font-14 text-warning ml-4">{{ dateTimeFormat($upcomingCourse->publish_date, 'j M Y H:i') }}</span>
                </div>
            @elseif($upcomingCourse->status == \App\Models\UpcomingCourse::$active)
                    <div class="d-inline-flex align-items-center mb-8 px-8 py-4 border-success bg-success-20 rounded-16">
                    <x-iconsax-lin-calendar-2 class="icons text-success" width="16px" height="16px"/>
                    <span class="font-14 text-success ml-4">{{ dateTimeFormat($upcomingCourse->publish_date, 'j M Y H:i') }}</span>
                </div>
            @endif

                <h3 class="upcoming-course-card__title font-16 font-weight-bold text-white">{{ $upcomingCourse->title }}</h3>
        </div>
    </div>

    <div class="upcoming-course-card__body position-relative px-12 pb-12">
        <div class="upcoming-course-card__content d-flex flex-column bg-white py-12 rounded-16">
                <div class="d-flex align-items-center mb-16 px-12" onclick="event.stopPropagation()">
                    <a href="{{ $upcomingCourse->teacher->getProfileUrl() }}" target="_blank" class="size-32 rounded-circle" onclick="event.stopPropagation()">
                    <img src="{{ $upcomingCourse->teacher->getAvatar(32) }}" class="img-cover rounded-circle" alt="{{ $upcomingCourse->teacher->full_name }}">
                    </a>

                <div class="d-flex flex-column ml-4">
                        <a href="{{ $upcomingCourse->teacher->getProfileUrl() }}" target="_blank" class="font-14 font-weight-bold text-dark" onclick="event.stopPropagation()">{{ $upcomingCourse->teacher->full_name }}</a>

                    @if(!empty($upcomingCourse->category))
                        <div class="d-inline-flex align-items-center gap-4 mt-2 font-14 text-gray-500">
                            <span class="">{{ trans('public.in') }}</span>
                                <a href="{{ $upcomingCourse->category->getUrl() }}" target="_blank" class="font-14 text-gray-500 text-ellipsis" onclick="event.stopPropagation()">{{ $upcomingCourse->category->title }}</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-auto pt-12 px-12 border-top-gray-100">
                <div class="d-flex align-items-center font-16 font-weight-bold text-primary">
                        <a href="{{ $upcomingCourse->getUrl() }}" target="_blank" class="text-decoration-none text-primary">
                    @include("design_1.web.upcoming_courses.components.price")
                        </a>
                </div>

                <div class="d-flex align-items-center font-14 text-gray-500">
                        <a href="{{ $upcomingCourse->getUrl() }}" target="_blank" class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                    <x-iconsax-lin-clock-1 class="icons text-gray-500" width="16px" height="16px"/>
                    <span class="ml-4">{{ convertMinutesToHourAndMinute($upcomingCourse->duration) }}</span>
                    <span class="ml-4">{{ trans('home.hours') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>
