<div class="">
    <img src="/assets/design_1/img/courses/learning_page/session_not_started.svg" alt="" class="img-fluid" width="285px" height="212px">
</div>

<h3 class="font-16 mt-12">{{ trans('update.session_is_not_started_yet') }}</h3>
<div class="mt-8 text-gray-500">{!! nl2br(($userIsInstructor) ? trans('update.session_not_started_hint_for_instructor') : trans('update.session_not_started_hint_for_student')) !!}</div>

<div class="d-flex align-items-center gap-20 gap-lg-40 mt-16">
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
            <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
        </div>
        <div class="ml-8 text-left">
            <span class="d-block font-12 text-gray-400">{{ trans('public.start_date') }}</span>
            <div class="font-weight-bold text-gray-500">{{ dateTimeFormat($session->date, 'j M Y H:i') }}</div>
        </div>
    </div>

    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
            <x-iconsax-lin-clock-1 class="icons text-gray-500" width="20px" height="20px"/>
        </div>
        <div class="ml-8 text-left">
            <span class="d-block font-12 text-gray-400">{{ trans('public.duration') }}</span>
            <div class="font-weight-bold text-gray-500">{{ $session->duration }} {{ trans('public.minutes') }}</div>
        </div>
    </div>
</div>

<div class="d-flex flex-column flex-lg-row align-items-lg-center gap-12 mt-24">
    @if($userIsInstructor)
        <a href="{{ $session->getJoinLink(true) }}" target="_blank" class="btn btn-primary btn-lg">{{ trans('update.start_session') }}</a>
    @else
        <button type="button" class="js-check-again-session btn btn-primary btn-lg" data-id="{{ $session->id }}">{{ trans('update.check_again') }}</button>
    @endif

    <a href="{{ $session->addToCalendarLink() }}" target="_blank" class="btn btn-outline-primary btn-lg">{{ trans('update.add_to_reminder') }}</a>
</div>
