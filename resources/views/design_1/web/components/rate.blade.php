@php
    $i = 5;
@endphp

@if((!empty($rate) and $rate > 0) or !empty($showRateStars))
    <div class="stars-card d-flex align-items-center {{ $rateClassName ?? '' }}">
        @while(--$i >= 5 - $rate)
            <span class="stars-card__item active">
                <x-iconsax-bol-star-1 class="icons" width="{{ !empty($rateIconSize) ? $rateIconSize : '14px' }}" height="{{ !empty($rateIconSize) ? $rateIconSize : '14px' }}"/>
            </span>
        @endwhile
        @while($i-- >= 0)
            <span class="stars-card__item">
                <x-iconsax-bol-star-1 class="icons" width="{{ !empty($rateIconSize) ? $rateIconSize : '14px' }}" height="{{ !empty($rateIconSize) ? $rateIconSize : '14px' }}"/>
            </span>
        @endwhile

        @if(!empty($rateCount))
            <span class="ml-4 text-gray-500 {{ !empty($rateCountFont) ? $rateCountFont : 'font-14' }}">({{ $rateCount }})</span>
        @endif
    </div>
@endif
