<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.total_events') }}</span>
                    <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                        <x-iconsax-bul-video-time class="icons text-primary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalEventsCount }}</h5>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.ended_events') }}</span>
                    <div class="d-flex-center size-48 bg-success-30 rounded-12">
                        <x-iconsax-bul-video-tick class="icons text-success" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalEndedEvents }}</h5>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.scheduled_events') }}</span>
                    <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                        <x-iconsax-bul-video-remove class="icons text-danger" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalScheduledEvents }}</h5>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.draft_events') }}</span>
                    <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                        <x-iconsax-bul-profile-2user class="icons text-secondary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalDraftEvents }}</h5>
            </div>
        </div>
    </div>
</div>
