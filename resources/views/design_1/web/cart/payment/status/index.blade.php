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

                    <div class="position-relative d-flex-center flex-column bg-white rounded-32 px-24 px-lg-40 py-54 py-lg-100 text-center z-index-2">

                        @if($order->status === \App\Models\Order::$paid)
                            <div class="system-status-page-image">
                                <img src="/assets/design_1/img/cart/successful_payment.png" alt="{{ trans('update.successful_payment') }}" class="img-cover">
                            </div>

                            <h1 class="font-16 font-weight-bold mt-14">{{ trans('update.successful_payment') }}</h1>

                            <p class="font-14 text-gray-500 mt-4">{{ trans('update.successful_payment_hint') }}</p>

                            <div class="d-flex align-items-center gap-16 mt-16">
                                <a href="academyapp://payment-success" class="btn btn-lg btn-outline-primary d-flex d-sm-none">{{ trans('update.redirect_to_app') }}</a>

                                <a href="/panel" class="btn btn-primary btn-lg">{{ trans('public.my_panel') }}</a>
                            </div>
                        @else
                            @php
                                $isOfflineSubmitted = (!empty($order) && $order->status === \App\Models\Order::$paying);
                            @endphp
                            @if($isOfflineSubmitted)
                                <div class="system-status-page-image">
                                    <img src="/assets/design_1/img/cart/successful_payment.png" alt="{{ trans('update.successful_payment') }}" class="img-cover">
                                </div>

                                <h1 class="font-16 font-weight-bold mt-14">{{ trans('update.offline_payment_submitted') }}</h1>

                                <p class="font-14 text-gray-500 mt-4">{{ trans('update.offline_payment_submitted_hint') }}</p>

                                <div class="d-flex align-items-center gap-16 mt-16">
                                    <a href="/panel" class="btn btn-primary btn-lg">{{ trans('public.my_panel') }}</a>
                                </div>
                            @else
                            <div class="system-status-page-image">
                                <img src="/assets/design_1/img/cart/failed_payment.png" alt="{{ trans('update.failed_payment') }}" class="img-cover">
                            </div>

                            <h1 class="font-16 font-weight-bold mt-14">{{ trans('update.failed_payment') }}</h1>

                            <p class="font-14 text-gray-500 mt-4">{{ trans('update.failed_payment_hint') }}</p>

                            <div class="d-flex align-items-center gap-16 mt-16">
                                <a href="academyapp://payment-failed" class="btn btn-lg btn-outline-primary d-flex d-sm-none">{{ trans('update.redirect_to_app') }}</a>

                                <a href="/panel" class="btn btn-primary btn-lg">{{ trans('public.my_panel') }}</a>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
