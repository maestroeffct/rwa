@extends('design_1.web.layouts.app')

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("auth/theme_1") }}">
@endpush

@section("content")
    <section class="container mt-96 mb-104 position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="auth-page-card position-relative">
                    <div class="auth-page-card__mask"></div>

                    <div class="position-relative bg-white rounded-32 p-16 z-index-2">
                        <div class="row">
                            <div class="col-12 col-lg-6">

                                @yield("page_content")

                            </div>

                            <div class="col-12 col-lg-6 d-none d-lg-block">
                                @include('design_1.web.auth.theme_1.includes.slider')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>

    <script src="{{ getDesign1ScriptPath("auth_theme_1") }}"></script>
@endpush
