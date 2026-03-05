<div class="card-with-mask position-relative mb-28">
    <div class="mask-8-white z-index-1"></div>
    <div class="position-relative d-flex align-items-center bg-white p-16 rounded-16 z-index-2 w-100 h-100">
        <div class="d-flex-center size-56 rounded-circle bg-warning-20">
            <div class="d-flex-center size-40 rounded-circle bg-warning">
                <x-iconsax-bul-receipt-disscount class="icons text-white" width="24px" height="24px"/>
            </div>
        </div>
        <div class="ml-8">
            <h6 class="font-14 font-weight-bold text-dark">{{ trans('update.user_group_discount') }}</h6>
            <div class="mt-4 font-12 text-gray-500">{{ trans('cart.in_user_group', ['group_name' => $userGroup->name , 'percent' => $userGroup->discount]) }}</div>
        </div>
    </div>
</div>
