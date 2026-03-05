@if((!isFreeModeEnabled() || isFreeModeShowPriceEnabled()) and $bundle->price > 0)
    @if($bundle->bestTicket() < $bundle->price)
        <span class="">{{ handlePrice($bundle->bestTicket(), true, true, false, null, true) }}</span>
        <span class="font-14 font-weight-400 text-gray-500 ml-8 text-decoration-line-through">{{ handlePrice($bundle->price, true, true, false, null, true) }}</span>
    @else
        <span class="">{{ handlePrice($bundle->price, true, true, false, null, true) }}</span>
    @endif
    @else
    <span class="">{{ trans('public.free') }}</span>
@endif
