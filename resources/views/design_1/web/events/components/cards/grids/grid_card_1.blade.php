<a href="{{ $event->getUrl() }}" class="text-decoration-none d-block">
    <div class="event-grid-card-1 position-relative bg-gray-100 rounded-16">
        <img src="{{ $event->thumbnail }}" alt="{{ $event->title }}" class="img-cover rounded-16">

        {{-- Badges On Image --}}
        @include("design_1.web.events.components.cards.grids.badges")

        {{-- Contents --}}
        <div class="event-grid-card-1__contents bg-white d-flex flex-column p-12 rounded-8">
            <h3 class="event-grid-card-1__contents-title font-14 text-dark">{{ $event->title }}</h3>

            @include("design_1.web.components.rate", [
                'rate' => round($event->getRate(), 1),
                'rateCount' => $event->reviews()->where('status', 'active')->count(),
                'rateClassName' => 'mt-12 mb-16',
            ])

            <div class="d-flex align-items-center mt-auto ">
                <div class="size-32 rounded-circle bg-gray-100">
                    <img src="{{ $event->creator->getAvatar(32) }}" alt="{{ $event->creator->full_name }}" class="img-cover rounded-circle">
                </div>
                <div class="ml-8">
                    <h6 class="font-12 font-weight-bold text-dark">{{ $event->creator->full_name }}</h6>
                    <p class="mt-2 font-12 text-gray-500">{{ trans('public.in') }} {{ $event->category->title }}</p>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-16 border-top-gray-100 pt-12">

                <div class="d-flex align-items-center gap-4 font-16 font-weight-bold text-primary flex-1">
                    @php
                        $getMinAndMaxPrice = $event->getMinAndMaxPrice();
                    @endphp

                    @if($getMinAndMaxPrice['min'] == $getMinAndMaxPrice['max'])
                        <span class="">{{ ($getMinAndMaxPrice['min'] > 0) ? handlePrice($getMinAndMaxPrice['min'], true, true, false, null, true) : trans('update.free') }}</span>
                    @else
                        <span class="">{{ ($getMinAndMaxPrice['min'] > 0) ? handlePrice($getMinAndMaxPrice['min'], true, true, false, null, true) : trans('update.free') }}</span>
                        -
                        <span class="">{{ ($getMinAndMaxPrice['max'] > 0) ? handlePrice($getMinAndMaxPrice['max'], true, true, false, null, true) : trans('update.free') }}</span>
                    @endif
                </div>

                @if(!empty($event->start_date))
                    <div class="d-flex align-items-center">
                        <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-2 font-12 text-gray-500">{{ dateTimeFormat($event->start_date, 'F j') }}</span>
                    </div>
                @endif

            </div>
        </div>
    </div>
</a>
