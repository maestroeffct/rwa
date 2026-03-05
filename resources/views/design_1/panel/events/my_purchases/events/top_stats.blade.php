<div class="row">
    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.total_events') }}</span>
                <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                    <x-iconsax-bul-video-play class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $totalEventsCount }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.conducted_events') }}</span>
                <div class="size-48 d-flex-center bg-success-30 rounded-12">
                    <x-iconsax-bul-clock class="icons text-success" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $totalEndedEvents }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-lg-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.open_events') }}</span>
                <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                    <x-iconsax-bul-bag class="icons text-warning" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $totalOpenEvents }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-lg-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.events_duration') }}</span>
                <div class="size-48 d-flex-center bg-secondary-30 rounded-12">
                    <x-iconsax-bul-bag class="icons text-secondary" width="24px" height="24px"/>
                </div>
            </div>

            <div class="d-flex align-items-end gap-4">
                <h5 class="font-24 mt-12 line-height-1">{{ convertMinutesToHourAndMinute($totalDurationEvents) }}</h5>
                <span class="font-12 text-gray-500">{{ trans('home.hours') }}</span>
            </div>
        </div>
    </div>
</div>
