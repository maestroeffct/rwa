@extends('design_1.web.layouts.app', ['appFooter' => false])

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("quiz") }}">
@endpush


@section('content')
    <div class="container my-64">
        <div class="row justify-content-center">
            <div class="col-12 mt-72 col-lg-8">
                <div class="bg-white p-16 rounded-32">

                    {{-- Stats --}}
                    <div class="d-flex align-items-center flex-wrap gap-16">

                        <div class="d-flex align-items-center bg-gray-100 p-24 rounded-16 flex-1">
                            <x-iconsax-bul-timer-1 class="icons text-primary" width="32px" height="32px"/>
                            <div class="ml-8">
                                @if(!empty($quiz->time))
                                    <span class="d-block font-16 font-weight-bold">{{ $quiz->time }} {{ trans('update.mins') }}</span>
                                @else
                                    <span class="d-block font-16 font-weight-bold">{{ trans('quiz.unlimited') }}</span>
                                @endif

                                <span class="d-block font-12 text-gray-500">{{ trans('update.quiz_time') }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center bg-gray-100 p-24 rounded-16 flex-1">
                            <x-iconsax-bul-clipboard-tick class="icons text-primary" width="32px" height="32px"/>
                            <div class="ml-8">
                                <span class="d-block font-16 font-weight-bold">{{ $totalQuestionsCount }}</span>
                                <span class="d-block font-12 text-gray-500">{{ trans('public.questions') }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center bg-gray-100 p-24 rounded-16 flex-1">
                            <x-iconsax-bul-tick-circle class="icons text-primary" width="32px" height="32px"/>
                            <div class="ml-8">
                                <span class="d-block font-16 font-weight-bold">{{  $quiz->pass_mark }}/{{  $quizQuestions->sum('grade') }}</span>
                                <span class="d-block font-12 text-gray-500">{{ trans('public.pass_mark') }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center bg-gray-100 p-24 rounded-16 flex-1">
                            <x-iconsax-bul-refresh-2 class="icons text-primary" width="32px" height="32px"/>
                            <div class="ml-8">
                                <span class="d-block font-16 font-weight-bold">{{ $attemptCount }}/{{ !empty($quiz->attempt) ? $quiz->attempt : trans('update.unlimited') }}</span>
                                <span class="d-block font-12 text-gray-500">{{ trans('update.attempts') }}</span>
                            </div>
                        </div>

                    </div>


                    <div class="d-flex-center flex-column text-center mt-48">
                        <div class="d-flex-center size-80">
                            @if(!empty($quiz->icon))
                                <img src="{{ $quiz->icon }}" class="img-cover rounded-12">
                            @else
                                <x-iconsax-bul-clipboard-tick class="icons text-success" width="32px" height="32px"/>
                            @endif
                        </div>

                        <h1 class="font-24 font-weight-bold mt-12">{{ $quiz->title }}</h1>

                        @if(!empty($quiz->description))
                            <p class="mt-8 font-14 text-gray-500 mx-32">{{ $quiz->description }}</p>
                        @endif

                        <div class="quiz-overview-center-line mt-8 bg-gray-400"></div>

                        <div class="size-48 rounded-circle mt-8">
                            <img src="{{ $quiz->creator->getAvatar(48) }}" alt="{{ $quiz->creator->full_name }}" class="img-cover rounded-circle">
                        </div>

                        <div class="mt-8 font-12 font-weight-bold text-gray-500">{{ $webinar->title }}</div>

                        <div class="mt-4 font-12 text-gray-500">{{ trans('public.by') }} {{ $quiz->creator->full_name }}</div>

                        @php
                            $canStart = true;

                            if (!$quiz->checkCanAccessByExpireDays()) {
                                $canStart = false;
                            }

                            if (!$quiz->checkUserCanStartByAttempt()) {
                                $canStart = false;
                            }

                            $expireTimestamp = $quiz->getExpireTimestamp();
                        @endphp

                        @if($canStart)
                            <a href="/panel/quizzes/{{ $quiz->id }}/start" class="btn btn-primary btn-lg mt-16">{{ trans('update.start_quiz') }}</a>
                        @endif


                        <div class="d-flex align-items-center flex-wrap gap-32 mb-48">

                            @if(!empty($quiz->certificate))
                                <div class="d-flex align-items-center mt-16">
                                    <x-iconsax-lin-medal class="icons text-gray-500" width="20px" height="20px"/>
                                    <span class="ml-4 text-gray-500 font-12">{{ trans('update.include_certificate') }}</span>
                                </div>
                            @endif

                            @if(!empty($expireTimestamp))
                                @php
                                    $expireToday = checkTimestampInToday($expireTimestamp);
                                    $expireClassName = $expireToday ? 'text-warning' : ($expireTimestamp < time() ? 'text-danger' : 'text-gray-500')
                                @endphp

                                <div class="d-flex align-items-center mt-16">
                                    <x-iconsax-lin-calendar-2 class="icons {{ $expireClassName }}" width="20px" height="20px"/>
                                    <span class="ml-4 font-12 {{ $expireClassName }}">{{ trans('update.expired_on_date', ['date' => dateTimeFormat($expireTimestamp, 'j M Y H:i')]) }}</span>
                                </div>
                            @endif

                        </div>

                    </div>

                    @if($quiz->hasDescriptiveQuestion())
                        <div class="d-flex align-items-center mt-48 border-dashed border-gray-300 rounded-16 p-12">
                            <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                                <x-iconsax-bul-award class="icons text-primary" width="24px" height="24px"/>
                            </div>

                            <div class="ml-8">
                                <h5 class="font-14 font-weight-bold">{{ trans('update.descriptive_quiz') }}</h5>
                                <p class="mt-4 font-12 text-gray-500">{{ trans('update.your_quiz_result_will_be_displayed_after_instructor_review') }}</p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
