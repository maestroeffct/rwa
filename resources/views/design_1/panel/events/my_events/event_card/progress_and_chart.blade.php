@if($event->type == "online" and !empty($event->start_date) and checkTimestampInToday($event->start_date))
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-success rounded-circle">
            <x-iconsax-bol-video class="icons text-white" width="36px" height="36px"/>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold text-dark">{{ trans('update.todays_live_session') }}!</h5>
            <p class="font-12 text-gray-500">{{ trans('update.your_event_starts_today') }}</p>
        </div>
    </div>
@elseif(!is_null($event->capacity))
    @php
        $percent = $event->getCapacityPercent();
    @endphp

    @if($percent < 100)
        <div class="w-100">
            <div class="d-flex align-items-center gap-4 font-12">
                <span class="font-weight-bold text-dark">{{ round($percent,1) }}%</span>
                <span class="text-gray-500">{{ trans('update.capacity_reached') }}</span>
            </div>

            <div class="progress-bar d-flex mt-8 rounded-4 bg-gray-100 w-100">
                <span class="bg-success rounded-4" style="width: {{ $percent }}%"></span>
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
