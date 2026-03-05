@if(!empty($quiz->icon))
    <div class="d-flex-center size-120">
        <img src="{{ $quiz->icon }}" alt="" class="img-cover">
    </div>
@else
    <div class="d-flex-center size-120 rounded-circle bg-primary">
        <x-iconsax-bul-clipboard-tick class="icons text-white" width="64px" height="64px"/>
    </div>
@endif

<h4 class="mt-12 font-16 text-dark">{{ $quiz->title }}</h4>

<div class="text-gray-500 mt-8">{!! nl2br($userIsInstructor ? trans('update.learning_page_quiz_not_participated_hint_for_instructor') : trans('update.learning_page_quiz_not_participated_hint_for_student')) !!}</div>


<div class="d-flex align-items-center flex-wrap gap-16 gap-lg-40 mt-16 text-left">
    @if($userIsInstructor)
        {{-- Participated Students --}}
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                <x-iconsax-lin-profile-2user class="icons text-gray-500" width="20px" height="20px"/>
            </div>
            <div class="ml-8">
                <div class="font-12 text-gray-500">{{ trans('update.participated_students') }}</div>
                <div class="font-weight-bold text-gray-500 mt-2">{{ $quiz->participated_students }}</div>
            </div>
        </div>

        {{-- Passed Students --}}
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                <x-iconsax-lin-tick-circle class="icons text-gray-500" width="20px" height="20px"/>
            </div>
            <div class="ml-8">
                <div class="font-12 text-gray-500">{{ trans('update.passed_students') }}</div>
                <div class="font-weight-bold text-gray-500 mt-2">{{ $quiz->passed_students }}</div>
            </div>
        </div>

        {{-- Failed Students --}}
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                <x-iconsax-lin-close-circle class="icons text-gray-500" width="20px" height="20px"/>
            </div>
            <div class="ml-8">
                <div class="font-12 text-gray-500">{{ trans('update.failed_students') }}</div>
                <div class="font-weight-bold text-gray-500 mt-2">{{ $quiz->failed_students }}</div>
            </div>
        </div>

        {{-- Waiting Students --}}
        @if($quiz->show_waiting)
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                    <x-iconsax-lin-more-circle class="icons text-gray-500" width="20px" height="20px"/>
                </div>
                <div class="ml-8">
                    <div class="font-12 text-gray-500">{{ trans('update.waiting_students') }}</div>
                    <div class="font-weight-bold text-gray-500 mt-2">{{ $quiz->waiting_students ?? 0 }}</div>
                </div>
            </div>
        @endif

        {{-- Average Grade --}}
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                <x-iconsax-lin-sound class="icons text-gray-500" width="20px" height="20px"/>
            </div>
            <div class="ml-8">
                <div class="font-12 text-gray-500">{{ trans('update.average_grade') }}</div>
                <div class="font-weight-bold text-gray-500 mt-2">{{ $quiz->average_grade ?? 0 }}</div>
            </div>
        </div>

    @else
        {{-- Quiz Time --}}
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                <x-iconsax-lin-timer class="icons text-gray-500" width="20px" height="20px"/>
            </div>
            <div class="ml-8">
                <div class="font-12 text-gray-500">{{ trans('update.quiz_time') }}</div>
                <div class="font-weight-bold text-gray-500 mt-2">{{ $quiz->time }} {{ trans('update.mins') }}</div>
            </div>
        </div>

        {{-- Questions --}}
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                <x-iconsax-lin-clipboard-tick class="icons text-gray-500" width="20px" height="20px"/>
            </div>
            <div class="ml-8">
                <div class="font-12 text-gray-500">{{ trans('public.questions') }}</div>
                <div class="font-weight-bold text-gray-500 mt-2">{{ $quiz->quiz_questions_count }}</div>
            </div>
        </div>

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
                <div class="font-weight-bold text-danger mt-2">{{ trans('update.not_participated') }}</div>
            </div>
        </div>

    @endif
</div>


@if($userIsInstructor)
    <a href="/panel/quizzes/results" target="_blank" class="btn btn-primary btn-lg mt-24">{{ trans('update.manage_students_result') }}</a>
@else
    @if($quiz->can_start)
        <a href="/panel/quizzes/{{ $quiz->id }}/overview" target="_blank" class="btn btn-primary btn-lg mt-24">{{ trans('update.view_quiz') }}</a>
    @endif
@endif


@if(!empty($expireTimestamp))
    @php
        $expireToday = checkTimestampInToday($expireTimestamp);
        $expireClassName = $expireToday ? 'text-warning' : ($expireTimestamp < time() ? 'text-danger' : 'text-gray-500')
    @endphp

    <div class="d-flex align-items-center mt-24">
        <x-iconsax-bul-danger class="icons {{ $expireClassName }}" width="24px" hright="24px"/>
        <span class="ml-4 font-12 {{ $expireClassName }}">{!! trans('update.this_quiz_expires_on_date', ['date' => dateTimeFormat($expireTimestamp, 'j M Y H:i')]) !!}</span>
    </div>
@endif
