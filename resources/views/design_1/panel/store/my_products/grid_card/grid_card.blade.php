@php
    $hasDiscount = $product->getActiveDiscount();
@endphp

<div class="product-grid-card position-relative d-flex bg-white p-12 rounded-16">
    <div class="product-grid-card__image position-relative rounded-16">
        <img src="{{ $product->thumbnail }}" class="img-cover rounded-16" alt="{{ $product->title }}">


        <div class="product-type-icon d-flex-center size-64 rounded-circle">
            @if($product->isPhysical())
                <x-iconsax-bol-box class="icons text-white" width="24px" height="24px"/>
            @else
                <x-iconsax-bol-document-download class="icons text-white" width="24px" height="24px"/>
            @endif
        </div>

    </div>

    <div class="product-grid-card__content d-flex flex-column pl-12 bg-white flex-1">
        <div class="d-flex align-items-start justify-content-between">
            <a href="{{ $product->getUrl() }}" target="_blank">
                <h3 class="product-grid-card__title font-14 text-dark">{{ $product->title }}</h3>
            </a>

            <div class="actions-dropdown position-relative ml-16">
                <div class="webinar-card-actions-btn d-flex-center size-40 rounded-8 bg-gray-100">
                    <x-iconsax-lin-more class="icons text-gray-400" width="24px" height="24px"/>
                </div>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                    <ul class="my-8">
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/store/products/{{ $product->id }}/edit" class="">{{ trans('public.edit') }}</a>
                        </li>

                        @if($product->creator_id == $authUser->id)
                            <li class="actions-dropdown__dropdown-menu-item">
                                @include('design_1.panel.includes.content_delete_btn', [
                                    'deleteContentUrl' => "/panel/store/products/{$product->id}/delete",
                                    'deleteContentClassName' => 'text-danger',
                                    'deleteContentItem' => $product,
                                    'deleteContentItemType' => "product",
                                ])
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

        </div>


        <div class="d-grid grid-columns-2 gap-12 my-12 py-12 px-4 rounded-8 border-gray-200">
            <div class="d-flex align-items-center font-12 text-gray-500">
                <x-iconsax-lin-profile class="icons text-gray-400" width="20px" height="20px"/>
                <span class="ml-4 font-weight-bold">
                @if(!empty($product->sales()) and count($product->sales()))
                        {{ $product->salesCount() }}
                    @else
                        0
                    @endif
                </span>
                <span class="ml-4">{{ trans('update.customers') }}</span>
            </div>

            <div class="d-flex align-items-center font-12 text-gray-500">
                <x-iconsax-lin-moneys class="icons text-gray-400" width="20px" height="20px"/>
                <span class="ml-4 font-weight-bold">
                    @if(!empty($product->sales()) and count($product->sales()))
                        {{ handlePrice($product->sales()->sum('total_amount')) }}
                    @else
                        0
                    @endif
                </span>
                <span class="ml-4">{{ trans('panel.sales') }}</span>
            </div>

            <div class="d-flex align-items-center font-12 text-gray-500">
                <x-iconsax-lin-note-2 class="icons text-gray-400" width="20px" height="20px"/>
                <div class="ml-4 font-weight-bold">
                    @if($product->unlimited_inventory)
                        {{ trans('update.unlimited') }}
                    @else
                        {{ $product->getAvailability() }}
                    @endif
                </div>
                <span class="ml-4">{{ trans('public.available') }}</span>
            </div>

            <div class="d-flex align-items-center font-12 text-gray-500">
                <x-iconsax-lin-bag class="icons text-gray-400" width="20px" height="20px"/>
                <span class="ml-4 font-weight-bold">{{ $product->productOrders->whereIn('status',[\App\Models\ProductOrder::$waitingDelivery,\App\Models\ProductOrder::$shipped])->count() }}</span>
                <span class="ml-4">{{ trans('update.waiting_orders') }}</span>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-auto">
            @include("design_1.web.components.rate", [
                'rate' => round($product->getRate(),1),
                'rateCount' => $product->reviews()->where('status', 'active')->count(),
                'rateClassName' => '',
            ])

            <div class="d-flex align-items-center font-16 font-weight-bold text-success">
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
</div>
