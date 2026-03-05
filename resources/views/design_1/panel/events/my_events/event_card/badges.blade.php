<div class="panel-course-card-1__badges d-flex flex-wrap gap-8">
    @if(!empty($event->deleteRequest) and $event->deleteRequest->status == "pending")
        <div class="d-flex-center badge bg-danger">
            <x-iconsax-lin-trash class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.removal_request_sent') }}</span>
        </div>
    @else
        @switch($event->status)
            @case('publish')
                @if(!empty($event->start_date) and $event->start_date > time())
                    <div class="d-flex-center badge bg-success">
                        <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                        <span class="ml-4 font-12 text-white">{{ trans('update.scheduled') }}</span>
                    </div>
                @elseif(!empty($event->end_date) and $event->end_date < time())
                    <div class="d-flex-center badge bg-info">
                        <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                        <span class="ml-4 font-12 text-white">{{ trans('update.ended') }}</span>
                    </div>
                @else
                    <div class="d-flex-center badge bg-primary">
                        <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                        <span class="ml-4 font-12 text-white">{{ trans('update.ongoing') }}</span>
                    </div>
                @endif

                @break
            @case('draft')
                <div class="d-flex-center badge bg-warning">
                    <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('public.draft') }}</span>
                </div>
                @break
            @case('pending')
                <div class="d-flex-center badge bg-warning">
                    <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('public.waiting') }}</span>
                </div>
                @break
            @case('unpublish')
                <div class="d-flex-center badge bg-danger">
                    <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('update.unpublished') }}</span>
                </div>
                @break
            @case('rejected')
                <div class="d-flex-center badge bg-danger">
                    <x-iconsax-lin-clipboard-close class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('public.rejected') }}</span>
                </div>
                @break
            @case('canceled')
                <div class="d-flex-center badge bg-danger">
                    <x-iconsax-lin-calendar-remove class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('update.canceled') }}</span>
                </div>
                @break
        @endswitch
    @endif
</div>
