@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("cart_page") }}">
@endpush

@section("content")
    <section class="container mt-56 mb-80 position-relative">
        <div class="d-flex-center flex-column text-center">
            <h1 class="font-32">{{ trans('update.cart') }}</h1>
            <p class="mt-8 font-16 text-gray-500">{{ handlePrice($calculatePrices["sub_total"], true, true, false, null, true) . ' ' . trans('cart.for_items',['count' => $carts->count()]) }}</p>
        </div>

        <form action="/cart/checkout" method="post" id="cartForm">
            {{ csrf_field() }}

            <div class="row mb-160">
                {{-- Items --}}
                <div class="col-12 col-md-7 col-lg-9 mt-32 mb-104">

                    {{-- CashBack --}}
                    @if(!empty($totalCashbackAmount))
                        @include('design_1.web.cart.overview.includes.cashback_alert')
                    @endif

                    @if(!empty($userGroup) and !empty($userGroup->discount))
                        @include('design_1.web.cart.overview.includes.user_group_discount')
                    @endif

                    <div class="card-with-mask position-relative">
                        <div class="mask-8-white"></div>

                        <div class="position-relative z-index-2 bg-white rounded-16 py-16">
                            {{-- Items --}}
                            @include('design_1.web.cart.overview.includes.cart_items')

                            @if($hasPhysicalProduct)
                                @include('design_1.web.cart.overview.includes.shipping_and_delivery')
                            @endif

                        </div>
                    </div>

                    {{-- Coupon --}}
                    @include('design_1.web.cart.overview.includes.coupon')
                </div>

                {{-- Right Side --}}
                <div class="col-12 col-md-5 col-lg-3 mt-32">
                    <div class="cart-right-side-section">
                        {{-- Summary --}}

                        <div class="js-cart-summary-container">
                            @include('design_1.web.cart.overview.includes.summary')
                        </div>

                    </div>
                </div>
            </div>

        </form>
    </section>
@endsection

@push('scripts_bottom')
    <script>
        var selectRegionDefaultVal = '';
        var selectStateLang = '{{ trans('update.choose_a_state') }}';
        var selectCityLang = '{{ trans('update.choose_a_city') }}';
        var selectDistrictLang = '{{ trans('update.all_districts') }}';
        var couponLang = '{{ trans('update.coupon') }}';
        var enterCouponLang = '{{ trans('update.please_enter_your_discount_code') }}';
        var removeCouponTitleLang = '{{ trans('update.remove_coupon_title') }}';
        var removeCouponHintLang = '{{ trans('update.remove_coupon_massage_hint') }}';
        var cancelLang = '{{ trans('public.cancel') }}';
        var removeLang = '{{ trans('public.remove') }}';
        var hasErrors = '{{ (!empty($errors) and count($errors)) ? 'true' : 'false' }}';
        var hasErrorsHintLang = '{{ trans('update.please_check_the_errors_in_the_shipping_form') }}';
    </script>

    <script src="{{ getDesign1ScriptPath("get_regions") }}"></script>
    <script src="{{ getDesign1ScriptPath("cart_page") }}"></script>

@endpush
