<div class="course-row-card-1 position-relative pl-lg-16">
    <div class="d-flex flex-column flex-lg-row bg-white rounded-24">
        {{-- Image --}}
        <div class="py-0 py-lg-16">
            <a href="{{ $course->getUrl() }}">
                <div class="course-row-card-1__image position-relative bg-gray-200">
                   @if($course->bestTicket() && $course->bestTicket(true)['percent'] > 0)
                    <div class="position-absolute z-index-1 bg-accent d-flex align-items-center justify-content-center py-4 px-8 mt-12 ml-12 rounded-pill">
                        <x-iconsax-bul-discount-shape class="icons text-white" width="20px" height="20px"/>
                        <span class="ml-4 text-white font-14">{{ $course->bestTicket(true)['percent'] }}% {{ trans('public.off') }}</span>
                    </div>
                @endif
                    <img src="{{ $course->getImage() }}" class="img-cover" alt="{{ $course->title }}">
                </div>
            </a>
        </div>

        <div class="p-16 d-flex flex-column flex-1">
            <a href="{{ $course->getUrl() }}" class="mb-8">
                <h3 class="course-title font-16 font-weight-bold text-dark">{{ clean($course->title,'title') }}</h3>
            </a>

            @include('design_1.web.components.rate', ['rate' => $course->getRate(), 'rateClassName' => 'mb-12'])

            @if(!empty($course->summary))
                <div class="mb-12 font-14 text-gray-500">{!! nl2br($course->summary) !!}</div>
            @endif

            <div class="mt-auto">
                <div class="d-flex align-items-center">
                    <div class="size-32 rounded-circle">
                        <img src="{{ $course->teacher->getAvatar(32) }}" class="img-cover rounded-circle" alt="{{ $course->teacher->full_name }}">
                    </div>

                    <div class="ml-4">
                        <a href="{{ $course->teacher->getProfileUrl() }}" target="_blank" class="font-14 font-weight-bold text-dark">{{ $course->teacher->full_name }}</a>
                        <p class="mt-2 font-14 text-gray-500 text-ellipsis">{{ $course->teacher->bio }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-24 mt-24">
                    <div class="d-flex align-items-center font-14 text-gray-500">
                        <x-iconsax-lin-clock-1 class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4">{{ convertMinutesToHourAndMinute($course->duration) }}</span>
                        <span class="ml-4">{{ trans('home.hours') }}</span>
                    </div>

                    <div class="d-flex align-items-center font-14 text-gray-500">
                        <x-iconsax-lin-note-2 class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4">{{ $course->getAllLessonsCount() }}</span>
                        <span class="ml-4">{{ trans('update.lessons') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between flex-lg-column px-16 pb-16 pt-lg-40 pb-lg-12 px-lg-12 border-left-gray-200">
            <div class="d-flex-center flex-column font-24 font-weight-bold text-primary">
                @if(!empty($showCoursePoints))
                    <span>{{ trans('update.n_points', ['count' => $course->points]) }}</span>
                @else
                    @include("design_1.web.courses.components.price_vertical", ['courseRow' => $course])
                @endif
            </div>

            <div class="d-flex align-items-center gap-12 mt-auto">
                @if(!isFreeModeEnabled())
                    <a href="{{ $course->getUrl() }}" class="course-row-card-1__add-to-cart-btn btn btn-primary btn-lg rounded-12">{{ trans('public.add_to_cart') }}</a>
                @endif

                <a @if(auth()->guest()) href="/login" @else href="/favorites/{{ $course->slug }}/toggle" id="favoriteToggle" @endif class="d-flex-center size-48 rounded-12 border-gray-200 {{ ($course->isFavoriteAuthUser()) ? 'text-danger' : 'text-gray-500' }}">
                    <x-iconsax-bol-heart class="icons " width="24px" height="24px"/>
                </a>
            </div>
        </div>
    </div>
</div>
