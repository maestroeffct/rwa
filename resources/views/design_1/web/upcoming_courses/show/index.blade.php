@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">

    <link rel="stylesheet" href="{{ getDesign1StylePath("css_stars") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("buy_with_points") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("reviews_and_comments") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("show_course") }}">
@endpush


@section("content")
    <div class="container position-relative mt-80 pb-120 ">

        {{-- Hero --}}
        @include('design_1.web.upcoming_courses.show.includes.hero')

        <div class="d-flex flex-column flex-lg-row gap-24">
            {{-- Contant and Tabs --}}
            <div class="course-body-side position-relative course-body-card flex-1">
                @include('design_1.web.upcoming_courses.show.includes.page_body')

                {{-- Ads Bannaer --}}
                @include('design_1.web.components.advertising_banners.page_banner')
                {{-- ./ Ads Bannaer --}}
            </div>

            {{-- Right Side --}}
            <div class="course-right-side position-relative">
                @include('design_1.web.upcoming_courses.show.includes.right_side')

                {{-- Sidebar ads Banner --}}
                @include('design_1.web.components.advertising_banners.sidebar_banner')
            </div>
        </div>
    </div>

    {{-- Bottom Fixed --}}
    @include("design_1.web.upcoming_courses.show.includes.bottom_fixed_card")
@endsection


@push('scripts_bottom')
    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>

    <script src="{{ getDesign1ScriptPath("swiper_slider") }}"></script>
    <script src="{{ getDesign1ScriptPath("video_player_helpers") }}"></script>

    <script>
        var webinarDemoLang = '{{ trans('webinars.webinar_demo') }}';
        var notAccessToastTitleLang = '{{ trans('public.not_access_toast_lang') }}';
        var notAccessToastMsgLang = '{{ trans('public.not_access_toast_msg_lang') }}';
        var closeLang = '{{ trans('public.close') }}';
        var shareLang = '{{ trans('public.share') }}';
        var reportCommentLang = '{{ trans('update.report_comment') }}';
        var reportLang = '{{ trans('panel.report') }}';
        var reportCourseLang = '{{ trans('update.report_course') }}';
        var joinCourseWaitlistLang = '{{ trans('update.join_course_waitlist') }}';
        var joinWaitlistLang = '{{ trans('update.join_waitlist') }}';
        var purchaseWithPointsLang = '{{ trans('update.purchase_with_points') }}';
    </script>

    <script src="{{ getDesign1ScriptPath("comments") }}"></script>
    <script src="{{ getDesign1ScriptPath("show_course") }}"></script>
@endpush
