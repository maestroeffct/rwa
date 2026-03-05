<a href="{{ $course->getUrl() }}" class="text-decoration-none d-block">
    <div class="course-grid-card-1 position-relative">
        <div class="course-grid-card-1__mask"></div>

        <div class="position-relative z-index-2">
            <div class="course-grid-card-1__image bg-gray-200">
                @if($course->bestTicket() && $course->bestTicket(true)['percent'] > 0)
                    <div class="position-absolute z-index-1 bg-accent d-flex align-items-center justify-content-center py-4 px-8 mt-12 ml-12 rounded-pill">
                        <x-iconsax-bul-discount-shape class="icons text-white" width="20px" height="20px"/>
                        <span class="ml-4 text-white font-12">{{ $course->bestTicket(true)['percent'] }}% {{ trans('public.off') }}</span>
                    </div>
                @endif
                <img src="{{ $course->getImage() }}" class="img-cover" alt="{{ $course->title }}">
            </div>

            <div class="course-grid-card-1__body d-flex flex-column py-12">
                <div class="d-flex flex-column px-12 w-100 h-100 mb-16">
                    <h3 class="course-title font-16 font-weight-bold text-dark">{{ clean($course->title,'title') }}</h3>

                    @include('design_1.web.components.rate', ['rate' => $course->getRate(), 'rateCount' => $course->getRateCount(), 'rateClassName' => 'mt-12'])

                    <div class="d-flex align-items-center mt-auto">
                        <div class="size-32 rounded-circle">
                            <img src="{{ $course->teacher->getAvatar(32) }}" class="img-cover rounded-circle" alt="{{ $course->teacher->full_name }}">
                        </div>

                        <div class="d-flex flex-column ml-4">
                            <div class="font-14 font-weight-bold text-dark">{{ $course->teacher->full_name }}</div>

                            @if(!empty($course->category))
                                <div class="d-inline-flex align-items-center gap-4 mt-2 font-12 text-gray-500">
                                    <span class="">{{ trans('public.in') }}</span>
                                    <div class="font-14 text-gray-500 text-ellipsis">{{ $course->category->title }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mt-auto pt-12 border-top-gray-100 px-12">
                    <div class="d-flex align-items-center font-16 font-weight-bold text-primary">
                        @if(!empty($showCoursePoints))
                            <span>{{ trans('update.n_points', ['count' => $course->points]) }}</span>
                        @else
                            @include("design_1.web.courses.components.price_horizontal", ['courseRow' => $course])
                        @endif
                    </div>

                    <div class="d-flex align-items-center font-14 text-gray-500">
                        <x-iconsax-lin-clock-1 class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4">{{ convertMinutesToHourAndMinute($course->duration) }}</span>
                        <span class="ml-4">{{ trans('home.hours') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>
