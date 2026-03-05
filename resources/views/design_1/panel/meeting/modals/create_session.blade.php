@php
    $activeAgora = !empty(getFeaturesSettings('agora_for_meeting'));
@endphp

<div class="d-flex-center flex-column text-center mt-16">
    <div class="d-flex-center size-64 rounded-16 border-gray-4 bg-info-gradient">
        <x-iconsax-bul-video class="icons text-white" width="32px" height="32px"/>
    </div>
    <h4 class="mt-12 font-14 text-dark">{{ trans('update.are_you_sure_to_create_a_live_session?') }}</h4>
    <div class="mt-8 font-12 text-gray-500">{!! nl2br(trans('update.meeting_reserve_create_session_are_you_sure_to_create_a_live_session_hint')) !!}</div>
</div>

<div class="js-meeting-reserve-create-session-form mt-28" data-action="/panel/meetings/{{ $ReserveMeeting->id }}/create-session">

    @if($activeAgora)
        <div class="d-flex align-items-center gap-5 p-4 border-gray-300 rounded-12 mt-8">
            @foreach(['agora', 'external'] as $sessionType)
                <div class="custom-input-button custom-input-button-none-border-and-active-bg  position-relative flex-1">
                    <input type="radio" class="" name="session_type" id="session_type_{{ $sessionType }}" value="{{ $sessionType }}" {{ $loop->first ? 'checked' : '' }}>
                    <label for="session_type_{{ $sessionType }}" class="position-relative d-flex-center flex-column p-12 rounded-8 text-center text-gray-500">
                        {{ trans("update.{$sessionType}_session") }}
                    </label>
                </div>
            @endforeach
        </div>
    @endif

    @if($activeAgora)
        <div class="js-agora-session-fields mt-32 mb-40">
            <div class="d-flex-center flex-column text-center">
                <x-iconsax-bul-video-square class="icons text-gray-400" width="48px" height="48px"/>
                <p class="text-gray-500 mt-8">{!! nl2br(trans('update.the_live_session_will_be_conducted_on_the_platform_directly')) !!}</p>
            </div>
        </div>
    @endif

    <div class="js-external-session-fields mt-28 {{ $activeAgora ? 'd-none' : '' }}">

        <div class="form-group">
            <label class="form-group-label">{{ trans('update.session_url') }}</label>
            <input type="text" name="url" class="js-ajax-url form-control" value="{{ (!empty($ReserveMeeting->link)) ? $ReserveMeeting->link : '' }}">
            <div class="invalid-feedback"></div>
            <div class="font-12 text-gray-500 mt-8">{{ trans('update.create_meeting_session_url_input_hint') }}</div>
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('auth.password') }} ({{ trans('public.optional') }})</label>
            <input type="text" name="password" class="js-ajax-password form-control" value="{{ (!empty($ReserveMeeting->password)) ? $ReserveMeeting->password : '' }}"/>
            <div class="invalid-feedback"></div>
        </div>

    </div>
</div>
