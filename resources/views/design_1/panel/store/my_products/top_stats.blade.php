<div class="row">
    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.physical_products') }}</span>
                <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                    <x-iconsax-bul-box class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $totalPhysicalProducts }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.virtual_products') }}</span>
                <div class="size-48 d-flex-center bg-success-30 rounded-12">
                    <x-iconsax-bul-document-download class="icons text-success" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $totalVirtualProducts }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.physical_sales') }}</span>
                <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                    <x-iconsax-bul-bag-2 class="icons text-warning" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ handlePrice($totalPhysicalSales) }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.virtual_sales') }}</span>
                <div class="size-48 d-flex-center bg-info-30 rounded-12">
                    <x-iconsax-bul-bag-2 class="icons text-info" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ handlePrice($totalVirtualSales) }}</h5>
        </div>
    </div>
</div>
