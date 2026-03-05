<div class="mb-12">
    <img src="/assets/design_1/img/courses/learning_page/certificate.svg" alt="" class="">
</div>

@if(!empty($courseCertificate))
    <h3 class="font-16 text-dark">{{ trans('update.download_certificate') }}</h3>
    <div class="mt-8 text-gray-500">{!! nl2br(trans('update.learning_page_download_course_certificate_hint')) !!}</div>

    <div class="learning-page-quiz-overview-center-line bg-gray-400 mt-8"></div>

    <div class="d-flex-center size-40 mt-8 rounded-circle bg-primary-20">
        <x-iconsax-bul-teacher class="icons text-primary" width="24px" height="24px"/>
    </div>

    <h5 class="font-12 text-dark mt-8">{{ trans('update.course_completion_certificate') }}</h5>

    <a href="/panel/certificates/my-achievements/{{ $courseCertificate->id }}/show" target="_blank" class="btn btn-primary btn-lg mt-24">
        <span class="">{{ trans('home.download') }}</span>
    </a>
@else
    <h3 class="font-16 text-dark">{{ trans('update.course_certificate') }}</h3>
    <div class="mt-8 text-gray-500">{!! nl2br(trans('update.learning_page_not_achieve_course_certificate_hint')) !!}</div>
@endif
