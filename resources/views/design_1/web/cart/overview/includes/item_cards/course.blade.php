<div class="card-with-mask mb-20 {{ !empty($className) ? $className : '' }}">
    <div class="mask-8-white z-index-1 border-dashed border-gray-300"></div>

    <div class="position-relative z-index-2 d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white p-8 rounded-16 border-gray-200 w-100 h-100">
        <div class="d-flex">
            <div class="cart-item__image rounded-8 bg-gray-200">
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

                <a href="{{ $cartItemInfo['profileUrl'] }}" target="_blank" class="d-flex align-items-center mt-16">
                    <x-iconsax-lin-profile class="icons text-gray-500" width="16px" height="16px"/>
                    <span class="ml-4 font-12 text-gray-500">{{ $cartItemInfo['teacherName'] }}</span>
                </a>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between justify-content-lg-start mt-16 mt-lg-0">
            <div class="mr-0 mr-lg-72">
                @if(!empty($cartItemInfo['discountPrice']))
                    <div class="d-flex flex-column">
                        <span class="font-16 font-weight-bold text-primary">{{ handlePrice($cartItemInfo['discountPrice'], true, true, false, null, true, $cartTaxType) }}</span>
                        <span class="text-decoration-line-through font-12 text-gray-500 mt-4">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</span>
                    </div>
                @else
                    <span class="font-16 font-weight-bold text-primary">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</span>
                @endif
            </div>

            <a href="/cart/{{ $cartItem->getId() }}/delete" class="delete-action d-flex-center size-40 rounded-circle bg-gray-100 bg-hover-gray-200">
                <x-iconsax-lin-close-circle class="icons text-danger" width="24px" height="24px"/>
            </a>
        </div>
    </div>
</div>
