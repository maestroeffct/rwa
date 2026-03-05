@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("css_stars") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("buy_with_points") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("reviews_and_comments") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("show_event") }}">
@endpush


@section("content")
    <div class="container position-relative mt-80 pb-120 ">

        {{-- Hero --}}
        @include('design_1.web.events.show.includes.hero')

        <div class="d-flex flex-column flex-lg-row gap-24">
            {{-- Contant and Tabs --}}
            <div class="event-body-side position-relative event-body-card flex-1">
                @include('design_1.web.events.show.includes.page_body')


                {{-- Ads Bannaer --}}
                @include('design_1.web.components.advertising_banners.page_banner')
                {{-- ./ Ads Bannaer --}}
            </div>

            {{-- Right Side --}}
            <div class="event-right-side position-relative">
                @include('design_1.web.events.show.includes.right_side')

                {{-- Sidebar ads Banner --}}
                @include('design_1.web.components.advertising_banners.sidebar_banner')
            </div>
        </div>
    </div>

    {{-- Bottom Fixed --}}
    @include("design_1.web.events.show.includes.bottom_fixed_card")

@endsection


@push('scripts_bottom')
    <script>
        var leafletApiPath = '{{ getLeafletApiPath() }}';
        var notAccessToastTitleLang = '{{ trans('public.not_access_toast_lang') }}';
        var notAccessToastMsgLang = '{{ trans('public.not_access_toast_msg_lang') }}';
        var closeLang = '{{ trans('public.close') }}';
        var shareLang = '{{ trans('public.share') }}';
        var reportCommentLang = '{{ trans('update.report_comment') }}';
        var reportEventLang = '{{ trans('update.report_event') }}';
        var reportLang = '{{ trans('panel.report') }}';
        var eventDemoLang = '{{ trans('public.demo_video') }}';
    </script>

    <script src="/assets/default/vendors/barrating/jquery.barrating.min.js"></script>
    <script src="/assets/vendors/leaflet/leaflet.min.js"></script>
    <script src="/assets/design_1/js/parts/time-counter-down.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="{{ getDesign1ScriptPath("swiper_slider") }}"></script>
    <script src="{{ getDesign1ScriptPath("leaflet_map") }}"></script>
    <script src="{{ getDesign1ScriptPath("video_player_helpers") }}"></script>


    <script src="{{ getDesign1ScriptPath("reviews") }}"></script>
    <script src="{{ getDesign1ScriptPath("comments") }}"></script>
    <script src="{{ getDesign1ScriptPath("show_event") }}"></script>
@endpush
