<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.total_landing_pages') }}</span>
                    <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                        <x-iconsax-bul-colorfilter class="icons text-primary" width="24px" height="24px"/>
                    </div>
                </div>

                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalLandingPages }}</h5>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.active_landing_pages') }}</span>
                    <div class="d-flex-center size-48 bg-success-30 rounded-12">
                        <x-iconsax-bul-colorfilter class="icons text-success" width="24px" height="24px"/>
                    </div>
                </div>

                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $activeLandingPages }}</h5>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.disabled_landing_pages') }}</span>
                    <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                        <x-iconsax-bul-colorfilter class="icons text-danger" width="24px" height="24px"/>
                    </div>
                </div>

                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $disabledLandingPages }}</h5>
            </div>
        </div>
    </div> 

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.landing_components') }}</span>
                    <div class="d-flex-center size-48 bg-info-30 rounded-12">
                        <x-iconsax-bul-category class="icons text-info" width="24px" height="24px"/>
                    </div>
                </div>

                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalLandingComponents }}</h5>
            </div>
        </div>
    </div>
</div>
