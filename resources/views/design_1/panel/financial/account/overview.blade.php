<div class="row">
    <div class="col-12 col-lg-6">
        <div class="bg-white p-16 rounded-24">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <h5 class="font-16 font-weight-bold">{{ trans('update.your_balance') }}</h5>

                    <ul class="mt-8">
                        <li class="text-gray-500">Payouts will be processed on 15th of each month</li>
                        <li class="text-gray-500">Minimum payout amount is $500</li>
                        <li class="text-gray-500">Holder name will be checked</li>
                    </ul>

                    <div class="d-flex align-items-center mt-72">
                        <a href="/panel/financial/summary" class="btn btn-primary">
                            {{ trans('update.view_transactions') }}
                        </a>

                        <a href="/panel/financial/payout" class="btn bg-gray-100 text-gray-500 ml-8">{{ trans('financial.payout') }}</a>
                    </div>
                </div>

                <div class="col-12 col-lg-6 mt-16 mt-lg-0">
                    <div class="position-relative">
                        <div class="payout-bank-card-mask"></div>
                        <div class="payout-bank-card d-flex flex-column w-100 z-index-2">
                            <img src="/assets/design_1/img/panel/payout/circle-left-top.svg" alt="" class="circle-left-top">
                            <img src="/assets/design_1/img/panel/payout/circle-bottom-right.svg" alt="" class="circle-bottom-right">
                            <img src="/assets/design_1/img/panel/payout/logo_mask.svg" alt="" class="logo-mask-right">

                            <div class="first-rectangle"></div>
                            <div class="second-rectangle"></div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="">
                                    <span class="d-block font-16 font-weight-bold text-white">{{ trans('update.current_balance') }}</span>
                                    <span class="d-block font-12 text-white">{{ dateTimeFormat(time(), 'j M Y H:i') }}</span>
                                </div>

                                <div class="position-relative z-index-3 d-flex-center size-48 rounded-12 bg-white">
                                    <x-iconsax-bol-empty-wallet-tick class="icons text-accent" width="24px" height="24px"/>
                                </div>
                            </div>

                            <div class="font-44 font-weight-bold text-white mt-24 mb-8">{{ $accountCharge ? handlePrice($accountCharge) : 0 }}</div>

                            <div class="mt-auto">
                                <span class="font-12 text-white">{{ $readyPayout ? handlePrice($readyPayout) : 0 }} {{ trans('update.ready_to_payout') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 mt-16 mt-lg-0">
        <div class="bg-white p-16 rounded-24">
            <div class="pb-10 border-bottom-gray-100">
                <h4 class="font-16 font-weight-bold">{{ trans('update.account_overview') }}</h4>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-8">
                <div class="">

                    <div class="">
                        <span class="d-block text-gray-500">{{ trans('financial.total_income') }}</span>
                        <span class="d-block font-16 font-weight-bold mt-4">{{ $totalIncome ? handlePrice($totalIncome) : 0 }}</span>
                    </div>

                    <div class="mt-16">
                        <span class="d-block text-gray-500">{{ trans('update.locked_amount') }}</span>
                        <span class="d-block font-16 font-weight-bold mt-4">-</span>
                    </div>

                    <div class="mt-16">
                        <span class="d-block text-gray-500">{{ trans('update.pending_offline_payments') }}</span>
                        <span class="d-block font-16 font-weight-bold mt-4">-</span>
                    </div>

                </div>

                <div class="">
                    <img src="/assets/design_1/img/panel/payout/payout-statistics-icon.svg" alt="" class="" width="120px" height="120px">
                </div>
            </div>
        </div>
    </div>
</div>
