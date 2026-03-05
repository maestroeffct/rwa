@php
    $currentTime = time();
@endphp

<div class="panel-course-card-1__badges d-flex flex-wrap gap-8">
    @if($event->status == "canceled")
        <div class="d-flex-center badge bg-danger">
            <x-iconsax-lin-calendar-remove class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('public.canceled') }}</span>
        </div>
    @elseif(!empty($event->start_date) and $event->start_date > $currentTime)
        <div class="d-flex-center badge bg-primary">
            <x-iconsax-lin-calendar-2 class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.scheduled') }}</span>
        </div>
    @elseif(!empty($event->end_date) and $event->end_date < $currentTime)
        <div class="d-flex-center badge bg-primary">
            <x-iconsax-lin-tick-circle class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.ended') }}</span>
        </div>
    @else
        <div class="d-flex-center badge bg-success">
            <x-iconsax-lin-calendar-tick class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.ongoing') }}</span>
        </div>
    @endif
</div>
