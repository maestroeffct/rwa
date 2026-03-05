{{-- Price Plans --}}
@if((!isFreeModeEnabled() || isFreeModeShowPriceEnabled()) and !empty($course->tickets) and count($course->tickets))
    <div class="mt-16 px-16">
        <h6 class="font-12 font-weight-bold mb-16">{{ trans('update.select_a_pricing_plan') }}</h6>

        @foreach($course->tickets as $ticket)
            @php
                $ticketIsValid = $ticket->isValid();
            @endphp

            <div class="course-right-side__price-plan position-relative custom-input-button position-relative mt-12">
                <input class="form-check-input" type="radio"
                       {{ (!$ticketIsValid) ? 'disabled' : '' }}
                       data-discount-price="{{ handlePrice($ticket->getPriceWithDiscount($course->price, !empty($activeSpecialOffer) ? $activeSpecialOffer : null)) }}"
                       value="{{ ($ticketIsValid) ? $ticket->id : '' }}"
                       name="ticket_id"
                       id="courseOff{{ $ticket->id }}">

                <label for="courseOff{{ $ticket->id }}" class="position-relative d-flex flex-column align-items-start p-16 rounded-12 bg-white {{ (!$ticketIsValid) ? 'disabled' : '' }}">
                    <span class="course-price-plan-title font-12 font-weight-bold">{{ $ticket->title }} ({{ $ticket->discount }}% {{ trans('public.off') }})</span>
                    <span class="course-price-plan-subtitle font-12 mt-8">{{ $ticket->getSubTitle() }}</span>
                </label>

                @if(!$ticketIsValid)
                    <span class="course-right-side__price-plan-expired px-8 py-4 bg-danger-20 text-danger font-12 rounded-8">{{ trans('panel.expired') }}</span>
                @endif
            </div>
        @endforeach
    </div>
@endif

{{-- Price --}}
@if((!isFreeModeEnabled() || isFreeModeShowPriceEnabled()) and $course->price > 0)
    @php
        $realPrice = handleCoursePagePrice($course->price);
    @endphp

    <div id="priceBox" class="d-flex align-items-end justify-content-center  mt-20 px-16">
        @if(!empty($activeSpecialOffer))
            <div class="d-flex align-items-center text-center mr-16">
                @php
                    $priceWithDiscount = handleCoursePagePrice($course->getPrice());
                @endphp

                <div id="priceWithDiscount" class="d-block font-24 font-weight-bold">
                    {{ $priceWithDiscount['price'] }}
                </div>

                @if(!empty($priceWithDiscount['tax']))
                    <span class="d-block font-12 text-gray-500 ml-4">+ {{ $priceWithDiscount['tax'] }} {{ trans('cart.tax') }}</span>
                @endif
            </div>
        @endif

        <div class="d-flex align-items-center text-center">
            <div id="realPrice"
                 data-value="{{ $course->price }}"
                 data-special-offer="{{ !empty($activeSpecialOffer) ? $activeSpecialOffer->percent : ''}}"
                 class="d-block @if(!empty($activeSpecialOffer)) font-14 text-gray-500 text-decoration-line-through @else font-24 font-weight-bold @endif">
                {{ $realPrice['price'] }}
            </div>

            @if(!empty($realPrice['tax']) and empty($activeSpecialOffer))
                <span class="d-block font-12 text-gray-500 ml-4">+ {{ $realPrice['tax'] }} {{ trans('cart.tax') }}</span>
            @endif
        </div>
    </div>
    @else
    <div class="d-flex align-items-center justify-content-center mt-20 px-16">
        <span class="font-24 font-weight-bold">{{ trans('public.free') }}</span>
    </div>
@endif
