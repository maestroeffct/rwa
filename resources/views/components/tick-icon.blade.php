@php
    $w = !empty($width) ? str_replace("px", '', $width) : 16;
    $h = !empty($height) ? str_replace("px", '', $height) : 16;
@endphp

<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 {{ $w }} {{ $h }}" fill="none" width="{{ $w }}px" height="{{ $h }}px" class="{{ $class ?? '' }}">
    <path d="M3 7.48222L6.17305 11L13 4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
