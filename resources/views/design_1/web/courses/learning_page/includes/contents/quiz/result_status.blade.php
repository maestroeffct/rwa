<div class="">
    <img src="/assets/design_1/img/courses/learning_page/quiz_{{ $quiz->result->status }}.svg" alt="" class="img-fluid" width="285px" height="212px">
</div>

<h3 class="font-16 text-dark mt-12">
    {!! trans("update.learning_page_quiz_{$quiz->result->status}_title") !!}
</h3>

<div class="text-gray-500 mt-8">{!! nl2br(trans("update.learning_page_quiz_{$quiz->result->status}_hint")) !!}</div>

<div class="learning-page-quiz-overview-center-line mt-8 bg-gray-400"></div>

@if(!empty($quiz->icon))
    <div class="d-flex-center size-120 mt-8">
        <img src="{{ $quiz->icon }}" alt="" class="img-cover">
    </div>
@else
    <div class="d-flex-center size-120 mt-8 rounded-circle bg-primary">
        <x-iconsax-bul-clipboard-tick class="icons text-white" width="64px" height="64px"/>
    </div>
@endif

<h4 class="mt-8 font-12 text-dark">{{ $quiz->title }}</h4>


<div class="d-flex align-items-center flex-wrap gap-16 gap-lg-40 mt-16 text-left">
    {{-- Pass Mark --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
            <x-iconsax-lin-tick-circle class="icons text-gray-500" width="20px" height="20px"/>
        </div>
        <div class="ml-8">
            <div class="font-12 text-gray-500">{{ trans('public.pass_mark') }}</div>
            <div class="font-weight-bold text-gray-500 mt-2">{{  $quiz->pass_mark }}/{{ $quiz->questions_grade }}</div>
        </div>
    </div>

    {{-- Your Grade --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
            <x-iconsax-lin-note-2 class="icons text-gray-500" width="20px" height="20px"/>
        </div>
        <div class="ml-8">
            <div class="font-12 text-gray-500">{{ trans('quiz.your_grade') }}</div>
            <div class="font-weight-bold text-gray-500 mt-2">{{ $quiz->result->user_grade }}</div>
        </div>
    </div>

    {{-- Attempts --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
            <x-iconsax-lin-refresh-2 class="icons text-gray-500" width="20px" height="20px"/>
        </div>
        <div class="ml-8">
            <div class="font-12 text-gray-500">{{ trans('update.attempts') }}</div>
            <div class="font-weight-bold text-gray-500 mt-2">{{ $quiz->result_count ?? 0 }}/{{ !empty($quiz->attempt) ? $quiz->attempt : trans('update.unlimited') }}</div>
        </div>
    </div>

    {{-- Status --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
            <x-iconsax-lin-award class="icons text-gray-500" width="20px" height="20px"/>
        </div>
        <div class="ml-8">
            <div class="font-12 text-gray-500">{{ trans('public.status') }}</div>

            @if($quiz->result->status == "passed")
                <div class="font-weight-bold mt-2 text-success">{{ trans('quiz.passed') }}</div>
            @elseif($quiz->result->status == "failed")
                <div class="font-weight-bold mt-2 text-danger">{{ trans('quiz.failed') }}</div>
            @else
                <div class="font-weight-bold mt-2 text-warning">{{ trans('quiz.waiting') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="d-flex align-items-center gap-12 mt-24">
    @if($quiz->result->status == "passed")
        @if($quiz->can_download_certificate)
            <a href="/panel/quizzes/results/{{ $quiz->result->id }}/showCertificate" target="_blank" class="btn btn-primary btn-lg">{{ trans('update.download_certificate') }}</a>
        @endif

        <a href="/panel/quizzes/{{ $quiz->result->id }}/result" target="_blank" class="btn btn-outline-primary btn-lg">{{ trans('update.view_answers') }}</a>

    @elseif($quiz->result->status == "failed")
        @if($quiz->can_try)
            <a href="/panel/quizzes/{{ $quiz->id }}/overview" target="_blank" class="btn btn-primary btn-lg">{{ trans('public.try_again') }}</a>
        @else
            <a href="/panel/quizzes/{{ $quizResult->id }}/result" target="_blank" class="btn btn-primary btn-lg">{{ trans('update.view_answers') }}</a>
        @endif

        <a href="/panel/quizzes/my-results" target="_blank" class="btn btn-outline-primary btn-lg">{{ trans('update.my_results') }}</a>
    @endif
</div>

@if($quiz->result->status == "passed")
    <div class="d-flex align-items-center mt-16 font-12 text-gray-500">
        <x-iconsax-lin-medal class="icons text-gray-500" width="20px" height="20px"/>
        <span class="ml-4">{{ trans('update.you_achieved_a_certificate_for_passing_this_quiz') }}</span>
    </div>
@elseif($quiz->result->status == "failed" and $quiz->can_try)
    <div class="d-flex align-items-center mt-16 font-12 text-gray-500">
        <x-iconsax-lin-refresh-2 class="icons text-gray-500" width="20px" height="20px"/>
        <span class="ml-4">
            @if($quiz->remaining_try_again == "unlimited")
                {{ trans('update.you_have_unlimited_chances_to_try_again_this_quiz') }}
            @else
                {{ trans('update.you_have_n_other_chances_to_try_again_this_quiz', ['count' => $quiz->remaining_try_again]) }}
            @endif
        </span>
    </div>
@endif
