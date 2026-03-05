@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("cart_page") }}">
@endpush

@php
    $isMultiCurrency = !empty(getFinancialCurrencySettings('multi_currency'));
    $userCurrency = currency();
    $invalidChannels = [];
@endphp

@section("content")
    <section class="container my-56 position-relative">
        <div class="d-flex-center flex-column text-center">
            <h1 class="font-32">{{ trans('update.checkout') }}</h1>
            <p class="mt-8 font-16 text-gray-500">
                {{ handlePrice($calculatePrices["total"], true, true, false, null, true) . ' ' . trans('cart.for_items',['count' => $count]) }}
            </p>
        </div>

        <form id="formpaymentrequest" action="/payments/payment-request" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="row">
                {{-- Items --}}
                <div class="col-12 col-md-7 col-lg-9 mt-32 mb-104">

                    {{-- CashBack --}}
                    @if(!empty($totalCashbackAmount))
                        @include('design_1.web.cart.overview.includes.cashback_alert')
                    @endif

                    <div class="card-with-mask position-relative">
                        <div class="mask-8-white"></div>

                        <div class="position-relative z-index-2 bg-white rounded-16 py-16">
                            <div class="card-before-line px-16">
                                <h3 class="font-14">{{ trans('update.select_a_payment_gateway') }}</h3>
                            </div>

                            <div class="d-grid grid-columns-2 grid-lg-columns-3 gap-24 px-16 mt-16">
                                @if(!empty($paymentChannels))
                                    @foreach($paymentChannels as $paymentChannel)
                                        @if(!$isMultiCurrency or (!empty($paymentChannel->currencies) and in_array($userCurrency, $paymentChannel->currencies)))
                                            <div class="payment-channel-card position-relative">
                                                <input
                                                    type="radio"
                                                    name="gateway"
                                                    id="gateway_{{ $paymentChannel->id }}"
                                                    data-class="{{ $paymentChannel->class_name }}"
                                                    value="{{ $paymentChannel->id }}"
                                                    image="{{ $paymentChannel->image }}"
                                                    totalprice="{{ convertPriceToUserCurrency($order->total_amount, getUserCurrencyItem(Auth::user())) }}"
                                                    orderid="{{ $order->id }}"
                                                    currency="{{ currency() }}"
                                                    onclick="togglePaymentChannel(this)"
                                                    url="{{ route('afpay-payment-link') }}"
                                                    token="{{ csrf_token() }}"
                                                >
                                                <label class="position-relative w-100 d-block cursor-pointer" for="gateway_{{ $paymentChannel->id }}">
                                                    <div class="gateway-mask"></div>
                                                    <div class="gateway-card position-relative z-index-2 d-flex-center flex-column rounded-16 bg-white w-100 h-100 text-center">
                                                        <div class="d-flex-center size-48 bg-gray-100">
                                                            <img src="{{ $paymentChannel->image }}" alt="" class="img-fluid">
                                                        </div>
                                                        <h6 class="font-14 mt-12">{{ $paymentChannel->title }}</h6>
                                                    </div>
                                                </label>
                                            </div>
                                        @else
                                            @php $invalidChannels[] = $paymentChannel; @endphp
                                        @endif
                                    @endforeach
                                @endif

                                <div class="payment-channel-card position-relative">
                                    <input type="radio" name="gateway" id="gateway_credit" value="credit" {{ (empty($userCharge) or ($calculatePrices["total"] > $userCharge)) ? 'disabled' : '' }}>
                                    <label class="position-relative w-100 d-block cursor-pointer" for="gateway_credit">
                                        <div class="gateway-mask"></div>
                                        <div class="gateway-card position-relative z-index-2 d-flex-center flex-column rounded-16 bg-white w-100 h-100 text-center">
                                            <div class="d-flex-center size-48 bg-gray-100">
                                                <x-iconsax-bul-empty-wallet class="icons text-dark" width="48px" height="48px"/>
                                            </div>
                                            <h6 class="font-14 mt-12">{{ trans('financial.account_charge') }}</h6>
                                            <p class="mt-4 font-12 text-gray-500">{{ handlePrice($userCharge) }}</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            @if(!empty($invalidChannels) and empty(getFinancialSettings("hide_disabled_payment_gateways")))
                                <div class="px-16 mt-28">
                                    <div class="position-relative pl-8">
                                        <div class="d-flex align-items-center p-12 rounded-12 bg-gray-500-20">
                                            <div class="alert-left-20 d-flex-center size-48 bg-gray-500 rounded-12">
                                                <x-iconsax-bol-info-circle class="icons text-white" width="24px" height="24px"/>
                                            </div>
                                            <div class="ml-8">
                                                <h6 class="font-14 text-gray-500">{{ trans('update.disabled_payment_gateways') }}</h6>
                                                <p class="font-12 text-gray-500 opacity-75">{{ trans('update.disabled_payment_gateways_hint') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid grid-columns-3 gap-24 mt-16">
                                        @foreach($invalidChannels as $invalidChannel)
                                            <div class="disabled-payment-channel d-flex align-items-center p-16 rounded-16 border-gray-200">
                                                <div class="d-flex-center size-48 bg-gray-100">
                                                    <img src="{{ $invalidChannel->image }}" alt="" class="img-fluid">
                                                </div>
                                                <h6 class="font-14 ml-16 text-gray-500">{{ $invalidChannel->title }}</h6>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- Right Side --}}
                <div class="col-12 col-md-5 col-lg-3 mt-32">
                    <div class="cart-right-side-section">
                        <div class="js-cart-summary-container">
                            @include('design_1.web.cart.overview.includes.summary', ['isCartPaymentPage' => true])
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </section>

    @if(!empty($razorpay) and $razorpay)
        <form action="/payments/verify/Razorpay" method="get">
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <script src="https://checkout.razorpay.com/v1/checkout.js"
                    data-key="{{ getRazorpayApiKey()['api_key'] }}"
                    data-amount="{{ (int)($order->total_amount * 100) }}"
                    data-buttontext=""
                    data-description="Razorpay"
                    data-currency="{{ currency() }}"
                    data-image="{{ $generalSettings['logo'] }}"
                    data-prefill.name="{{ $order->user->full_name }}"
                    data-prefill.email="{{ $order->user->email }}"
                    data-theme.color="#43d477">
            </script>
        </form>
    @endif
@endsection

@push('scripts_bottom')
<script>
    var hasErrors = '{{ (!empty($errors) and count($errors)) ? 'true' : 'false' }}';
    var hasErrorsHintLang = '{{ trans('update.please_check_the_errors_in_the_shipping_form') }}';
    var selectPaymentGatewayLang = '{{ trans('update.select_a_payment_gateway') }}';
    var pleaseWaitLang = '{{ trans('update.please_wait') }}';
    var transferringToLang = '{{ trans('update.transferring_to_the_payment_gateway') }}';
</script>
<script src="{{ getDesign1ScriptPath("cart_page") }}"></script>
@endpush
