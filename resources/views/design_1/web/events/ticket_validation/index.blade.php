@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("instructor_finder") }}">
@endpush

@section('content')
    <div class="container mt-104 pb-140">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <form action="/events/validation/check" method="post">
                    {{ csrf_field() }}

                    <div class="instructor-finder-wizard position-relative bg-white rounded-32 p-16">
                        <div class="instructor-finder-wizard__mask bg-gray-200"></div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="p-16">
                                    <div class="font-16 font-weight-bold">{{ trans('update.validate_now') }} 👋</div>
                                    <h1 class="font-24 font-weight-bold mt-4">{{ trans('update.ticket_validation') }}</h1>
                                    <p class="font-14 text-gray-500 mt-16">{{ trans('update.event_ticket_validation_subtitle_hint') }}</p>

                                    <div class="form-group mt-40">
                                        <label class="form-group-label">{{ trans('update.ticket_code') }}:</label>
                                        <input type="tel" name="ticket_code" class="js-ajax-ticket_code form-control" value="{{ request()->get('code') }}" id="ticket_code" aria-describedby="ticket_codeHelp">
                                        <div class="invalid-feedback"></div>

                                        <div class="mt-8 font-12 text-gray-500">{{ trans('update.event_ticket_validation_code_input_hint') }}</div>
                                    </div>

                                    @include('design_1.web.includes.captcha_input')

                                    <div class="mt-16">
                                        <button type="button" class="js-submit-certificate-validation-form-btn btn btn-primary btn-block btn-lg" data-title="{{ trans('update.ticket_validation') }}">{{ trans('cart.validate') }}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mt-24 mt-md-0">
                                @php
                                    $mainImage = getThemePageBackgroundSettings('event_ticket_validation');
                                    $overlayImage = getThemePageBackgroundSettings('event_ticket_validation_overlay_image');
                                @endphp

                                <div class="instructor-finder-wizard__images-card bg-gray-100 rounded-16">
                                    @if(!empty($mainImage))
                                        <img src="{{ $mainImage }}" alt="{{ trans('update.main_image') }}" class="img-cover rounded-16">
                                    @endif

                                    @if(!empty($overlayImage))
                                        <div class="wizard-overlay-image">
                                            <img src="{{ $overlayImage }}" alt="{{ trans('update.overlay_image') }}" class="img-fluid">
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>
@endsection


@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("certificate_validation") }}"></script>
@endpush
