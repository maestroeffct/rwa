<div class="d-flex-center flex-column text-center">
    <div class="d-flex-center size-64 rounded-16 bg-info-gradient">
        <x-iconsax-bul-video class="icons text-white" width="32px" height="32px"/>
    </div>

    <h3 class="font-14 font-weight-bold mt-12">{{ trans('update.are_you_sure_to_join_the_session?') }}</h3>
    <p class="font-14 text-gray-500 mt-8">{{ trans('update.are_you_sure_to_join_the_session_hint') }}</p>
</div>

<div class="mt-16 p-16 rounded-12 border-gray-200">
    <div class="d-flex align-items-center justify-content-between">
        <span class="font-14 text-gray-500">{{ trans('product.course') }}</span>
        <span class="font-14">{{ $course->title }}</span>
    </div>

    @if(!empty($session))
        <div class="d-flex align-items-center justify-content-between mt-12">
            <span class="font-14 text-gray-500">{{ trans('webinars.session_title') }}</span>
            <span class="font-14">{{ $session->title }}</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-12">
            <span class="font-14 text-gray-500">{{ trans('update.session_date') }}</span>
            <span class="font-14">{{ dateTimeFormat($session->date, 'Y-m-d H:i', false, true, ($session->webinar ? $session->webinar->timezone : null)) }}</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-12">
            <span class="font-14 text-gray-500">{{ trans('update.session_duration') }}</span>
            <span class="font-14">{{ convertMinutesToHourAndMinute($session->duration) }} Hrs</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-12">
            <span class="font-14 text-gray-500">{{ trans('update.live_provider') }}</span>
            <span class="font-14">{{ $session->session_api }}</span>
        </div>
    @endif
</div>
