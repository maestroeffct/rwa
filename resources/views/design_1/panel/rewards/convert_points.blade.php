<div class="reward-your-points-card bg-primary rounded-24 p-16">
    <h3 class="font-16 font-weight-bold text-white">{{ trans('update.convert_your_points') }}</h3>

    <div class="row">
        <div class="col-12 col-md-5 mt-16">
            <div class="reward-your-points-cash-img">
                <img src="/assets/design_1/img/panel/reward/cash.png" alt="cash" class="img-cover">
            </div>
        </div>

        <div class="col-12 col-md-7 mt-16">
            <p class="font-14 text-white text-center">{{ trans('update.you_can_convert_your_earned_points_to_the_wallet_charge_or_get_free_courses_by_spending_points') }}</p>

            <div class="reward-your-points-exchange-card d-flex align-items-center justify-content-around mt-32 py-16 px-32 rounded-16 bg-white soft-shadow-2">
                <div class="text-center">
                    <span class="d-block font-24 font-weight-bold">{{ $availablePoints }}</span>
                    <span class="d-block font-12 text-gray-500 mt-2">{{ trans('update.points') }}</span>
                </div>

                <div class="d-flex-center size-40 rounded-circle border-gray-200 mx-20 mx-md-32">
                    <x-iconsax-lin-repeat class="icons text-primary" width="20px" height="20px"/>
                </div>

                <div class="text-center">
                    <span class="d-block font-24 font-weight-bold">{{ handlePrice($earnByExchange) }}</span>
                    <span class="d-block font-12 text-gray-500 mt-2">{{ trans('update.wallet_charge') }}</span>
                </div>
            </div>

            <div class="d-flex-center mt-20">

                @if(!empty($rewardsSettings) and !empty($rewardsSettings['exchangeable']) and $rewardsSettings['exchangeable'] == '1')
                    <div class="d-flex align-items-center cursor-pointer {{ ($earnByExchange > 0) ? 'js-exchange-btn' : '' }}">
                        <x-iconsax-lin-refresh-2 class="icons {{ ($earnByExchange > 0) ? 'text-white' : 'text-gray-500' }}" width="16px" height="16px"/>
                        <span class="ml-2 font-14 font-weight-bold {{ ($earnByExchange > 0) ? 'text-white' : 'text-gray-500' }}">{{ trans('update.convert_points') }}</span>
                    </div>
                @endif

                <a href="/reward-courses" target="_blank" class="d-flex align-items-center ml-24">
                    <x-iconsax-lin-gift class="icons text-white" width="16px" height="16px"/>
                    <span class="ml-2 font-14 font-weight-bold text-white">{{ trans('update.explore_gifts') }}</span>
                </a>

            </div>
        </div>
    </div>
</div>
