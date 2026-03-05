<a href="/panel/financial/payout" class="d-block">
<div class="bg-white p-16 rounded-24 mt-24">
    <div class="card-with-mask position-relative">
        <div class="dashboard-current-balance-box__mask"></div>

        <div class="position-relative dashboard-current-balance-box rounded-16 z-index-2">
            <div class="d-flex flex-column pt-16 pb-24 px-16 text-white">
                <span class="font-16 font-weight-bold">{{ trans('update.current_balance') }}</span>
                <span class="font-12 opacity-75">{{ dateTimeFormat(time(), 'j M Y H:i') }}</span>
                <span class="mt-24 font-44 font-weight-bold">{{ !empty($authUserBalanceCharge) ? handlePrice($authUserBalanceCharge) : trans('update.no_balance') }}</span>
            </div>

            <div class="dashboard-current-balance-box__footer p-16">
                <span class="font-12 text-white opacity-75">
                    @if(!empty($authUserReadyPayout))
                        {{ trans('update.amount_ready_to_payout', ['amount' => handlePrice($authUserReadyPayout)]) }}
                    @else
                        {{ trans('update.no_balance') }}
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mt-32">
        <div class="">
            <span class="d-block font-16 font-weight-bold text-dark">{{ trans('update.wallet') }}</span>
            <span class="d-block font-12 text-gray-500 mt-4">{{ trans('update.manage_your_balance') }}</span>
        </div>

        <a href="/panel/financial/payout" target="_blank" class="d-flex-center size-40 rounded-circle border-gray-200 bg-hover-gray-100">
            <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
        </a>
    </div>
</div>
</a>
