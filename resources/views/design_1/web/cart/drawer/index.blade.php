<div class="cart-drawer no-footer bg-white py-16">
    <div class="d-flex align-items-center pb-16 border-bottom-gray-bg px-16">
        <button type="button" class="js-cart-drawer-close d-flex btn-transparent">
            <x-iconsax-lin-arrow-right class="icons text-gray-500" width="25px" height="25px"/>
        </button>

        <span class="font-14 font-weight-bold ml-8">{{ trans("update.cart") }}</span>
    </div>

    <div class="cart-drawer__body pb-32" data-simplebar @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>

    </div>

    <div class="cart-drawer__footer pt-16 border-top-gray-bg d-none px-16">
        <div class="d-flex align-items-center justify-content-between">
            <span class="text-gray-500">{{ trans('update.subtotal') }}</span>
            <span class="js-side-cart-subtotal text-dark font-weight-bold"></span>
        </div>

        <div class="mt-12">
            <a href="/cart" class="btn btn-outline-primary btn-block">{{ trans('update.view_cart') }}</a>
        </div>
    </div>
</div>
<div class="cart-drawer-mask"></div>
