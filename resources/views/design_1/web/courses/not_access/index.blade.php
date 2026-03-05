@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("system_status_pages") }}">
@endpush

@section("content")
    <section class="container mt-96 mb-56 position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="system-status-page-section position-relative">
                    <div class="system-status-page-section__mask"></div>

                    <div class="position-relative d-flex-center flex-column bg-white rounded-32 p-24 pt-64 p-lg-40 text-center z-index-2">
                        <div class="system-status-page-image">
                            <img src="/assets/design_1/img/courses/private_mode/private_content.svg" alt="" class="img-cover">
                        </div>

                        <h1 class="font-16 font-weight-bold mt-16">{{ trans('update.access_denied') }}</h1>
                        <p class="font-14 text-gray-500 mt-4">{{ trans('update.this_course_is_not_accessible_publicly_at_this_moment') }}</p>
                        <a href="/" class="btn btn-primary mt-16">{{ trans('update.back_to_home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
