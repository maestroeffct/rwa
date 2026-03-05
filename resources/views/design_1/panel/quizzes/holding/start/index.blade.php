@extends('design_1.web.layouts.app', ['appFooter' => false])

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("quiz") }}">
@endpush

@section('content')
    <form id="quizHoldingForm" action="/panel/quizzes/{{ $quiz->id }}/store-result" method="post" class="">
        {{ csrf_field() }}
        <input type="hidden" name="quiz_result_id" value="{{ $newQuizStart->id }}" class="form-control" placeholder=""/>
        <input type="hidden" name="attempt_number" value="{{ $attemptCount }}" class="form-control" placeholder=""/>
        <input type="hidden" class="js-quiz-question-count" value="{{ $quizQuestions->count() }}"/>


        <div class="container mt-160 pb-120">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">

                    {{-- Top Info --}}
                    @include('design_1.panel.quizzes.holding.start.top_info')

                    {{-- Quiz Content --}}
                    <div class="quiz-content-card bg-white rounded-32 p-16 mt-20">

                        {{-- Header --}}
                        <div class="position-relative d-flex align-items-center justify-content-between">
                            <div class="d-flex-center bg-gray-100 py-4 px-8 rounded-32 text-gray-500 font-12">
                                <span class="">{{ trans('public.questions') }}</span>
                                <span class="js-question-count-text ml-4">1/{{ $quizQuestions->count() }}</span>
                            </div>

                            @if(!empty($quiz->time))
                                <div class="d-flex align-items-center timer ltr font-14 font-weight-bold text-dark" data-minutes-left="{{ $quiz->time }}"></div>
                            @else
                                <div class="font-14 font-weight-bold text-dark">{{ trans('quiz.unlimited') }}</div>
                            @endif
                        </div>

                        <div class="quiz-time-progress-container position-relative rounded-4 bg-gray-100 mt-12">
                            <div class="js-time-progress-bar progress-bar rounded-4 bg-success" style="width: 0"></div>
                        </div>

                        {{-- Seperator --}}
                        <div class="quiz-content-separator d-flex align-items-center mt-16 mb-8">
                            <div class="flex-1 border-top-gray-200"></div>
                        </div>

                        {{-- Questions Form  --}}
                        @include('design_1.panel.quizzes.holding.start.questions_form')

                    </div>

                </div>
            </div>
        </div>

        <div class="quiz-holding-footer bg-white py-16 soft-shadow-2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-8">
                        <div class="d-flex align-items-center justify-content-between">

                            <div class="d-flex align-items-center">
                                <div class="js-previous-btn d-flex-center size-48 rounded-circle bg-gray-100 bg-hover-gray-200 mr-16 text-gray-500">
                                    <x-iconsax-lin-arrow-left class="icons " width="16px" height="16px"/>
                                </div>

                                <div class="js-next-btn d-flex-center size-48 rounded-circle bg-gray-100 bg-hover-gray-200 {{ ($quizQuestions->count() > 1) ? 'text-primary cursor-pointer' : 'text-gray-500' }}">
                                    <x-iconsax-lin-arrow-right class="icons " width="16px" height="16px"/>
                                </div>

                            </div>

                            <button type="button" class="js-finish-btn btn btn-lg btn-danger">{{ trans('update.finish_quiz') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="quiz-holding-footer__progressbar"></div>
        </div>

    </form>
@endsection

@push('scripts_bottom')
    <script>
        var cancelLang = '{{ trans('public.cancel') }}';
        var quizFinishTitle = '{{ trans('update.finish_quiz') }}';
        var quizFinishHint = '{!! trans('update.finish_quiz_hint') !!}';
        var confirmLang = '{{ trans('update.finish_quiz_confirm') }}';
    </script>

    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>
    <script src="/assets/default/vendors/jquery.simple.timer/jquery.simple.timer.js"></script>
    <script src="{{ getDesign1ScriptPath("quiz_start", "panel") }}"></script>
@endpush
