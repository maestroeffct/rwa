<div class="row">
    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('panel.purchased') }}</span>
                <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                    <x-iconsax-bul-video-play class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $totalPurchasedCount }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('home.hours') }}</span>
                <div class="size-48 d-flex-center bg-success-30 rounded-12">
                    <x-iconsax-bul-clock class="icons text-success" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ convertMinutesToHourAndMinute($totalCoursesHours) }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('panel.upcoming') }}</span>
                <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                    <x-iconsax-bul-video-time class="icons text-warning" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $totalUpcomingCount }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.total_purchased_amount') }}</span>

                <div class="size-48 d-flex-center bg-secondary-30 rounded-12">
                    <x-iconsax-bul-dollar-square class="icons text-secondary" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ handlePrice($totalPurchasedAmount) }}</h5>
        </div>
    </div>
</div>
