<html lang="{{ app()->getLocale() }}">
@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];
    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
    $themeCustomCssAndJs = getThemeCustomCssAndJs();
@endphp
<head>
    @include('design_1.web.includes.metas')
    <title>{{ $pageTitle ?? '' }} </title>

    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/fontawesome/css/all.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">

    @stack('libraries_top')

    <link rel="stylesheet" href="/assets/admin/css/style.css">
    <link rel="stylesheet" href="/assets/admin/css/custom.css">
    <link rel="stylesheet" href="/assets/admin/css/components.css">
    <link rel="stylesheet" href="/assets/admin/css/extra.min.css">
    @if($isRtl)
        <link rel="stylesheet" href="/assets/admin/css/rtl.css">
    @endif
    <link rel="stylesheet" href="/assets/admin/vendor/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">

    @stack('styles_top')
    @stack('scripts_top')

    <style>
        {!! !empty($themeCustomCssAndJs['css']) ? $themeCustomCssAndJs['css'] : '' !!}

        {!! getThemeFontsSettings() !!}

        {!! getThemeColorsSettings(null, true) !!}
    </style>
</head>
<body class="{{ $isRtl ? 'rtl' : '' }}">

<div id="app">
    <div class="main-wrapper">
        @include('admin.includes.header.index')

        @include('admin.includes.sidebar.index')


        <div class="main-content">

            @yield('content')

        </div>
    </div>

    <div class="modal fade" id="fileViewModal" tabindex="-1" aria-labelledby="fileViewModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <img src="" class="img-fluid" alt="">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('public.close') }}</button>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- AI Contents --}}
@if(!empty(getAiContentsSettingsName("status")) and !empty(getAiContentsSettingsName("active_for_admin_panel")))
    @include('admin.includes.aiContent.generator')
@endif

<script>
    window.adminPanelPrefix = '{{ getAdminPanelUrl() }}';
</script>

<!-- General JS Scripts -->
<script src="/assets/admin/vendor/jquery/jquery-3.3.1.min.js"></script>
<script src="/assets/admin/vendor/poper/popper.min.js"></script>
<script src="/assets/admin/vendor/bootstrap/bootstrap.min.js"></script>
<script src="/assets/admin/vendor/nicescroll/jquery.nicescroll.min.js"></script>
<script src="/assets/admin/vendor/moment/moment.min.js"></script>
<script src="/assets/admin/js/stisla.js"></script>
<script src="/assets/default/vendors/toast/jquery.toast.min.js"></script>
<script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>

<script src="/assets/admin/vendor/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/vendors/select2/select2.min.js"></script>

<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<!-- Template JS File -->
<script src="/assets/admin/js/scripts.js"></script>


<script src="/assets/admin/js/admin.min.js"></script>

@stack('styles_bottom')
@stack('scripts_bottom')

<script>
    (function () {
        "use strict";

        @if(session()->has('toast'))
        showToast('{{ session()->get('toast')['status'] }}', '{{ session()->get('toast')['title'] ?? '' }}', '{{ session()->get('toast')['msg'] ?? '' }}')
        @endif
    })(jQuery);


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

<script src="/assets/admin/js/custom.js"></script>
<script src="/assets/admin/js/parts/ai-content-generator.min.js"></script>

<script>
    {!! !empty($themeCustomCssAndJs['js']) ? $themeCustomCssAndJs['js'] : '' !!}
</script>
</body>
</html>
