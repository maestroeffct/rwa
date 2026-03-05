@php
    $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
@endphp

@if($carts->whereNotNull('webinar_id')->count())
    <div class="card-before-line px-16">
        <h5 class="font-14 mb-16">{{ trans('update.courses') }}</h5>

        @foreach($carts->whereNotNull('webinar_id') as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.course', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach
    </div>
@endif

@if($carts->whereNotNull('bundle_id')->count())
    <div class="card-before-line px-16">
        <h5 class="font-14 mb-16">{{ trans('update.bundles') }}</h5>

        @foreach($carts->whereNotNull('bundle_id') as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.course', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach
    </div>
@endif

@if($carts->whereNotNull('reserve_meeting_id')->count() or $carts->whereNotNull('meeting_package_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14 mb-16">{{ trans('panel.meetings') }}</h5>

        @foreach($carts->whereNotNull('reserve_meeting_id') as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.meeting', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach

        @foreach($carts->whereNotNull('meeting_package_id') as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.meeting_package', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach
    </div>
@endif


@if($carts->whereNotNull('product_order_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14 mb-16">{{ trans('update.products') }}</h5>

        @foreach($carts->whereNotNull('product_order_id') as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.product', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach
    </div>
@endif

@if($carts->whereNotNull('event_ticket_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14 mb-16">{{ trans('update.event_tickets') }}</h5>

        @foreach($carts->whereNotNull('event_ticket_id') as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.event_ticket', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach
    </div>
@endif


@if($carts->whereNotNull('installment_payment_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14 mb-16">{{ trans('update.installment_upfront') }}</h5>

        @foreach($carts->whereNotNull('installment_payment_id') as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.installment_payment', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach
    </div>
@endif
