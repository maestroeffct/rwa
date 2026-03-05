@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("system_status_pages") }}">
@endpush

@php
    $errorSettings = get404ErrorPageSettings();
@endphp

@section("content")
    <section class="container mt-96 mb-104 position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="system-status-page-section position-relative">
                    <div class="system-status-page-section__mask"></div>

                    <div class="position-relative d-flex-center flex-column bg-white rounded-32 p-24 pt-64 p-lg-40 text-center z-index-2">

                        @if(!empty($errorSettings['right_float_image']))
                            <div class="system-status-page-right-float-image">
                                <img src="{{ $errorSettings['right_float_image'] }}" alt="{{ trans('update.right_float_image') }}" class="img-cover">
                            </div>
                        @endif

                        @if(!empty($errorSettings['image']))
                            <div class="system-status-page-image">
                                <img src="{{ $errorSettings['image'] }}" alt="{{ $errorSettings['title'] ?? '' }}" class="img-cover">
                            </div>
                        @endif

                        @if(!empty($errorSettings['title']))
                            <h1 class="font-16 font-weight-bold mt-16">{{ $errorSettings['title'] }}</h1>
                        @endif

                        @if(!empty($errorSettings['description']))
                            <p class="font-14 text-gray-500 mt-4">{!! nl2br($errorSettings['description']) !!}</p>
                        @endif

                        @if(!empty($errorSettings['button']) and !empty($errorSettings['button']['title']) and !empty($errorSettings['button']['link']))
                            <a href="{{ $errorSettings['button']['link'] }}" class="btn btn-primary btn-lg mt-24">{{ $errorSettings['button']['title'] }}</a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
