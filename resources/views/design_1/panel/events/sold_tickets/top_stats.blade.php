  <div class="row">

    <div class="col-6 col-md-3">
            <div class="bg-white p-16 rounded-24">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.total_participants') }}</span>
                    <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                    <x-iconsax-bul-profile-2user class="icons text-primary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1">{{ $totalParticipantsCount }}</h5>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="bg-white p-16 rounded-24">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.sold_tickets') }}</span>
                    <div class="size-48 d-flex-center bg-success-30 rounded-12">
                    <x-iconsax-bul-ticket class="icons text-success" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1">{{ $soldTicketsCount }}</h5>
            </div>
        </div>

        <div class="col-6 col-md-3 mt-16 mt-md-0">
            <div class="bg-white p-16 rounded-24">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.sales_amount') }}</span>
                    <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                        <x-iconsax-bul-moneys class="icons text-warning" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1">{{ handlePrice($totalSalesAmount) }}</h5>
            </div>
        </div>

        <div class="col-6 col-md-3 mt-16 mt-md-0">
            <div class="bg-white p-16 rounded-24">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{ trans('update.ticket_types') }}</span>
                    <div class="size-48 d-flex-center bg-secondary-30 rounded-12">
                    <x-iconsax-bul-dollar-square class="icons text-secondary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1">{{ $totalTicketTypes }}</h5>
            </div>
        </div>


    </div>
