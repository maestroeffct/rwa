<div class="d-flex-center flex-column text-center mt-24">
    <div class="">
        <img src="/assets/design_1/img/panel/quiz/status/pending.svg" alt="{{ trans('quiz.waiting') }}" class="img-fluid" width="294.63px" height="280px">
    </div>

    <h1 class="font-24 font-weight-bold mt-16">{{ trans('update.pending_quiz') }}</h1>
    <p class="text-gray-500 mt-8">{{ trans('update.pending_quiz_hint') }}</p>

    <div class="d-flex-center size-48 mt-36">
        @if(!empty($quiz->icon))
            <img src="{{ $quiz->icon }}" class="img-cover rounded-12">
        @else
            <x-iconsax-bul-clipboard-tick class="icons text-success" width="48px" height="48px"/>
        @endif
    </div>

    <div class="mt-8 font-12 font-weight-bold text-gray-500">{{ $quiz->title }}</div>
    <div class="mt-4 font-12 text-gray-500">{{ trans('public.by') }} {{ $quiz->creator->full_name }}</div>

    <div class="d-flex align-items-center flex-wrap gap-16 mt-16">
        @if(!empty($webinar))
            <a href="{{ $webinar->getLearningPageUrl() }}" class="btn btn-primary btn-lg">{{ trans('update.back_to_learning_page') }}</a>
        @endif

        <a href="/panel/quizzes/my-results" class="btn btn-outline-primary btn-lg">{{ trans('update.my_results') }}</a>
    </div>
</div>
