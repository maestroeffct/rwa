<div class="course-bottom-fixed-card bg-white">
    <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between h-100">
        <div class="d-flex align-items-center mb-16 mb-lg-0">
            <div class="course-bottom-fixed-card__course-img rounded-8">
                <img src="{{ $course->getImage() }}" class="img-cover rounded-8" alt="{{ $course->title }}">
            </div>
            <div class="ml-8">
                <div class="font-12 text-gray-500">{{ trans('update.you_are_viewing') }}</div>
                <div class="mt-4 font-14 font-weight-bold">{{ $course->title }}</div>
            </div>
        </div>

        @if($hasBought or !empty($course->getInstallmentOrder()))
            <a href="{{ $course->getLearningPageUrl() }}" class="btn btn-primary btn-lg">{{ trans('update.go_to_learning_page') }}</a>
        @else
            <button type="button" class="js-bottom-fixed-enroll-on-course-btn btn btn-primary btn-lg">{{ trans('update.enroll_on_course') }}</button>
        @endif
    </div>
</div>

<div class="course-bottom-fixed-card__progress">
    <div class="progress-line"></div>
</div>
