<div class="card-with-mask {{ !empty($className) ? $className : '' }}">
    <div class="mask-8-white z-index-1 border-dashed border-gray-300"></div>
    <div class="position-relative z-index-2 bg-white p-8 rounded-16 border-gray-200 w-100 h-100">
        <div class="d-flex">
            <div class="cart-drawer__item-image d-flex-center rounded-8 bg-gray-100">
                <div class="size-52 rounded-circle border-4 border-gray-200">
                    <img src="{{ $cartItemInfo['imgPath'] }}" class="img-cover rounded-circle" alt="{{ $cartItemInfo['title'] }}">
                </div>
            </div>
            <div class="ml-8">
                <a href="{{ $cartItemInfo['profileUrl'] }}" target="_blank">
                    <h6 class="font-12 text-dark">{{ trans('update.meeting_with_“user”', ['userName' => $cartItemInfo['teacherName']]) }}</h6>
                </a>

                @if(!is_null($cartItemInfo['rate']))
                    @include('design_1.web.components.rate', [
                         'rate' => $cartItemInfo['rate'],
                         'rateCount' => $cartItemInfo['rateCount'],
                         'rateClassName' => 'mt-8'
                     ])
                @endif

                <div class="d-flex align-items-center mt-16 ">
                    <x-iconsax-lin-clock-1 class="icons text-gray-500" width="16px" height="16px"/>
                    <span class="font-12 text-gray-500 ml-4">{{ $cartItem->reserveMeeting->day .' '. dateTimeFormat($cartItem->reserveMeeting->start_at,'h:iA',false).'-'.dateTimeFormat($cartItem->reserveMeeting->end_at,'h:iA',false) }}</span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-8 rounded-12 bg-gray-100 p-16">
            <div class="">
                @if(!empty($cartItemInfo['discountPrice']))
                    <div class="d-flex flex-column">
                        <span class="font-16 font-weight-bold text-primary">{{ handlePrice($cartItemInfo['discountPrice'], true, true, false, null, true, $cartTaxType) }}</span>
                        <span class="text-decoration-line-through font-12 text-gray-500 mt-4">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</span>
                    </div>
                @else
                    <span class="font-16 font-weight-bold text-primary">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</span>
                @endif
            </div>

            <a href="/cart/{{ $cartItem->getId() }}/delete" class="delete-action">
                <x-iconsax-bol-close-circle class="icons text-danger" width="24px" height="24px"/>
            </a>
        </div>
    </div>
</div>
