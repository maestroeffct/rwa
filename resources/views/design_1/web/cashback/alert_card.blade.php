@if(!empty($cashbackRules) and count($cashbackRules) and !empty($itemPrice) and $itemPrice > 0)
    @foreach($cashbackRules as $cashbackRule)
        <div class="card-with-mask position-relative {{ !empty($cashbackRulesCardClassName) ? $cashbackRulesCardClassName : '' }}">
            <div class="mask-8-white z-index-1"></div>
            <div class="position-relative d-flex align-items-center bg-white p-16 rounded-16 z-index-2 w-100 h-100">
                <div class="d-flex-center size-56 rounded-circle bg-success-20">
                    <div class="d-flex-center size-40 rounded-circle bg-success">
                        <x-iconsax-bul-empty-wallet-change class="icons text-white" width="24px" height="24px"/>
                    </div>
                </div>
                <div class="ml-8">
                    <h6 class="font-14 font-weight-bold text-dark">{{ trans('update.get_cashback') }}</h6>

                    @if(!empty($itemType) and $itemType == 'meeting')
                        @if($cashbackRule->min_amount)
                            <div class="mt-4 font-12 text-gray-500">{{ trans('update.by_reserving_a_this_meeting_you_will_get_amount_as_cashback_for_orders_more_than_min_amount',['amount' => handlePrice($cashbackRule->getAmount($itemPrice)), 'min_amount' => handlePrice($cashbackRule->min_amount)]) }}</div>
                        @else
                            <div class="mt-4 font-12 text-gray-500">{{ trans('update.by_reserving_a_this_meeting_you_will_get_amount_as_cashback',['amount' => handlePrice($cashbackRule->getAmount($itemPrice))]) }}</div>
                        @endif
                    @else
                        @if($cashbackRule->min_amount)
                            <div class="mt-4 font-12 text-gray-500">{{ trans('update.by_purchasing_this_product_you_will_get_amount_as_cashback_for_orders_more_than_min_amount',['amount' => handlePrice($cashbackRule->getAmount($itemPrice)), 'min_amount' => handlePrice($cashbackRule->min_amount)]) }}</div>
                        @else
                            <div class="mt-4 font-12 text-gray-500">{{ trans('update.by_purchasing_this_product_you_will_get_amount_as_cashback',['amount' => handlePrice($cashbackRule->getAmount($itemPrice))]) }}</div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endif
