@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("cart_page") }}">
@endpush

@section("content")
    <section class="container mt-96 mb-104 position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="cart-empty position-relative">
                    <div class="cart-empty__mask"></div>

                    <div class="position-relative d-flex-center flex-column bg-white rounded-32 p-24 pt-64 p-lg-40 text-center z-index-2">

                        <div class="cart-empty-image">
                            <img src="/assets/design_1/img/cart/empty.svg" alt="{{ trans('update.cart_is_empty') }}" class="img-fluid">
                        </div>

                        <h1 class="font-16 font-weight-bold mt-16">{{ trans('update.cart_is_empty') }}</h1>

                        <p class="font-14 text-gray-500 mt-4">{{ trans('update.cart_is_empty_page_hint') }}</p>


                        @if(!empty($cartDiscount))
                            @include('design_1.web.cart.includes.discount', [
                                'cartDiscount' => $cartDiscount,
                                'className' => "mt-20"
                            ])
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
