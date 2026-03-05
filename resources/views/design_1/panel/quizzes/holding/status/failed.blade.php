<div class="d-flex-center flex-column text-center mt-24">
    <div class="">
        <img src="/assets/design_1/img/panel/quiz/status/failed.svg" alt="{{ trans('quiz.failed') }}" class="img-fluid" width="294.63px" height="280px">
    </div>

    <h1 class="font-24 font-weight-bold mt-16">{{ trans('update.quiz_passed_failed!') }}</h1>
    <p class="text-gray-500 mt-8">{{ trans('update.quiz_passed_failed_hint') }}</p>

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
        @if($canTryAgain)
            <a href="/panel/quizzes/{{ $quiz->id }}/overview" class="btn btn-primary btn-lg">{{ trans('public.try_again') }}</a>
        @else
            <a href="/panel/quizzes/results/{{ $quizResult->id }}/details" class="btn btn-primary btn-lg">{{ trans('update.view_answers') }}</a>
        @endif

        <a href="/panel/quizzes/my-results" class="btn btn-outline-primary btn-lg">{{ trans('update.my_results') }}</a>
    </div>

    @if($canTryAgain)
        <div class="d-flex align-items-center font-12 text-gray-500 mt-16">
            <x-iconsax-lin-refresh-2 class="icons" width="20px" height="20px"/>

            <span class="ml-4">
                @if($remainingTryAgain == "unlimited")
                    {{ trans('update.you_have_unlimited_chances_to_try_again_this_quiz') }}
                @else
                    {{ trans('update.you_have_n_other_chances_to_try_again_this_quiz', ['count' => $remainingTryAgain]) }}
                @endif
            </span>
        </div>
    @endif

</div>
