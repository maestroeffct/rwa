@extends('design_1.web.layouts.app', ['appFooter' => false])

@section('content')
    <div class="container my-64">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="bg-white p-16 rounded-32">

                    {{-- Stats --}}
                    <div class="d-flex align-items-center flex-wrap gap-16">

                        <div class="d-flex align-items-center bg-gray-100 p-24 rounded-16 flex-1">
                            <x-iconsax-bul-tick-circle class="icons text-primary" width="32px" hright="32px"/>
                            <div class="ml-8">
                                <span class="d-block font-16 font-weight-bold">{{  $quiz->pass_mark }}/{{  $quizQuestions->sum('grade') }}</span>
                                <span class="d-block font-12 text-gray-500">{{ trans('quiz.pass_mark') }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center bg-gray-100 p-24 rounded-16 flex-1">
                            <x-iconsax-bul-note-2 class="icons text-primary" width="32px" hright="32px"/>
                            <div class="ml-8">
                                <span class="d-block font-16 font-weight-bold">{{  $quizResult->user_grade }}/{{  $quizQuestions->sum('grade') }}</span>
                                <span class="d-block font-12 text-gray-500">{{ trans('quiz.your_grade') }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center bg-gray-100 p-24 rounded-16 flex-1">
                            <x-iconsax-bul-refresh-2 class="icons text-primary" width="32px" hright="32px"/>
                            <div class="ml-8">
                                <span class="d-block font-16 font-weight-bold">{{ $attemptCount }}/{{ !empty($quiz->attempt) ? $quiz->attempt : trans('update.unlimited') }}</span>
                                <span class="d-block font-12 text-gray-500">{{ trans('update.attempts') }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center bg-gray-100 p-24 rounded-16 flex-1">
                            <x-iconsax-bul-award class="icons text-primary" width="32px" hright="32px"/>
                            <div class="ml-8">
                                <span class="d-block font-16 font-weight-bold {{ ($quizResult->status == 'passed') ? 'text-primary' : ($quizResult->status == 'waiting' ? 'text-warning' : 'text-danger') }}">{{ trans('quiz.'.$quizResult->status) }}</span>
                                <span class="d-block font-12 text-gray-500">{{ trans('public.status') }}</span>
                            </div>
                        </div>

                    </div>


                    @switch($quizResult->status)

                        @case(\App\Models\QuizzesResult::$passed)
                            @include('design_1.panel.quizzes.holding.status.passed')
                        @break

                        @case(\App\Models\QuizzesResult::$failed)
                            @include('design_1.panel.quizzes.holding.status.failed')
                        @break

                        @case(\App\Models\QuizzesResult::$waiting)
                            @include('design_1.panel.quizzes.holding.status.pending')
                        @break

                    @endswitch

                </div>
            </div>
        </div>
    </div>
@endsection
