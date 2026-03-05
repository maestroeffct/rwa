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

                <div class="d-none d-lg-flex align-items-center gap-20 mt-16 ">
                    <div class="d-flex align-items-center">
                        <x-iconsax-lin-profile class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-12 text-gray-500 ml-4">{{ $cartItemInfo['teacherName'] }}</span>
                    </div>

                    <div class="d-flex align-items-center">
                        <x-iconsax-lin-global class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-12 text-gray-500 ml-4">{{ getTimezone() }}</span>
                    </div>

                    @if($cartItemInfo['eventItem']->type == "in_person")
                        <div class="d-flex align-items-center">
                            <x-iconsax-lin-location class="icons text-gray-500" width="16px" height="16px"/>

                            @if(!empty($cartItemInfo['eventItem']->specificLocation))
                                @php
                                    $specificLocationTitle = $cartItemInfo['eventItem']->specificLocation->getFullAddress(false, false, true, false, false)
                                @endphp

                                <span class="font-12 text-gray-500 ml-4">{{ !empty($specificLocationTitle) ? $specificLocationTitle : trans('update.in_person') }}</span>
                            @else
                                <span class="font-12 text-gray-500 ml-4">{{ trans('update.in_person') }}</span>
                            @endif
                        </div>
                    @else
                        <div class="d-flex align-items-center">
                            <x-iconsax-lin-monitor class="icons text-gray-500" width="16px" height="16px"/>
                            <span class="font-12 text-gray-500 ml-4">{{ trans('update.online') }}</span>
                        </div>
                    @endif

                    <div class="d-flex align-items-center">
                        <x-iconsax-lin-ticket class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-12 text-gray-500 ml-4">{{ $cartItemInfo['ticketTitle'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex d-lg-none flex-wrap gap-12 mt-16 ">
            <div class="d-flex align-items-center">
                <x-iconsax-lin-profile class="icons text-gray-500" width="16px" height="16px"/>
                <span class="font-12 text-gray-500 ml-4">{{ $cartItemInfo['teacherName'] }}</span>
            </div>

            <div class="d-flex align-items-center">
                <x-iconsax-lin-global class="icons text-gray-500" width="16px" height="16px"/>
                <span class="font-12 text-gray-500 ml-4">{{ getTimezone() }}</span>
            </div>

            @if($cartItemInfo['eventItem']->type == "in_person")
                <div class="d-flex align-items-center">
                    <x-iconsax-lin-location class="icons text-gray-500" width="16px" height="16px"/>

                    @if(!empty($cartItemInfo['eventItem']->specificLocation))
                        @php
                            $specificLocationTitle = $cartItemInfo['eventItem']->specificLocation->getFullAddress(false, false, true, false, false)
                        @endphp

                        <span class="font-12 text-gray-500 ml-4">{{ !empty($specificLocationTitle) ? $specificLocationTitle : trans('update.in_person') }}</span>
                    @else
                        <span class="font-12 text-gray-500 ml-4">{{ trans('update.in_person') }}</span>
                    @endif
                </div>
            @else
                <div class="d-flex align-items-center">
                    <x-iconsax-lin-monitor class="icons text-gray-500" width="16px" height="16px"/>
                    <span class="font-12 text-gray-500 ml-4">{{ trans('update.online') }}</span>
                </div>
            @endif

            <div class="d-flex align-items-center">
                <x-iconsax-lin-ticket class="icons text-gray-500" width="16px" height="16px"/>
                <span class="font-12 text-gray-500 ml-4">{{ $cartItemInfo['ticketTitle'] }}</span>
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
