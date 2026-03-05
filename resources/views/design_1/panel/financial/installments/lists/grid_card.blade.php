@php
    $orderItem = $order->getItem();
    $itemType = $order->getItemType();
    $itemPrice = $order->getItemPrice();
@endphp

<div class="installment-grid-card position-relative">
    <div class="installment-grid-card__image position-relative rounded-16 bg-gray-100">

        @if(in_array($itemType, ['course', 'bundle']))
            <img src="{{ $orderItem->getImage() }}" class="img-cover rounded-16" alt="">
        @elseif($itemType == 'product')
            <img src="{{ $orderItem->thumbnail }}" class="img-cover rounded-16" alt="">
        @elseif($itemType == "subscribe")
            <div class="d-flex-center w-100 h-100">
                <img src="/assets/default/img/icons/installment/subscribe_default.svg" alt="">
            </div>
        @elseif($itemType == "registrationPackage")
            <div class="d-flex-center w-100 h-100">
                <img src="/assets/default/img/icons/installment/reg_package_default.svg" alt="">
            </div>
        @endif

        <div class="installment-grid-card__actions-box d-flex align-items-start justify-content-between">
            {{-- Badges --}}
            @include("design_1.panel.financial.installments.lists.grid_card.grid_card_badges")

            {{-- Actions --}}
            @if(!in_array($order->status, ['refunded', 'canceled']) or $order->isCompleted())
                @include("design_1.panel.financial.installments.lists.grid_card.grid_card_actions")
            @endif
        </div>

        @if($itemType == "course" and $orderItem->isWebinar())
            <div class="is-live-installment-icon d-flex-center size-64 rounded-circle">
                <x-iconsax-bol-video-vertical class="icons text-white" width="24px" height="24px"/>
            </div>
        @endif
    </div>

    <div class="installment-grid-card__body position-relative px-16 pb-12">
        <div class="installment-grid-card__content d-flex flex-column bg-white p-12 rounded-16">

            <h3 class="installment-grid-card__title font-14 text-dark">{{ $orderItem->title }}</h3>


            <div class="d-grid grid-columns-2 gap-20 mt-12 p-16 rounded-8 border-gray-200 mb-16">

                <div class="d-flex align-items-center font-12 text-gray-500">
                    <x-iconsax-lin-card-tick class="icons text-gray-400" width="20px" height="20px"/>
                    <span class="ml-4 font-weight-bold">{{ !empty($order->selectedInstallment->upfront) ? handlePrice($order->selectedInstallment->getUpfront($itemPrice)) : '-' }}</span>
                    <span class="ml-4">{{ trans('update.upfront') }}</span>
                </div>


                <div class="d-flex align-items-center font-12 text-gray-500">
                    <x-iconsax-lin-cards class="icons text-gray-400" width="20px" height="20px"/>
                    <span class="ml-4 font-weight-bold">{{ handlePrice($order->remained_installments_amount) }}</span>
                    <span class="ml-4">{{ trans('update.remained') }}</span>
                </div>

                <div class="d-flex align-items-center font-12 text-gray-500">
                    <x-iconsax-lin-moneys class="icons text-gray-400" width="20px" height="20px"/>
                    <span class="ml-4 font-weight-bold">{{ $order->selectedInstallment->steps_count }}</span>
                    <span class="ml-4">{{ trans('update.total_inst.') }}</span>
                </div>

                <div class="d-flex align-items-center font-12 text-gray-500">
                    <x-iconsax-lin-money-time class="icons text-gray-400" width="20px" height="20px"/>
                    <span class="ml-4 font-weight-bold">{{ $order->remained_installments_count }}</span>
                    <span class="ml-4">{{ trans('update.remained_inst.') }}</span>
                </div>


                <div class="d-flex align-items-center font-12 text-gray-500">
                    <x-iconsax-lin-money-3 class="icons text-gray-400" width="20px" height="20px"/>
                    <span class="ml-4 font-weight-bold">{{ (!empty($order->upcoming_installment)) ? handlePrice($order->upcoming_installment->getPrice($itemPrice)) : trans('public.no') }}</span>
                    <span class="ml-4">{{ trans('update.upcoming') }}</span>
                </div>


                <div class="d-flex align-items-center font-12 text-gray-500">
                    <x-iconsax-lin-money-remove class="icons text-gray-400" width="20px" height="20px"/>
                    <span class="ml-4 font-weight-bold">{{ $order->overdue_count }}</span>
                    <span class="ml-4">{{ trans('update.overdue_inst.') }}</span>
                </div>

            </div>


            <div class="d-flex align-items-center justify-content-between mt-auto">

                {{-- Chart --}}
                @include("design_1.panel.financial.installments.lists.grid_card.grid_card_progress_chart")

                <div class="d-flex align-items-center font-16 font-weight-bold text-success">
                    {{ handlePrice($order->getCompletePrice()) }}
                </div>
            </div>
        </div>
    </div>

</div>
