@if((!isFreeModeEnabled() || isFreeModeShowPriceEnabled()) and $courseRow->price > 0)
    @if($courseRow->bestTicket() < $courseRow->price)
        <span class="">{{ handlePrice($courseRow->bestTicket(), true, true, false, null, true) }}</span>
        <span class="font-14 font-weight-400 text-gray-500 text-decoration-line-through {{ !empty($discountedPriceClass) ? $discountedPriceClass : 'ml-8' }}">{{ handlePrice($courseRow->price, true, true, false, null, true) }}</span>
    @else
        <span class="">{{ handlePrice($courseRow->price, true, true, false, null, true) }}</span>
    @endif
    @else
    <span class="">{{ trans('public.free') }}</span>
@endif
