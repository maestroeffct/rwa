<div class="mb-12">
    <img src="/assets/design_1/img/courses/learning_page/certificate.svg" alt="" class="">
</div>

@if(!empty($quizResult))
    <h3 class="font-16 text-dark">{{ trans('update.download_certificate') }}</h3>
    <div class="mt-8 text-gray-500">{!! nl2br(trans('update.learning_page_download_quiz_certificate_hint')) !!}</div>
@else
    <h3 class="font-16 text-dark">{{ trans('update.quiz_certificate') }}</h3>
    <div class="mt-8 text-gray-500">{!! nl2br(trans('update.learning_page_not_achieve_quiz_certificate_hint')) !!}</div>
@endif

<div class="learning-page-quiz-overview-center-line bg-gray-400 mt-8"></div>

@if(!empty($quiz))
    @if(!empty($quiz->icon))
        <div class="d-flex-center size-40 mt-8">
            <img src="{{ $quiz->icon }}" alt="" class="img-cover">
        </div>
    @else
        <div class="d-flex-center size-40 mt-8 rounded-circle bg-primary">
            <x-iconsax-bul-clipboard-tick class="icons text-white" width="24px" height="24px"/>
        </div>
    @endif

    <h4 class="mt-8 font-12 text-dark">{{ $quiz->title }}</h4>
@endif

@if(!empty($quizResult))
    <a href="/panel/quizzes/results/{{ $quizResult->id }}/showCertificate" target="_blank" class="btn btn-primary btn-lg mt-24">
        <span class="">{{ trans('home.download') }}</span>
    </a>
@endif
