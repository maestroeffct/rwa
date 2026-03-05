  <div class="row">

    <div class="col-6 col-md-3">
            <div class="bg-white p-16 rounded-24">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.total_purchased_packages') }}</span>
                    <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                    <x-iconsax-bul-box-time class="icons text-primary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1">{{ $totalPurchasedPackages }}</h5>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="bg-white p-16 rounded-24">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.total_duration') }}</span>
                    <div class="size-48 d-flex-center bg-success-30 rounded-12">
                    <x-iconsax-bul-moneys class="icons text-success" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1">{{ $totalDurations }}</h5>
            </div>
        </div>

        <div class="col-6 col-md-3 mt-16 mt-md-0">
            <div class="bg-white p-16 rounded-24">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.open_packages') }}</span>
                    <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                        <x-iconsax-bul-more-circle class="icons text-warning" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1">{{ $totalOpenPackages }}</h5>
            </div>
        </div>

        <div class="col-6 col-md-3 mt-16 mt-md-0">
            <div class="bg-white p-16 rounded-24">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.finished_packages') }}</span>
                    <div class="size-48 d-flex-center bg-secondary-30 rounded-12">
                    <x-iconsax-bul-tick-circle class="icons text-secondary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1">{{ $totalFinishedPackages }}</h5>
            </div>
        </div>


    </div>
