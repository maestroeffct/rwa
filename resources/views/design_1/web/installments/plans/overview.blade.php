<div class="installment-overview position-relative">
    <div class="installment-overview__mask"></div>

    <div class="position-relative bg-primary p-4 rounded-16 z-index-2">
        <div class="px-4 py-8">
            <h4 class="font-14 font-weight-bold text-white">{{ trans('update.installment_plans_overview') }}</h4>
        </div>

        <div class="position-relative bg-white rounded-12 p-16 mt-8 ">

            <div class="d-flex align-items-center">
                <div class="position-relative">
                    <div class="installment-overview__cup-icon-mask"></div>
                    <div class="position-relative d-flex-center size-40 rounded-circle bg-primary z-index-2">
                        <x-iconsax-bul-moneys class="icons text-white" width="24px" height="24px"/>
                    </div>
                </div>
                <div class="ml-16">
                    <h5 class="font-14 font-weight-bold">{{ $overviewTitle }}</h5>
                    <div class="font-12 text-gray-500 mt-4">{{ trans("update.{$itemType}") }}</div>
                </div>
            </div>

            <div class="d-flex align-items-center flex-wrap gap-64 gap-lg-120 mt-24 pt-16 border-top-gray-200">

                {{-- upfront_amount --}}
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-cards class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.cash_price') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ handlePrice($cash) }}</span>
                    </div>
                </div>

                {{-- installments --}}
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-moneys class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.installment_plans') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ $plansCount }}</span>
                    </div>
                </div>

                {{-- total_amount --}}
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-dollar-circle class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.minimum_upfront') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ handlePrice($minimumUpfront) }}</span>
                    </div>
                </div>

                {{-- duration --}}
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-money-recive class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.minimum_installment_amount') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ handlePrice($minimumAmount) }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
