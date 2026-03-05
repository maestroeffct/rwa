<div class="d-flex-center flex-column h-100 px-16">

    <div class="d-flex-center flex-column text-center">
        <img src="/assets/design_1/img/cart/drawer-empty.svg" alt="basket" width="292px" height="217px">

        <h4 class="font-16 mt-8">{{ trans('update.cart_is_empty') }}</h4>
        <p class="text-gray-500 mt-4">{{ trans('update.cart_is_empty_message') }}</p>

    </div>

    @if(!empty($cartDiscount))
        <div class="bg-gray-100 rounded-16 mt-32 p-16 pb-32">
            <h5 class="font-14">{{ $cartDiscount->title }}</h5>
            <div class="mt-4 font-12 text-gray-500">{{ $cartDiscount->subtitle }}</div>


            <div class="cart-drawer__discount-code form-group mb-8 d-flex-center mt-24 pt-40 w-100">
                <span class="font-16 font-weight-bold">{{ $cartDiscount->discount->code }}</span>

                <button type="button" class="js-copy-input btn-transparent d-flex ml-4" data-tippy-content="{{ trans('public.copy') }}" data-copy-text="{{ trans('public.copy') }}" data-done-text="{{ trans('public.copied') }}">
                    <x-iconsax-lin-document-copy class="icons text-gray-500" width="16px" height="16px"/>
                </button>

                <input type="hidden" value="{{ $cartDiscount->discount->code }}">
            </div>
        </div>
    @endif
</div>
