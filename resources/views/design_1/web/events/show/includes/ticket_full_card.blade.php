@php
    $eventTicketUserHasBought = $eventTicket->checkUserHasBought();
    $eventTicketHasDiscount = $eventTicket->hasDiscount();
    $eventTicketCapacity = $eventTicket->getAllCapacity();
    $eventTicketAvailability = $eventTicket->getAvailableCapacity();
    $hasInventory = (is_null($eventTicketAvailability) or $eventTicketAvailability > 0);

@endphp

<div class="event-ticket-full-card position-relative d-flex flex-column flex-lg-row justify-content-lg-between mt-16 rounded-16 bg-gray-100">

    @if($hasInventory and $eventTicketHasDiscount)
        <div class="event-ticket-full-card__discount-badge d-inline-flex-center py-4 px-8 rounded-10 bg-accent font-12 text-white">{{ trans('update.percent_off', ['percent' => $eventTicket->discount]) }}</div>
    @endif

    <div class="d-flex align-items-start p-16 p-lg-20 flex-1">
        <div class="event-ticket-full-card__main-icon d-flex-center size-48 rounded-circle bg-gray-300">
            @php
                $eventTicketIcon = $eventTicket->getIconText();
            @endphp

            @svg("iconsax-{$eventTicketIcon}", ['height' => 24, 'width' => 24, 'class' => "icons text-gray-500"])
        </div>

        <div class="ml-12 flex-1">
            <h3 class="font-16 text-dark">{{ $eventTicket->title }}</h3>
            <p class="mt-4 text-gray-500">{!! nl2br($eventTicket->description) !!}</p>

            @if(!empty($eventTicket->options))
                @php
                    $eventTicketOptions = explode(',', $eventTicket->options);
                @endphp

                <div class="d-flex align-items-center flex-wrap mt-16 gap-16">
                    @foreach($eventTicketOptions as $eventTicketOption)
                        <div class="d-flex align-items-center">
                            <div class="size-16">
                                <x-tick-icon class="icons text-primary" width="16px" height="16px"/>
                            </div>

                            <span class="flex-1 ml-4 font-12 text-gray-500">{{ $eventTicketOption }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="d-flex align-items-center flex-wrap mt-16 gap-20">
                <div class="d-flex align-items-center gap-4">
                    @if(!is_null($eventTicketCapacity))
                        @php
                            $capacityPercent = $eventTicket->getCapacityPercent();
                        @endphp

                        @if($capacityPercent > 0)
                            <div class="">
                                <div class="event-capacity-progress-bar progress-bar-120 d-flex mt-8 rounded-4 w-100 bg-gray-200">
                                    <span class="bg-success rounded-4" style="width: {{ $capacityPercent }}%"></span>
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="d-flex align-items-center gap-4">
                        <x-iconsax-lin-user class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-12 font-weight-bold">{{ (!is_null($eventTicketCapacity)) ? $eventTicketAvailability : trans('update.unlimited') }}</span>
                        <span class="font-12 text-gray-500">{{ (!is_null($eventTicketCapacity)) ? trans('update.seats_available') : trans('update.seats') }}</span>
                    </div>
                </div>

                @if(!empty($eventTicket->point))
                    <div class="d-flex align-items-center gap-4">
                        <x-iconsax-lin-star-1 class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-12 font-weight-bold">{{ $eventTicket->point }}</span>
                        <span class="font-12 text-gray-500">{{ trans('update.reward_points') }}</span>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Price & Actions --}}
    <div class="event-ticket-full-card__actions {{ ($hasInventory and $eventTicketHasDiscount) ? 'has-discount' : '' }}">
        <span class="circle-1"></span>
        <span class="circle-2"></span>

        <div class="js-event-ticket-add-to-cart-form d-flex align-items-end gap-16">
            <input type="hidden" name="item_id" value="{{ $eventTicket->id }}">
            <input type="hidden" name="item_name" value="event_ticket_id">

            {{-- quantity --}}
            <div class="js-event-quantity-card event-ticket-card__quantity-card d-inline-flex align-items-center gap-4">
                <input type="hidden" class="js-event-availability-count" value="{{ $eventTicketAvailability }}">

                <button type="button" class="minus d-flex-center bg-gray-200 rounded-8" {{ !$hasInventory ? 'disabled' : '' }}>
                    <x-iconsax-lin-minus class="icons text-gray-500" width="14px" height="14px"/>
                </button>

                <input type="number" name="quantity" value="1" {{ !$hasInventory ? 'disabled' : '' }} class="bg-gray-100 font-14 font-weight-bold" data-item="{{ $event->id }}">

                <button type="button" class="plus d-flex-center bg-gray-200 rounded-8" {{ !$hasInventory ? 'disabled' : '' }}>
                    <x-iconsax-lin-add class="icons text-gray-500" width="14px" height="14px"/>
                </button>
            </div>

            <div class="d-flex flex-column align-items-center">
                @if(!empty($event->sales_end_date) and $event->sales_end_date <= time())
                    <span class="font-24 text-gray-500 font-weight-bold">{{ ($eventTicket->price > 0) ? handlePrice($eventTicket->price) : trans('update.free') }}</span>

                    <div class="d-flex-center px-24 py-16 rounded-12 border-dashed border-gray-400 text-gray-500 mt-12">{{ trans('update.sales_end') }}</div>
                @elseif($hasInventory or $eventTicketUserHasBought)
                    {{-- Price --}}
                    <div class="d-flex align-items-center gap-8">
                        @if($eventTicketHasDiscount)
                            @php
                                $eventTicketPriceWithDiscount = $eventTicket->getPriceWithDiscount();
                            @endphp

                            <span class="font-24 text-primary font-weight-bold">{{ ($eventTicketPriceWithDiscount > 0) ? handlePrice($eventTicketPriceWithDiscount) : trans('update.free') }}</span>
                            <span class="text-gray-500 text-decoration-line-through">{{ handlePrice($eventTicket->price) }}</span>
                        @else
                            <span class="font-24 text-primary font-weight-bold">{{ ($eventTicket->price > 0) ? handlePrice($eventTicket->price) : trans('update.free') }}</span>
                        @endif
                    </div>

                    {{-- Btn --}}
                    @if($eventTicket->price > 0 or $eventTicketUserHasBought)
                        @if($eventTicketUserHasBought)
                            <a href="/panel/events/my-purchases" target="_blank" class="event-ticket-full-card__actions-btn btn btn-lg btn-primary px-24 mt-12">{{ trans('update.get_ticket') }}</a>
                        @else
                            <button type="button" class="js-event-ticket-add-to-cart event-ticket-full-card__actions-btn btn btn-lg btn-primary px-24 mt-12" data-ticket="{{ $eventTicket->id }}">{{ trans("public.add_to_cart") }}</button>
                        @endif
                    @else
                        <button type="button" class="js-event-get-free-ticket-btn event-ticket-full-card__actions-btn btn btn-lg btn-primary px-24 mt-12" data-path="/events/{{ $event->slug }}/tickets/{{ $eventTicket->id }}/free">{{ trans('update.get_ticket') }}</button>
                    @endif

                @else
                    <span class="font-24 text-gray-500 font-weight-bold">{{ ($eventTicket->price > 0) ? handlePrice($eventTicket->price) : trans('update.free') }}</span>

                    <div class="d-flex-center px-24 py-16 rounded-12 border-dashed border-gray-400 text-gray-500 mt-12">{{ trans('update.sold_out') }}</div>
                @endif
            </div>
        </div>

        @if($hasInventory and $eventTicketHasDiscount and !empty($eventTicket->discount_end_at))
            <div class="d-flex justify-content-end mt-12 font-12 text-gray-500">{{ trans('update.offer_until_date', ['date' => dateTimeFormat($eventTicket->discount_end_at, 'j F')]) }}</div>
        @endif
    </div>

</div>
