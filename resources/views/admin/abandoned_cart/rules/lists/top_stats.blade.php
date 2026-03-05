<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{trans('update.total_rules')}}</span>
                    <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                        <x-iconsax-bul-bag-timer class="icons text-primary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalRules }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{trans('update.active_rules')}}</span>
                    <div class="d-flex-center size-48 bg-success-30 rounded-12">
                        <x-iconsax-bul-bag-timer class="icons text-success" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $activeRules }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{trans('update.total_activities')}}</span>
                    <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                        <x-iconsax-bul-refresh-circle class="icons text-accent" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalActivities }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{trans('admin/main.total_sales')}}</span>
                    <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                        <x-iconsax-bul-bag class="icons text-secondary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalSales }}</h5>
            </div>
        </div>
    </div>
</div>
