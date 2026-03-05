<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.total_sold_packages') }}</span>
                    <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                        <x-iconsax-bul-box-time class="icons text-primary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalSoldPackages }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.sales_amount') }}</span>
                    <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                        <x-iconsax-bul-message-square class="icons text-secomndary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ handlePrice($totalSalesAmount) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.open_packages') }}</span>
                    <div class="d-flex-center size-48 bg-success-30 rounded-12">
                        <x-iconsax-bul-message-edit class="icons text-success" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalOpenPackages }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.finished_packages') }}</span>
                    <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                        <x-iconsax-bul-moneys class="icons text-accent" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalFinishedPackages }}</h5>
            </div>
        </div>
    </div>
</div>
