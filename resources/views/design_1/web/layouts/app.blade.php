<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];
    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
    $themeCustomCssAndJs = getThemeCustomCssAndJs();
@endphp

<head>
    @include('design_1.web.includes.metas')
    <title>{{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : '' }}</title>

    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/design_1/css/app.min.css">

    @if($isRtl)
        <link rel="stylesheet" href="/assets/design_1/css/rtl-app.min.css">
    @endif

    @if(!empty($themeHeaderData['component_name']))
        <link rel="stylesheet" href="{{ getDesign1StylePath("theme/headers/{$themeHeaderData['component_name']}") }}">
    @endif

    @if(!empty($themeFooterData['component_name']))
        <link rel="stylesheet" href="{{ getDesign1StylePath("theme/footers/{$themeFooterData['component_name']}") }}">
    @endif

    @stack('styles_top')
    @stack('scripts_top')

    <style>
        {!! !empty($themeCustomCssAndJs['css']) ? $themeCustomCssAndJs['css'] : '' !!}

        {!! getThemeFontsSettings() !!}

        {!! getThemeColorsSettings(!empty($landingItem) ? $landingItem : null) !!}
    </style>

</head>

<body class="bg-gray {{ $isRtl ? 'rtl' : '' }} {{ "{$userThemeColorMode}-mode" }}">

<div id="app">

    @if(!empty($floatingBar) and $floatingBar->position == 'top')
        <div id="appTopFloatingBarArea">
            @include('design_1.web.includes.floating_bar')
        </div>
    @endif

    @if(!isset($appHeader) and !empty($themeHeaderData['component_name']))
        <div id="appHeaderArea">
            @include("design_1.web.theme.headers.{$themeHeaderData['component_name']}.index")
        </div>
    @endif

    {{-- Content --}}
    @yield('content')

    @if(!isset($appFooter) and !empty($themeFooterData['component_name']))
        <div id="appFooterArea">
            @include("design_1.web.theme.footers.{$themeFooterData['component_name']}.index")
        </div>
    @endif

    @include('design_1.web.includes.advertise_modal.index')

    @if(!empty($floatingBar) and $floatingBar->position == 'bottom')
        <div id="appBottomFloatingBarArea">
            @include('design_1.web.includes.floating_bar')
        </div>
    @endif

    {{-- Cart Drawer --}}
    @include('design_1.web.cart.drawer.index')

</div>

<!-- Template JS File -->
<script>
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
<script defer src="/assets/design_1/js/parts/content_delete.min.js"></script>

<script type="text/javascript" src="/assets/design_1/js/parts/afpaymanager/custom.js?ver=2.0"></script>

@if(empty($justMobileApp) and checkShowCookieSecurityDialog() and empty($dontShowCookieSecurity))
    @include('design_1.web.includes.cookie_security.cookie-security')
@endif

@if(session()->has('toast'))
    <script>
        (function () {
            "use strict";

            showToast('{{ session()->get('toast')['status'] }}', '{{ session()->get('toast')['title'] ?? '' }}', '{{ session()->get('toast')['msg'] ?? '' }}')
        })(jQuery)
    </script>
@endif

@include('design_1.web.includes.purchase_notifications')


@stack('styles_bottom')
@stack('scripts_bottom')

@stack('scripts_bottom2')

<script>

    @if(session()->has('registration_package_limited'))
    (function () {
        "use strict";

        handleFireSwalModal('{!! session()->get('registration_package_limited') !!}', 32)
    })(jQuery)

    {{ session()->forget('registration_package_limited') }}
    @endif

    {!! !empty($themeCustomCssAndJs['js']) ? $themeCustomCssAndJs['js'] : '' !!}
</script>

<script src="/assets/design_1/js/parts/general.min.js"></script>
</body>
</html>
