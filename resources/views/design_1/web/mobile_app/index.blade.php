
@php
    $mobileAppSettings = getMobileAppSettings();

    $layoutOptions = [
        'appHeader' => false,
        'justMobileApp' => true
    ];

    if (empty($mobileAppSettings['show_app_footer'])) {
        $layoutOptions['appFooter'] = true;
    }
@endphp

@extends("design_1.web.layouts.app", $layoutOptions)


@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("system_status_pages") }}">
@endpush

@section("content")
    <section class="container maintenance-page-container position-relative">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-12 col-lg-8">
                <div class="system-status-page-section position-relative">
                    <div class="system-status-page-section__mask"></div>

                    <div class="position-relative d-flex-center flex-column bg-white rounded-32 p-24 pt-64 p-lg-40 text-center z-index-2">

                        @if(!empty($mobileAppSettings['right_float_image']))
                            <div class="system-status-page-right-float-image">
                                <img src="{{ $mobileAppSettings['right_float_image'] }}" alt="{{ trans('update.right_float_image') }}" class="img-cover">
                            </div>
                        @endif

                        @if(!empty($mobileAppSettings['mobile_app_hero_image']))
                            <div class="system-status-page-image">
                                <img src="{{ $mobileAppSettings['mobile_app_hero_image'] }}" alt="{{ $mobileAppSettings['title'] ?? '' }}" class="img-cover">
                            </div>
                        @endif

                        @if(!empty($mobileAppSettings['title']))
                            <h1 class="font-16 font-weight-bold mt-16">{{ $mobileAppSettings['title'] }}</h1>
                        @endif

                        @if(!empty($mobileAppSettings['mobile_app_description']))
                            <p class="font-14 text-gray-500 mt-4">{!! nl2br($mobileAppSettings['mobile_app_description']) !!}</p>
                        @endif

                        @if(!empty($mobileAppSettings['maintenance_button']) and !empty($mobileAppSettings['maintenance_button']['title']) and !empty($mobileAppSettings['maintenance_button']['link']))
                            <a href="{{ $mobileAppSettings['maintenance_button']['link'] }}" class="btn btn-primary btn-lg mt-24">{{ $mobileAppSettings['maintenance_button']['title'] }}</a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    {{--<script src="/assets/design_1/js/parts/time-counter-down.min.js"></script>
    <script src="{{ getDesign1ScriptPath("maintenance") }}"></script>--}}
@endpush
