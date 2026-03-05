<div class="row">
    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.total_orders') }}</span>
                <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                    <x-iconsax-bul-bag-2 class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>
            <h5 class="font-24 mt-12 line-height-1">{{ $totalOrders }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.pending_orders') }}</span>
                <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                    <x-iconsax-bul-bag-timer class="icons text-warning" width="24px" height="24px"/>
                </div>
            </div>
            <h5 class="font-24 mt-12 line-height-1">{{ $pendingOrders }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.canceled_orders') }}</span>
                <div class="size-48 d-flex-center bg-danger-30 rounded-12">
                    <x-iconsax-bul-bag-cross class="icons text-danger" width="24px" height="24px"/>
                </div>
            </div>
            <h5 class="font-24 mt-12 line-height-1">{{ $canceledOrders }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('financial.total_sales') }}</span>
                <div class="size-48 d-flex-center bg-secondary-30 rounded-12">
                    <x-iconsax-bul-money-recive class="icons text-secondary" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ (!empty($totalSales) and $totalSales > 0) ? handlePrice($totalSales) : 0 }}</h5>
        </div>
    </div>
</div>
