@extends("design_1.web.layouts.app", ['appFooter' => false, 'appHeader' => false, 'justMobileApp' => true])

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("system_status_pages") }}">
@endpush

@php
    $restrictionSettings = getRestrictionSettings();

@endphp

@section("content")
    <section class="container maintenance-page-container position-relative">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-12 col-lg-8">
                <div class="system-status-page-section position-relative">
                    <div class="system-status-page-section__mask"></div>

                    <div class="position-relative d-flex-center flex-column bg-white rounded-32 p-24 pt-64 p-lg-40 text-center z-index-2">

                        @if(!empty($restrictionSettings['right_float_image']))
                            <div class="system-status-page-right-float-image">
                                <img src="{{ $restrictionSettings['right_float_image'] }}" alt="{{ trans('update.right_float_image') }}" class="img-cover">
                            </div>
                        @endif

                        @if(!empty($restrictionSettings['image']))
                            <div class="system-status-page-image">
                                <img src="{{ $restrictionSettings['image'] }}" alt="{{ $restrictionSettings['title'] ?? '' }}" class="img-cover">
                            </div>
                        @endif

                        @if(!empty($restrictionSettings['title']))
                            <h1 class="font-16 font-weight-bold mt-16">{{ $restrictionSettings['title'] }}</h1>
                        @endif

                        @if(!empty($restrictionSettings['description']))
                            <p class="font-14 text-gray-500 mt-4">{!! nl2br($restrictionSettings['description']) !!}</p>
                        @endif

                        @if(!empty($restrictionSettings['maintenance_button']) and !empty($restrictionSettings['maintenance_button']['title']) and !empty($restrictionSettings['maintenance_button']['link']))
                            <a href="{{ $restrictionSettings['maintenance_button']['link'] }}" class="btn btn-primary btn-lg mt-24">{{ $restrictionSettings['maintenance_button']['title'] }}</a>
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
