@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("system_status_pages") }}">
@endpush

@section("content")
    <section class="container mt-96 mb-104 position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="system-status-page-section position-relative">
                    <div class="system-status-page-section__mask"></div>

                    <div class="position-relative d-flex-center flex-column bg-white rounded-32 p-24 pt-64 p-lg-40 text-center z-index-2">

                        @if(!empty($userNotAccess) and $userNotAccess)
                            <div class="system-status-page-image">
                                <img src="/assets/design_1/img/courses/private_mode/pending_verification.svg" alt="" class="img-cover">
                            </div>
                            <h1 class="font-16 font-weight-bold mt-16">{{ trans('update.course_pending_verification_title') }}</h1>
                            <p class="font-14 text-gray-500 mt-4">{{ trans('update.course_pending_verification_desc') }}</p>
                            <a href="/" class="btn btn-primary mt-16">{{ trans('update.back_to_home') }}</a>
                        @else
                            <div class="system-status-page-image">
                                <img src="/assets/design_1/img/courses/private_mode/private_content.svg" alt="" class="img-cover">
                            </div>
                            <h1 class="font-16 font-weight-bold mt-16">{{ trans('update.course_private_content_title') }}</h1>
                            <p class="font-14 text-gray-500 mt-4">{{ trans('update.course_private_content_desc') }}</p>
                            <a href="/login" class="btn btn-primary mt-16">{{ trans('auth.login') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
