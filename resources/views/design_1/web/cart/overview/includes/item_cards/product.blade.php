<div class="card-with-mask mb-20 {{ !empty($className) ? $className : '' }}">
    <div class="mask-8-white z-index-1 border-dashed border-gray-300"></div>

    <div class="position-relative z-index-2 d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white p-8 rounded-16 border-gray-200 w-100 h-100">
        <div class="d-flex">
            <div class="cart-item__image position-relative rounded-8 bg-gray-200">
                <img src="{{ $cartItemInfo['imgPath'] }}" class="img-cover rounded-8" alt="{{ $cartItemInfo['title'] }}">
            </div>

            <div class="ml-8">
                <a href="{{ $cartItemInfo['itemUrl'] ?? '#!' }}" target="_blank">
                    <h6 class="font-12 text-dark">{{ $cartItemInfo['title'] }}</h6>
                </a>

                @if(!is_null($cartItemInfo['rate']))
                    @include('design_1.web.components.rate', [
                         'rate' => $cartItemInfo['rate'],
                         'rateCount' => $cartItemInfo['rateCount'],
                         'rateClassName' => 'mt-8'
                     ])
                @endif

                <div class="d-flex align-items-center mt-16 gap-12 gap-lg-20">
                    <a href="{{ $cartItemInfo['profileUrl'] }}" target="_blank" class="d-flex align-items-center">
                        <x-iconsax-lin-profile class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4 font-12 text-gray-500">{{ $cartItemInfo['teacherName'] }}</span>
                    </a>

                    <div class="d-flex align-items-center">
                        @if($cartItemInfo['productType'] == "physical")
                            <x-iconsax-lin-box-1 class="icons text-gray-500" width="16px" height="16px"/>
                        @else
                            <x-iconsax-lin-frame-2 class="icons text-gray-500" width="16px" height="16px"/>
                        @endif

                        <span class="font-12 text-gray-500 ml-4">{{ trans("update.{$cartItemInfo['productType']}") }}</span>
                    </div>

                </div>
            </div>
        </div>

        <div class="js-cart-quantity d-flex align-items-center justify-content-between mt-16 mt-lg-0" data-path="/cart/{{ $cartItem->getId() }}/quantity">

            <div class="cart-item__quantity-card d-flex align-items-center mr-32 mr-lg-64">
                <input type="hidden" class="js-product-availability-count" value="{{ !empty($cartItemInfo['productAvailabilityCount']) ? $cartItemInfo['productAvailabilityCount'] : 1 }}">

                <button type="button" class="js-cart-drawer-item-quantity-btn cart-item__quantity-btn d-flex-center border-0 bg-gray-100 rounded-8 bg-hover-gray-200"
                        data-operation="minus"
                >
                    <x-iconsax-lin-minus class="icons text-gray-500" width="16px" height="16px"/>
                </button>

                <input type="number" name="quantity" class="d-flex-center text-center flex-1 bg-transparent border-0 font-14 font-weight-bold w-100 disable-input-inner-spin-button" value="{{ $cartItemInfo['quantity'] }}">

                <button type="button" class="js-cart-drawer-item-quantity-btn cart-item__quantity-btn d-flex-center border-0 bg-gray-100 rounded-8 bg-hover-gray-200"
                        data-operation="add"
                >
                    <x-iconsax-lin-add class="icons text-gray-500" width="16px" height="16px"/>
                </button>
            </div>

            <div class="d-flex align-items-center">
                <div class="js-cart-item-price-div mr-56 mr-lg-72">
                    <div class="js-cart-price-loading-card d-none">
                        <img src="/assets/default/img/loading.gif" width="32px" height="32px"/>
                    </div>

                    <div class="js-cart-price-card">
                        @if(!empty($cartItemInfo['discountPrice']))
                            <div class="d-flex flex-column">
                                <span class="font-16 font-weight-bold text-primary">{{ handlePrice(($cartItemInfo['discountPrice'] * $cartItemInfo['quantity']), true, true, false, null, true, $cartTaxType) }}</span>
                                <span class="text-decoration-line-through font-12 text-gray-500 mt-4">{{ handlePrice(($cartItemInfo['price'] * $cartItemInfo['quantity']), true, true, false, null, true, $cartTaxType) }}</span>
                            </div>
                        @else
                            <span class="font-16 font-weight-bold text-primary">{{ handlePrice(($cartItemInfo['price'] * $cartItemInfo['quantity']), true, true, false, null, true, $cartTaxType) }}</span>
                        @endif
                    </div>
                </div>

                <a href="/cart/{{ $cartItem->getId() }}/delete" class="delete-action d-flex-center size-40 rounded-circle bg-gray-100 bg-hover-gray-200">
                    <x-iconsax-lin-close-circle class="icons text-danger" width="24px" height="24px"/>
                </a>
            </div>

        </div>
    </div>
</div>
