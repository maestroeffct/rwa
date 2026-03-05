@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("system_status_pages") }}">
@endpush

@section("content")
    <section class="container mt-96 pb-80 position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="system-status-page-section position-relative">
                    <div class="system-status-page-section__mask"></div>

                    <div class="position-relative d-flex-center flex-column bg-white rounded-32 p-24 pt-64 p-lg-40 text-center z-index-2">

                        <div class="system-status-page-image">
                            <img src="/assets/design_1/img/installments/request_submitted.svg" alt="{{ trans('update.installment_request_submitted') }}" class="img-cover">
                        </div>

                        <h1 class="font-16 font-weight-bold mt-16">{{ trans('update.installment_request_submitted') }}</h1>

                        <p class="font-14 text-gray-500 mt-4">{{ trans('update.installment_request_submitted_hint') }}</p>

                        <a href="/panel/financial/installments" class="btn btn-primary btn-lg mt-24">{{ trans('update.my_installments') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
