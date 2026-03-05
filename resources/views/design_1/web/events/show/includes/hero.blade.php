<div class="event-hero d-flex flex-column justify-content-end rounded-32 px-20 bg-gray-200">
    <div class="event-hero__mask rounded-32"></div>

    <img src="{{ $event->cover_image }}" class="event-hero__cover-img img-cover rounded-32" alt="{{ $event->title }}"/>

    <div class="event-hero__content position-relative z-index-3">
        @if(!empty($event->category))
            <div class="d-flex align-items-center text-white opacity-50">
                <a href="/events" class="text-white">{{ trans('update.events') }}</a>
                <x-iconsax-lin-arrow-right-1 class="icons text-white mx-2" width="16px" height="16px"/>
                <a href="{{ $event->category->getUrl() }}" class="text-white">{{ $event->category->title }}</a>
            </div>
        @endif

        <div class="d-flex align-items-center flex-wrap gap-12 mt-4">
            <h1 class="event-hero__title font-32 font-weight-bold text-white text-ellipsis">{{ $event->title }}</h1>

            @php
                $eventAllBadges = $event->allBadges();
            @endphp

            {{-- Badges --}}
            @if(count($eventAllBadges))
                <div class="d-flex flex-wrap align-items-center gap-12">
                    @foreach($eventAllBadges as $eventBadge)
                        <div class="d-flex-center gap-4 p-4 pr-8 rounded-32" style="background-color: {{ $eventBadge->background }}; color: {{ $eventBadge->color }};">
                            @if(!empty($eventBadge->icon))
                                <div class="size-24">
                                    <img src="{{ $eventBadge->icon }}" alt="{{ $eventBadge->title }}" class="img-cover">
                                </div>
                            @endif
                            <span class="font-12">{{ $eventBadge->title }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        @if(!empty($event->summary))
            <div class="mt-8 text-white opacity-50">{!! nl2br($event->summary) !!}</div>
        @endif

        @php
            $eventRate = $event->getRate();
        @endphp

        <div class="d-flex align-items-center flex-wrap gap-24 mt-12">
            {{-- Rate --}}
            @if($eventRate > 0)
                @include('design_1.web.components.rate', [
                     'rate' => $eventRate,
                     'rateCount' => $event->getRateCount(),
                     'rateClassName' => ''
                 ])
            @endif

            {{-- Online --}}
            @if($event->type == "online")
                <div class="d-flex align-items-center font-12 text-white">
                    <x-iconsax-lin-monitor class="icons text-white" width="16px" height="16px"/>
                    <span class="mx-4 font-weight-bold">{{ trans("update.online") }}</span>
                </div>
            @else
                @php
                    $specificLocationTitle = !empty($event->specificLocation) ? $event->specificLocation->getFullAddress(false, true, true, false, false) : null;
                @endphp

                <div class="d-flex align-items-center font-12 text-white">
                    <x-iconsax-lin-location class="icons text-white" width="16px" height="16px"/>

                    @if(!empty($specificLocationTitle))
                        <span class="mx-4 font-weight-bold">{{ $specificLocationTitle }}</span>
                    @else
                        <span class="mx-4 font-weight-bold">{{ trans("update.in_person") }}</span>
                    @endif
                </div>
            @endif

            {{-- Date --}}
            @if(!empty($event->start_date))
                <div class="d-flex align-items-center font-12 text-white">
                    <x-iconsax-lin-calendar-2 class="icons text-white" width="16px" height="16px"/>
                    <span class="mx-4 font-weight-bold">{{ dateTimeFormat($event->start_date, 'j M Y') }}</span>
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-108 mt-20">
            <div class="d-flex align-items-center">
                <div class="size-40 rounded-circle">
                    <img src="{{ $event->creator->getAvatar(40) }}" class="img-cover rounded-circle" alt="{{ $event->creator->full_name }}">
                </div>

                <div class="ml-8">
                    <a href="{{ $event->creator->getProfileUrl() }}" target="_blank" class="font-14 font-weight-bold text-white">{{ $event->creator->full_name }}</a>
                    <p class="mt-2 font-12 text-white">{{ $event->creator->role->caption }}</p>
                </div>
            </div>

            @if(!is_null($event->capacity))
                @php
                    $capacityPercent = $event->getCapacityPercent();
                @endphp

                @if($capacityPercent < 100)
                    <div class="">
                        <div class="d-flex align-items-center gap-4 font-12">
                            <span class="font-weight-bold text-white">{{ round($capacityPercent,1) }}%</span>
                            <span class="text-gray-500">{{ trans('update.capacity_reached') }}</span>
                        </div>

                        <div class="event-capacity-progress-bar d-flex mt-8 rounded-4 w-100">
                            <span class="bg-success rounded-4" style="width: {{ $capacityPercent }}%"></span>
                        </div>
                    </div>
                @else
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-36 bg-success rounded-circle">
                            <x-iconsax-bul-tick-circle class="icons text-white" width="36px" height="36px"/>
                        </div>

                        <div class="ml-8">
                            <h5 class="font-12 font-weight-bold text-dark">{{ trans('update.capacity_reached') }}!</h5>
                            <p class="font-12 text-gray-500">{{ trans('update.all_seats_were_been_sold') }}</p>
                        </div>
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>
