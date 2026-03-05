@php
    $checkSequenceContent = $quiz->checkSequenceContent();
    $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));
@endphp

<div class="accordion bg-gray-100 border-gray-200 p-16 rounded-12 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center cursor-pointer" href="#collapseQuiz{{ $quiz->id }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
            <div class="d-flex mr-8">
                <x-iconsax-lin-clipboard-tick class="icons text-gray-500" width="20px" height="20px"/>
            </div>

            <div class="font-14 font-weight-bold d-block">{{ $quiz->title }}</div>
        </div>

        <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseQuiz{{ $quiz->id }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
            <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
        </div>
    </div>

    <div id="collapseQuiz{{ $quiz->id }}" class="accordion__collapse border-0 " role="tabpanel">
        @if(!empty($quiz->description))
            <div class="p-16 rounded-12 border-gray-200 bg-gray-100">
                <div class="font-14 text-gray-500">
                    {!! nl2br(clean($quiz->description)) !!}
                </div>
            </div>
        @endif

        <div class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between p-16 bg-white border-gray-200 rounded-12 mt-16{{ (!empty($quiz->description)) ? 'mt-24' : '' }}">
            @if(!empty($quiz->description))
                <div class="course-content-separator-with-circles">
                    <span class="circle-top"></span>
                    <span class="circle-bottom"></span>
                </div>
            @endif

            <div class="d-flex align-items-center flex-wrap gap-24">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-message-question class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('public.questions') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ $quiz->quizQuestions->count() }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-clock-1 class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('public.duration') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ $quiz->time }} {{ trans('public.minutes') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-verify class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.pass_grade') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ $quiz->pass_mark }}/{{ $quiz->quizQuestions->sum('grade') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-book class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.total_grade') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ $quiz->total_mark }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-refresh-2 class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.attempts') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ (!empty($user) and !empty($quiz->result_count)) ? $quiz->result_count : '0' }}/{{ $quiz->attempt }}</span>
                    </div>
                </div>

                @if(!empty($user) and !empty($quiz->result_status))
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                            <x-iconsax-lin-status class="icons text-gray-500" width="16px" height="16px"/>
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-12 text-gray-400">{{ trans('public.status') }}</span>
                            @if($quiz->result_status == 'passed')
                                <span class="d-block font-12 font-weight-bold text-success mt-4">{{ trans('quiz.passed') }}</span>
                            @elseif($quiz->result_status == 'failed')
                                <span class="d-block font-12 font-weight-bold text-danger mt-4">{{ trans('quiz.failed') }}</span>
                            @else
                                <span class="d-block font-12 font-weight-bold text-warning mt-4">{{ trans('quiz.waiting') }}</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-16 mt-lg-0">
                @if(!empty($user) and $quiz->can_try and $hasBought)
                    <a href="/panel/quizzes/{{ $quiz->id }}/start" class="btn btn-primary btn-lg">
                        <x-iconsax-lin-arrow-right class="icons text-white" width="16px" height="16px"/>
                        <span class="ml-4 text-white">{{ trans('quiz.quiz_start') }}</span>
                    </a>
                @else
                    <button type="button" class="btn btn-lg bg-gray-300 disabled {{ ((empty($user)) ? 'not-login-toast' : (!$hasBought ? 'not-access-toast' : (!$quiz->can_try ? 'can-not-try-again-quiz-toast' : ''))) }}">
                        <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4 text-gray-500">{{ trans('quiz.quiz_start') }}</span>
                    </button>
                @endif
            </div>

        </div>

    </div>
</div>
