@extends("design_1.web.layouts.app")

@php
    $pageHeroImage = !empty($form->cover) ? $form->cover : getThemePageBackgroundSettings("form_default_cover");
    $pageHeaderIcon = !empty($form->header_icon) ? $form->header_icon : getThemePageBackgroundSettings("form_default_header_icon");
    $pageOverlayImage = !empty($form->header_overlay_image) ? $form->header_overlay_image : getThemePageBackgroundSettings("form_default_overlay_image");
@endphp

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("forms") }}">
@endpush

@section('content')
    <main class="pb-56">
        <section class="form-page-hero position-relative">
            <div class="form-page-hero__mask"></div>
            <img src="{{ $pageHeroImage }}" class="img-cover" alt="{{ trans('update.forms') }}"/>
        </section>

        <div class="container">

            {{-- Header --}}
            <div class="form-page-header position-relative">
                <div class="form-page-header__mask"></div>
                <div class="position-relative d-flex align-items-start bg-white rounded-32 z-index-2">
                    <div class="d-flex flex-column p-32">

                        <div class="d-flex-center size-64 rounded-12 bg-gray-400-30">
                            @if(!empty($pageHeaderIcon))
                                <img src="{{ $pageHeaderIcon }}" alt="{{ trans('admin/main.form_default_header_icon_background') }}" class="img-fluid">
                            @else
                                <x-iconsax-bul-note-2 class="icons text-primary" width="32px" height="32px"/>
                            @endif
                        </div>

                        <div class="d-flex align-items-center mt-16 text-gray-500">
                            <a href="/" class="text-gray-500">{{ getPlatformName() }}</a>
                            <x-iconsax-lin-arrow-right-1 class="mx-4" width="16px" height="16px"/>
                            <span class="">{{ trans('update.forms') }}</span>
                        </div>

                        <h1 class="font-24 font-weight-bold mt-12">{{ $form->title }}</h1>
                        <div class="font-12 text-gray-500 mt-8">{{ $form->subtitle }}</div>
                    </div>

                    @if(!empty($pageOverlayImage))
                        <div class="form-page-header__overlay-img">
                            <img src="{{ $pageOverlayImage }}" alt="{{ trans('update.overlay_image') }}" class="img-cover">
                        </div>
                    @endif
                </div>
            </div>

            {{-- Content --}}
            @yield("formContent")
        </div>
    </main>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>

    <script src="{{ getDesign1ScriptPath("forms") }}"></script>
@endpush
