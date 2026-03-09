@extends('design_1.web.layouts.app', ['appFooter' => false, 'appHeader' => false])

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("learning_page_noticeboards") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("learning_page") }}">
@endpush

@section('content')
    <div class="learning-page d-flex">
        <div class="learning-page__main">
            {{-- Top Header --}}
            @include('design_1.web.courses.learning_page.includes.top_header')

            {{-- Page Content --}}
            @include('design_1.web.courses.learning_page.includes.main_content')
        </div>

        {{-- Sidebar --}}
        @include('design_1.web.courses.learning_page.includes.sidebar.index')
    </div>

    {{-- Noticeboards --}}
    @include('design_1.web.courses.learning_page.noticeboards.index')
@endsection

@push('scripts_bottom')
    <script>
        var courseUrl = '{{ $course->getUrl() }}';
        var courseSlug = '{{ $course->slug }}';
        var courseLearningUrl = '{{ $course->getLearningPageUrl() }}';
        // Watermark user context
        @if(auth()->check())
        window.wmUserName = @json(auth()->user()->full_name ?? '');
        window.wmUserAvatar = @json(auth()->user()->getAvatar(40));
        @else
        window.wmUserName = '';
        window.wmUserAvatar = '';
        @endif
        var defaultItemType = '{{ !empty(request()->get('type')) ? request()->get('type') : (!empty($userLearningLastView) ? $userLearningLastView->item_type : '') }}'
        var defaultItemId = '{{ !empty(request()->get('item')) ? request()->get('item') : (!empty($userLearningLastView) ? $userLearningLastView->item_id : '') }}'
        var loadFirstContent = {{ (!empty($dontAllowLoadFirstContent) and $dontAllowLoadFirstContent) ? 'false' : 'true' }}; // allow to load first content when request item is empty
        // Langs
        var learningPageEmptyContentTitleLang = '{{ trans('update.learning_page_empty_content_title') }}';
        var learningPageEmptyContentHintLang = '{{ trans('update.learning_page_empty_content_hint') }}';
        var pleaseWaitLang = '{{ trans('update.please_wait') }}';
        var pleaseWaitForTheContentLang = '{{ trans('update.please_wait_for_the_content_to_load') }}';
        var newCourseNoteLang = '{{ trans('update.new_course_note') }}';
        var editCourseNoteLang = '{{ trans('update.edit_course_note') }}';
        var courseNoteLang = '{{ trans('update.course_note') }}';
        var saveNoteLang = '{{ trans('update.save_note') }}';
        var deleteNoteLang = '{{ trans('update.delete_note') }}';
        var submittedOnLang = '{{ trans('update.submitted_on') }}';
        var editLang = '{{ trans('public.edit') }}';
        var accessDeniedLang = '{{ trans('update.access_denied') }}';
        var noteLang = '{{ trans('update.note') }}';
        var accessDeniedModalFooterHintLang = '{{ trans('update.your_access_will_be_delegated_automatically') }}';
        var rateAssignmentLang = '{{ trans('update.rate_assignment') }}';
        var passGradeLang = '{{ trans('update.pass_grade') }}';
        var submitGradeLang = '{{ trans('update.submit_grade') }}';
        var submitQuestionLang = '{{ trans('update.submit_question') }}';
        var courseCompletedLang = '{{ trans('update.course_completed') }}';
    </script>

    <script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>
    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>

    <script src="{{ getDesign1ScriptPath("video_player_helpers") }}"></script>
    <script src="{{ getDesign1ScriptPath("learning_page_noticeboards") }}"></script>
    <script src="{{ getDesign1ScriptPath("learning_page") }}"></script>
    <script>
        (function ($) {
            "use strict";

            let isNavigatingToNextItem = false;

            function parseRequestData(data) {
                if (!data) return {};

                if (typeof data === "object") return data;

                const parsed = {};
                try {
                    data.split("&").forEach(function (pair) {
                        const parts = pair.split("=");
                        const key = decodeURIComponent(parts[0] || "");
                        const value = decodeURIComponent((parts[1] || "").replace(/\+/g, " "));

                        if (key) {
                            parsed[key] = value;
                        }
                    });
                } catch (e) {
                }

                return parsed;
            }

            function getNextAvailableItem() {
                const $active = $(".js-content-tab-item.active").first();

                if (!$active.length) {
                    return null;
                }

                const $items = $(".js-content-tab-item").not(".js-sequence-content-error-modal");
                const activeIndex = $items.index($active);

                if (activeIndex < 0) {
                    return null;
                }

                for (let i = activeIndex + 1; i < $items.length; i++) {
                    const $candidate = $($items[i]);

                    if ($candidate.length) {
                        return $candidate;
                    }
                }

                return null;
            }

            function redirectToNextItem() {
                if (isNavigatingToNextItem) return;

                const $next = getNextAvailableItem();
                if (!$next || !$next.length) return;

                const nextType = $next.attr("data-type");
                const nextItemId = $next.attr("data-id");

                if (!nextType || !nextItemId) return;

                isNavigatingToNextItem = true;

                const nextUrl = `${courseLearningUrl}?type=${encodeURIComponent(nextType)}&item=${encodeURIComponent(nextItemId)}`;
                window.location.assign(nextUrl);
            }

            // Manual check -> save success -> move to next content item
            $(document).ajaxSuccess(function (event, xhr, settings) {
                if (isNavigatingToNextItem || !settings || !settings.url) {
                    return;
                }

                const isLearningStatusRequest =
                    settings.url.indexOf("/learningStatus") !== -1 &&
                    settings.url.indexOf("/track-time") === -1;

                if (!isLearningStatusRequest) {
                    return;
                }

                const requestData = parseRequestData(settings.data);
                const checked = requestData.status === true || requestData.status === "true" || requestData.status === "1";

                if (!checked) {
                    return;
                }

                setTimeout(redirectToNextItem, 250);
            });

            // Video ended (HTML5 source) -> auto check current item
            $("body").on("ended", "video.plyr-io-video", function () {
                const $toggle = $("#mainContent").find(".js-passed-item-toggle").first();

                if (!$toggle.length || $toggle.prop("checked") || $toggle.prop("disabled")) {
                    return;
                }

                $toggle.prop("checked", true).trigger("change");
            });
        })(jQuery);
    </script>
    <script>
        // Watermark behavior from settings
        (function(){
            try {
                var wmEnabled = {{ (int) (getGeneralSecuritySettings('learning_page_watermark') ? 1 : 0) }};
                var wmMode = @json(getGeneralSecuritySettings('learning_page_watermark_mode') ?? 'fade');
                var wmOpacity = parseFloat(@json(getGeneralSecuritySettings('learning_page_watermark_opacity') ?? ''));
                var wmSize = @json(getGeneralSecuritySettings('learning_page_watermark_size') ?? '1');
                var wmData = @json(getGeneralSecuritySettings('learning_page_watermark_data') ?? 'student');
                // platform info from general basic
                window.platformName = @json(getGeneralSettings('site_name') ?? '');
                window.platformLogo = @json(getGeneralSettings('logo') ?? '');
                window.platformFavicon = @json(getGeneralSettings('fav_icon') ?? '');
                // instructor info
                window.instructorName = @json(($course->teacher->full_name ?? '') ?? '');
                window.instructorAvatar = @json(($course->teacher->getAvatar(40) ?? '') ?? '');
                // student info
                window.studentEmail = @json((auth()->user()->email ?? '') ?? '');
                window.studentPhone = @json((auth()->user()->mobile ?? auth()->user()->phone ?? '') ?? '');
                window.authUserId = @json((auth()->id() ?? '') ?? '');

                window.wmEnabled = !!wmEnabled;
                window.wmMode = wmMode === 'move' ? 'move' : 'fade';
                if (!isNaN(wmOpacity)) {
                    window.wmOpacity = Math.max(0, Math.min(1, wmOpacity));
                }
                window.wmSize = (['1','2','3'].indexOf(String(wmSize)) !== -1) ? String(wmSize) : '1';
                window.wmData = (['student','student_name_id','student_phone','student_email','instructor','platform','platform_logo'].indexOf(String(wmData)) !== -1) ? String(wmData) : 'student';
            } catch (e) {}
        })();
    </script>
@endpush
