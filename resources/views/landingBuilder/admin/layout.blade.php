<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];

    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
@endphp
<head>
    @include('design_1.web.includes.metas')
    <title>{{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : '' }}</title>

    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/design_1/css/app.min.css">
    <link rel="stylesheet" href="/assets/design_1/landing_builder/app.min.css">

    @if($isRtl)
        <link rel="stylesheet" href="/assets/design_1/css/rtl-app.min.css">
    @endif

    @stack('styles_top')
    @stack('scripts_top')

    <style>

        {!! getThemeFontsSettings() !!}

        {!! getThemeColorsSettings() !!}
    </style>

</head>
<body class="@if($isRtl) rtl @endif">

<div id="panel_app">

    @include('landingBuilder.admin.includes.header')

    <div class="d-flex justify-content-end">
        @if(!empty($sidebarFilePath))
            @include($sidebarFilePath)
        @else
            @include('landingBuilder.admin.includes.sidebar')
        @endif

        <div class="landing-builder-content">
            @include('landingBuilder.admin.includes.title_and_breadcrumb')

            <div id="panelContentScrollable" class="landing-builder-content__scrollable px-28 pt-20 pb-40" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
                @yield('content')
            </div>
        </div>
    </div>

</div>
<!-- Template JS File -->

<!-- Template JS File -->
<script>
    var landingBuilderPrefixUrl = '{{ getLandingBuilderUrl('') }}';
    var siteDomain = '{{ url('') }}';
    var deleteAlertTitle = '{{ trans('public.are_you_sure') }}';
    var deleteAlertHint = '{{ trans('public.deleteAlertHint') }}';
    var deleteAlertConfirm = '{{ trans('public.deleteAlertConfirm') }}';
    var deleteAlertCancel = '{{ trans('public.cancel') }}';
    var deleteAlertSuccess = '{{ trans('public.success') }}';
    var deleteAlertFail = '{{ trans('public.fail') }}';
    var deleteAlertFailHint = '{{ trans('public.deleteAlertFailHint') }}';
    var deleteAlertSuccessHint = '{{ trans('public.deleteAlertSuccessHint') }}';
    var forbiddenRequestToastTitleLang = '{{ trans('public.forbidden_request_toast_lang') }}';
    var forbiddenRequestToastMsgLang = '{{ trans('public.forbidden_request_toast_msg_lang') }}';
    var priceInvalidHintLang = '{{ trans('update.price_invalid_hint') }}';
    var clearLang = '{{ trans('clear') }}';
    var requestSuccessLang = '{{ trans('public.request_success') }}';
    var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    var requestFailedLang = '{{ trans('public.request_failed') }}';
    var oopsLang = '{{ trans('update.oops') }}';
    var somethingWentWrongLang = '{{ trans('update.something_went_wrong') }}';
    var loadingDataPleaseWaitLang = '{{ trans('update.loading_data,_please_wait') }}';
    var deleteRequestLang = '{{ trans('update.delete_request') }}';
    var deleteRequestTitleLang = '{{ trans('update.delete_request_title') }}';
    var deleteRequestDescriptionLang = '{{ trans('update.delete_request_description') }}';
    var requestDetailsLang = '{{ trans('update.request_details') }}';
    var sendRequestLang = '{{ trans('update.send_request') }}';
    var closeLang = '{{ trans('public.close') }}';
    var generatedContentLang = '{{ trans('update.generated_content') }}';
    var copyLang = '{{ trans('public.copy') }}';
    var doneLang = '{{ trans('public.done') }}';
    var jsCurrentCurrency = '{{ $currency }}';
    var defaultLocale = '{{ getUserLocale() }}';
    var appLocale = '{{ app()->getLocale() }}';
    var dangerCloseIcon = `<x-iconsax-lin-add class="icons text-danger" width="24" height="24"/>`;
    var directSendIcon = `<x-iconsax-lin-direct-send class="icons text-primary" width="24" height="24"/>`;
    var closeIcon = `<x-iconsax-lin-add class="close-icon" width="25px" height="25px"/>`;
    var bulDangerIcon = `<x-iconsax-bul-danger class="icons text-white" width="32px" height="32px"/>`;
    var defaultAvatarPath = "{{ getDefaultAvatarPath() }}";
    var themeColorsMode = @json(getThemeColorsMode());
</script>

<script type="text/javascript" src="/assets/design_1/js/app.min.js"></script>
<script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>
<script src="/assets/default/vendors/moment.min.js"></script>
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>

@if(session()->has('toast'))
    <script>
        (function () {
            "use strict";

            showToast('{{ session()->get('toast')['status'] }}', '{{ session()->get('toast')['title'] ?? '' }}', '{{ session()->get('toast')['msg'] ?? '' }}')
        })(jQuery)
    </script>
@endif


@stack('styles_bottom')
@stack('scripts_bottom')

@stack('scripts_bottom2')

<script src="/assets/design_1/landing_builder/js/general.min.js"></script>
</body>
</html>
