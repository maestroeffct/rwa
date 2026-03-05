@php
    $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
@endphp

@if($cartItems->whereNotNull('webinar_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14">{{ trans('update.courses') }}</h5>

        @foreach($cartItems->whereNotNull('webinar_id') as $cartItem)
            @include('design_1.web.cart.drawer.cards.course', [
                'cartItemInfo' => $cartItem->getItemInfo(),
                'className' => $loop->first ? 'mt-16' : 'mt-20',
            ])
        @endforeach
    </div>
@endif

@if($cartItems->whereNotNull('bundle_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14">{{ trans('update.bundles') }}</h5>

        @foreach($cartItems->whereNotNull('bundle_id') as $cartItem)
            @include('design_1.web.cart.drawer.cards.course', [
                'cartItemInfo' => $cartItem->getItemInfo(),
                'className' => $loop->first ? 'mt-16' : 'mt-20',
            ])
        @endforeach
    </div>
@endif

@if($cartItems->whereNotNull('reserve_meeting_id')->count() or $cartItems->whereNotNull('meeting_package_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14 mb-16">{{ trans('panel.meetings') }}</h5>

        @foreach($cartItems->whereNotNull('reserve_meeting_id') as $cartItem)
            @include('design_1.web.cart.drawer.cards.meeting', [
                'cartItemInfo' => $cartItem->getItemInfo(),
                'className' => $loop->last ? '' : 'mb-20',
            ])
        @endforeach


        @foreach($cartItems->whereNotNull('meeting_package_id') as $cartItem)
            @include('design_1.web.cart.drawer.cards.meeting_package', [
                'cartItemInfo' => $cartItem->getItemInfo(),
                'className' => $loop->last ? '' : 'mb-20',
            ])
        @endforeach
    </div>
@endif


@if($cartItems->whereNotNull('product_order_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14">{{ trans('update.products') }}</h5>

        @foreach($cartItems->whereNotNull('product_order_id') as $cartItem)
            @include('design_1.web.cart.drawer.cards.product', [
                'cartItemInfo' => $cartItem->getItemInfo(),
                'className' => $loop->first ? 'mt-16' : 'mt-20',
            ])
        @endforeach
    </div>
@endif

@if($cartItems->whereNotNull('event_ticket_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14">{{ trans('update.events') }}</h5>

        @foreach($cartItems->whereNotNull('event_ticket_id') as $cartItem)
            @include('design_1.web.cart.drawer.cards.event_ticket', [
                'cartItemInfo' => $cartItem->getItemInfo(),
                'className' => $loop->first ? 'mt-16' : 'mt-20',
            ])
        @endforeach
    </div>
@endif

@if($cartItems->whereNotNull('installment_payment_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14">{{ trans('update.installment_upfront') }}</h5>

        @foreach($cartItems->whereNotNull('installment_payment_id') as $cartItem)
            @include('design_1.web.cart.drawer.cards.installment_payment', [
                'cartItemInfo' => $cartItem->getItemInfo(),
                'className' => $loop->first ? 'mt-16' : 'mt-20',
            ])
        @endforeach
    </div>
@endif
