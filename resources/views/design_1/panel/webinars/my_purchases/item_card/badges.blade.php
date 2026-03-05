<div class="panel-course-card-1__badges d-flex flex-wrap gap-8">
    @if(!empty($saleItem->deleteRequest) and $saleItem->deleteRequest->status == "pending")
        <div class="d-flex-center badge bg-danger">
            <x-iconsax-lin-trash class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.removal_request_sent') }}</span>
        </div>
    @else
        @if(!empty($sale->bundle))
            <div class="d-flex-center badge bg-primary">
                <x-iconsax-lin-box-1 class="icons text-white" width="20px" height="20px"/>
                <span class="ml-4 font-12 text-white">{{ trans('update.bundle') }}</span>
            </div>
        @else
            @if($saleItem->isWebinar())
                @if($saleItem->start_date > time())
                    <div class="d-flex-center badge bg-primary">
                        <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                        <span class="ml-4 font-12 text-white">{{ trans('panel.not_conducted') }}</span>
                    </div>
                @elseif($saleItem->isProgressing())
                    <div class="d-flex-center badge bg-warning">
                        <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                        <span class="ml-4 font-12 text-white">{{ trans('webinars.in_progress') }}</span>
                    </div>
                @else
                    <div class="d-flex-center badge bg-info">
                        <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                        <span class="ml-4 font-12 text-white">{{ trans('public.finished') }}</span>
                    </div>
                @endif
            @endif
        @endif
    @endif
</div>
