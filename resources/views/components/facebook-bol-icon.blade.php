@php
    $w = !empty($width) ? str_replace("px", '', $width) : 16;
    $h = !empty($height) ? str_replace("px", '', $height) : 16;
@endphp

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {{ $w }} {{ $h }}" fill="none" width="{{ $w }}px" height="{{ $h }}px" class="{{ $class ?? '' }}">
    <path d="M15.1204 5.32003H17.0004V2.14003C16.0901 2.04538 15.1755 1.99865 14.2604 2.00003C11.5404 2.00003 9.68035 3.66003 9.68035 6.70003V9.32003H6.61035V12.88H9.68035V22H13.3604V12.88H16.4204L16.8804 9.32003H13.3604V7.05003C13.3604 6.00003 13.6404 5.32003 15.1204 5.32003Z"
          fill="currentColor"/>
</svg>
