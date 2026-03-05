@extends('design_1.web.layouts.app', ['appFooter' => false, 'appHeader' => false])

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("learning_page_noticeboards") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("learning_page") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("agora_page") }}?v=14">
@endpush

@section('content')
    <div class="agora-page d-flex">
        <div class="agora-page__main">
            {{-- Top Header --}}
            @if(!empty($session->webinar))
                @include('design_1.web.courses.learning_page.includes.top_header', ['course' => $session->webinar])
            @else
                @include('design_1.web.courses.agora.includes.other_top_header')
            @endif

            {{-- Page Content --}}
            <div class="agora-page__main-content" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
                <div id="mainContent" class="w-100 h-100 pb-56">
                    {{-- Stream --}}
                    @include('design_1.web.courses.agora.stream')
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        @include('design_1.web.courses.agora.sidebar.index')
    </div>

    {{-- Noticeboards --}}
    @if(!empty($session->webinar))
        @include('design_1.web.courses.learning_page.noticeboards.index', ['course' => $session->webinar])
    @endif
@endsection

@push('scripts_bottom')
    <script>
        var appId = '{{ $appId }}';
        var accountName = '{{ $accountName }}';
        var userName = '{{ $userName }}';
        var channelName = '{{ $channelName }}';
        var streamRole = '{{ $streamRole }}';
        var redirectAfterLeave = '{{ url('/panel') }}';
        var streamStartAt = Number({{ $streamStartAt }});
        var sessionId = Number({{ $session->id }});
        var sessionStreamType = '{{ $sessionStreamType }}';
        var authUserId = Number({{ $authUserId }});
        var hostUserId = Number({{ $hostUserId }});
        var rtcToken = '{{ $rtcToken }}';

        var notStarted = false;
        @if($notStarted) notStarted = true @endif

        var userDefaultAvatar = '{{ getThemePageBackgroundSettings('user_avatar') }}';
        var fullscreenIcon = `<x-iconsax-lin-mirroring-screen class="icons text-gray-500" width="16px" height="16px"/>`;
        // Langs
        var joinedToChannel = '{{ trans('update.joined_the_live') }}';
        var liveEndedLang = '{{ trans('update.this_live_has_been_ended') }}';
        var redirectToPanelInAFewMomentLang = '{{ trans('update.a_few_moments_redirect_to_panel') }}';
        var joinedLiveSessionLang = '{{ trans('update.joined_live_session') }}'
        var endLiveLang = '{{ trans('update.end_live') }}'
        var endLiveStreamLang = '{{ trans('update.end_live_stream?') }}'
        var endLiveConfirmLang = '{{ trans('update.end_live_stream_confirm_hint') }}'
        var closeLang = '{{ trans('public.close') }}'
        var hostLang = '{{ trans('update.host') }}'
        var studentLang = '{{ trans('quiz.student') }}'
    </script>

    <script src="{{ getDesign1ScriptPath("learning_page_noticeboards") }}"></script>
    <script src="{{ getDesign1ScriptPath("agora/agora_live_stream") }}?v=14"></script>

    @if($session->agora_settings->chat)
        <script>
            var rtmToken = '{{ $rtmToken }}';
        </script>

        <script src="{{ getDesign1ScriptPath("agora/agora_chat") }}"></script>
    @endif
@endpush
