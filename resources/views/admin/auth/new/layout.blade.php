<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];
    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
    $themeCustomCssAndJs = getThemeCustomCssAndJs();
    $userThemeColorMode = getUserThemeColorMode();
    $pageBackgroundImage = getThemePageBackgroundSettings("admin_login");
@endphp

<head>
    @include('design_1.web.includes.metas')
    <title>{{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : '' }}</title>

    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/design_1/css/app.min.css">
    <link rel="stylesheet" href="/assets/admin/css/extra.min.css">

    @if($isRtl)
        <link rel="stylesheet" href="/assets/design_1/css/rtl-app.min.css">
    @endif

    @stack('styles_top')
    @stack('scripts_top')

    <style>
        {!! !empty($themeCustomCssAndJs['css']) ? $themeCustomCssAndJs['css'] : '' !!}

        {!! getThemeFontsSettings() !!}

        {!! getThemeColorsSettings() !!}
    </style>

</head>

<body class="bg-gray {{ $isRtl ? 'rtl' : '' }} {{ "{$userThemeColorMode}-mode" }}">

<div id="app">

    <div class="admin-auth-pages">
        <div class="admin-auth-pages__bg bg-gray-100">
            @if(!empty($pageBackgroundImage))
                <img src="{{ $pageBackgroundImage }}" alt="{{ trans('update.background') }}" class="img-cover">
            @endif
        </div>

        <div class="admin-auth-pages__contents px-16 px-lg-0">
            @yield('content')
        </div>

        <div class="admin-auth-pages__footer d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white px-24 py-14">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                    <x-iconsax-bol-info-circle class="icons text-gray-400" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <h5 class="font-16">Rocket LMS</h5>
                    <a href="https://codecanyon.net/user/rocketsoft/portfolio" class="font-12 text-gray-500 mt-4">All rights reserved for Rocket Soft on Codecanyon</a>
                </div>
            </div>

            <div class="d-flex align-items-center gap-24 mt-16 mt-lg-0">
                <a href="/" class="font-12 text-gray-500 ">{{ trans('navbar.home') }}</a>
                <a href="/classes?sort=newest" class="font-12 text-gray-500 ">{{ trans('product.courses') }}</a>
                <a href="/contact" class="font-12 text-gray-500 ">{{ trans('site.contact_us') }}</a>
            </div>

        </div>
    </div>
</div>

<!-- Template JS File -->
<script>

    var themeColorsMode = @json(getThemeColorsMode());
</script>

<script type="text/javascript" src="/assets/design_1/js/app.min.js"></script>
<script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>
<script defer src="/assets/design_1/js/parts/content_delete.min.js"></script>

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

<script src="/assets/admin/js/parts/auth_pages.min.js"></script>
<script src="/assets/design_1/js/parts/general.min.js"></script>
</body>
</html>
