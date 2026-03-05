<div class="card-with-mask position-relative mb-28">
    <div class="mask-8-white z-index-1"></div>
    <div class="position-relative d-flex align-items-center bg-white p-16 rounded-16 z-index-2 w-100 h-100">
        <div class="d-flex-center size-56 rounded-circle bg-success-20">
            <div class="d-flex-center size-40 rounded-circle bg-success">
                <x-iconsax-bul-empty-wallet-change class="icons text-white" width="24px" height="24px"/>
            </div>
        </div>
        <div class="ml-8">
            <h6 class="font-14 font-weight-bold text-dark">{{ trans('update.get_cashback') }}</h6>
            <div class="mt-4 font-12 text-gray-500">{{ trans('update.you_will_get_amount_as_cashback_by_finalizing_this_order_and_making_your_payment', ['amount' => handlePrice($totalCashbackAmount)]) }}</div>
        </div>
    </div>
</div>
