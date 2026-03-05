<div class="course-bottom-fixed-card bg-white">
    <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between h-100">
        <div class="d-flex align-items-center mb-16 mb-lg-0">
            <div class="course-bottom-fixed-card__course-img rounded-8">
                <img src="{{ $upcomingCourse->getImage() }}" class="img-cover rounded-8" alt="{{ $upcomingCourse->title }}">
            </div>
            <div class="ml-8">
                <div class="font-12 text-gray-500">{{ trans('update.you_are_viewing') }}</div>
                <div class="mt-4 font-14 font-weight-bold">{{ $upcomingCourse->title }}</div>
            </div>
        </div>

        @if(!empty($upcomingCourse->webinar))
            <a href="{{ $upcomingCourse->webinar->getUrl() }}" target="_blank" class="btn btn-lg btn-primary">{{ trans('update.view_published_course') }}</a>
        @else
            @if(!empty($authUser))
                <button type="button" class="js-follow-upcoming-course btn btn-lg btn-primary" data-path="/upcoming_courses/{{ $upcomingCourse->slug }}/toggleFollow">
                    {{ $followed ? trans('update.unfollow_course') : trans('update.follow_course') }}
                </button>
            @else
                <a href="/login" class="btn btn-lg btn-primary">{{ trans('update.follow_course') }}</a>
            @endif
        @endif

    </div>
</div>

<div class="course-bottom-fixed-card__progress">
    <div class="progress-line"></div>
</div>
