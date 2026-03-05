<div class="flex-1 d-flex flex-wrap">
    @if($order->has_overdue)
        <div class="d-flex-center badge bg-danger">
            <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.overdue') }}</span>
        </div>
    @endif

    @if($order->isCompleted())
        <div class="d-flex-center badge bg-accent">
            <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.completed') }}</span>
        </div>
    @elseif($order->status == "open")
        <div class="d-flex-center badge bg-primary">
            <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('public.open') }}</span>
        </div>
    @elseif($order->status == "rejected")
        <div class="d-flex-center badge bg-danger">
            <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('public.rejected') }}</span>
        </div>
    @elseif($order->status == "canceled")
        <div class="d-flex-center badge bg-danger">
            <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('public.canceled') }}</span>
        </div>
    @elseif($order->status == "pending_verification")
        <div class="d-flex-center badge bg-warning">
            <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.pending_verification') }}</span>
        </div>
    @elseif($order->status == "refunded")
        <div class="d-flex-center badge bg-accent">
            <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.refunded') }}</span>
        </div>
    @endif
</div>
