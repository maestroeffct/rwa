@php
    $hasDiscount = $product->getActiveDiscount();
@endphp

<div class="panel-product-card-1 position-relative">
    <div class="card-mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row gap-12 z-index-2 bg-white p-12 rounded-24">
        <a href="{{ $product->getUrl() }}" target="_blank" class="d-flex flex-column flex-lg-row gap-12 flex-grow-1 text-decoration-none">
            {{-- Image --}}
            <div class="panel-product-card-1__image position-relative rounded-16 bg-gray-100">
                <img src="{{ $product->thumbnail }}" alt="" class="img-cover rounded-16">

                <div class="product-type-icon d-flex-center size-64 rounded-circle">
                    @if($product->isPhysical())
                        <x-iconsax-bol-box class="icons text-white" width="24px" height="24px"/>
                    @else
                        <x-iconsax-bol-document-download class="icons text-white" width="24px" height="24px"/>
                    @endif
                </div>

                {{-- Badges --}}
                @include("design_1.panel.store.my_products.product_card.badges")

            </div>

            {{-- Content --}}
            <div class="panel-product-card-1__content flex-1 d-flex flex-column">
                <div class="bg-gray-100 p-16 rounded-16 mb-12">
                    <div class="d-flex align-items-start justify-content-between gap-12">
                        <div class="">
                            <h3 class="font-16 text-dark">{{ truncate($product->title, 46) }}</h3>

                            @include("design_1.web.components.rate", [
                                'rate' => round($product->getRate(),1),
                                'rateCount' => $product->reviews()->where('status', 'active')->count(),
                                'rateClassName' => 'mt-8',
                            ])
                        </div>
                    </div>

                    {{-- Stats --}}
                    @include("design_1.panel.store.my_products.product_card.stats")
                    {{-- End Stats --}}
                </div>

                {{-- Progress & Price --}}
                <div class="row align-items-center justify-content-between mt-auto">
                    <div class="col-8">
                        @include("design_1.panel.store.my_products.product_card.progress_and_chart")
                    </div>

                    {{-- Price --}}
                    <div class="col-4 d-flex align-items-center justify-content-end font-16 font-weight-bold text-primary">
                        @if($product->price > 0)
                            @if($product->getPriceWithActiveDiscountPrice() < $product->price)
                                <span class="">{{ handlePrice($product->getPriceWithActiveDiscountPrice(), true, true, false, null, true, 'store') }}</span>
                                <span class="font-14 font-weight-400 text-gray-500 ml-8 text-decoration-line-through">{{ handlePrice($product->price, true, true, false, null, true, 'store') }}</span>
                            @else
                                <span class="">{{ handlePrice($product->price, true, true, false, null, true, 'store') }}</span>
                            @endif
                        @else
                            <span class="">{{ trans('public.free') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </a>

        {{-- Actions Dropdown (positioned outside the link) --}}
        <div class="item-card-actions-dropdown-container">
            @include("design_1.panel.store.my_products.product_card.actions_dropdown")
        </div>
    </div>
</div>
