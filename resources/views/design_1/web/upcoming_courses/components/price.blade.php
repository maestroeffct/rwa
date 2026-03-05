@if($upcomingCourse->price > 0)
    <span class="">{{ handlePrice($upcomingCourse->price, true, true, false, null, true) }}</span>
@else
    <span class="">{{ trans('public.free') }}</span>
@endif
