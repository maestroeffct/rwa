@extends("design_1.web.layouts.app")

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("system_status_pages") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("gift_page") }}">
@endpush

@php
    $rightFloatImage = getGiftsGeneralSettings("right_float_image");
@endphp

@section("content")
    <div class="container mt-56 mb-120">
        <div class="text-center">
            <h1 class="font-32 font-weight-bold">{{ $pageTitle }}</h1>
            <p class="font-16 text-gray-500 mt-8">{{ $titleHint }}</p>
        </div>

        <div class="row justify-content-center mt-56">
            <div class="col-12 col-lg-8">
                <div class="position-relative">
                    <div class="system-status-page-section__mask"></div>

                    @if(!empty($rightFloatImage))
                        <div class="system-status-page-right-float-image">
                            <img src="{{ $rightFloatImage }}" alt="{{ trans('update.right_float_image') }}" class="img-cover">
                        </div>
                    @endif

                    <div class="position-relative bg-white p-32 rounded-32 z-index-2">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <h2 class="font-24 font-weight-bold">{{ trans('update.receipt_information') }} 🎁</h2>
                                <p class="font-14 text-gray-500 mt-16">{{ trans('update.please_insert_your_gift_receipt_information_accurately_the_system_will_send_the_gift') }}</p>

                                <form action="/gift/{{ $itemType }}/{{ $item->slug }}" method="post" class="mt-40">

                                    <div class="form-group mb-28">
                                        <label class="form-group-label">{{ trans('auth.name') }}:</label>
                                        <input name="name" type="text" class="js-ajax-name form-control">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="form-group mb-28">
                                        <label class="form-group-label">{{ trans('auth.email') }}:</label>
                                        <input name="email" type="email" class="js-ajax-email form-control">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="form-group mb-28">
                                        <label class="form-group-label">{{ trans('update.gift_date') }}:</label>
                                        <input name="date" type="text" class="js-ajax-date form-control datetimepicker js-default-init-date-picker" autocomplete="off">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="form-group mb-0">
                                        <label class="form-group-label">{{ trans('update.message_to_recipient_(optional)') }}:</label>
                                        <textarea name="description" rows="7" class="js-ajax-description form-control"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <button type="button" class="js-submit-gift-form-btn btn btn-primary btn-block mt-16">{{ trans('update.proceed_to_checkout') }}</button>
                                </form>

                            </div>

                            <div class="col-12 col-md-6 mt-20 mt-md-0">
                                <div class="bg-gray-100 rounded-16 py-64 py-md-104 px-32 px-md-52">
                                    @include('design_1.web.gift.includes.item_card')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>

    <script src="{{ getDesign1ScriptPath("send_gift_page") }}"></script>
@endpush
