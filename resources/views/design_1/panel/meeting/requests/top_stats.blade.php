  <div class="row">

    <div class="col-6 col-md-3">
            <div class="bg-white p-16 rounded-24">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('panel.total_meetings') }}</span>
                    <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                    <x-iconsax-bul-calendar class="icons text-primary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1">{{ $totalReserveCount }}</h5>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="bg-white p-16 rounded-24">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('panel.pending_appointments') }}</span>
                    <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                    <x-iconsax-bul-calendar class="icons text-warning" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1">{{ $pendingReserveCount }}</h5>
            </div>
        </div>

        <div class="col-6 col-md-3 mt-16 mt-md-0">
            <div class="bg-white p-16 rounded-24">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('panel.active_hours') }}</span>
                    <div class="size-48 d-flex-center bg-success-30 rounded-12">
                        <x-iconsax-bul-clock class="icons text-success" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1">{{ convertMinutesToHourAndMinute($activeHoursCount / 60) }}</h5>
            </div>
        </div>

        <div class="col-6 col-md-3 mt-16 mt-md-0">
            <div class="bg-white p-16 rounded-24">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('panel.sales_amount') }}</span>
                    <div class="size-48 d-flex-center bg-secondary-30 rounded-12">
                    <x-iconsax-bul-dollar-square class="icons text-secondary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1">{{ handlePrice($sumReservePaid) }}</h5>
            </div>
        </div>


    </div>
