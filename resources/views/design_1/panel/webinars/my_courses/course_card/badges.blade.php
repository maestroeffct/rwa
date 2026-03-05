<div class="panel-course-card-1__badges d-flex flex-wrap gap-8">
        @if(!empty($course->deleteRequest) and $course->deleteRequest->status == "pending")
        <div class="d-flex-center badge bg-danger">
            <x-iconsax-lin-trash class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.removal_request_sent') }}</span>
        </div>
    @else
        @switch($course->status)
            @case(\App\Models\Webinar::$active)
                @if($course->isWebinar())
                    @if($course->start_date > time())
                        <div class="d-flex-center badge bg-primary">
                            <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                            <span class="ml-4 font-12 text-white">{{ trans('panel.not_conducted') }}</span>
                        </div>
                    @elseif($course->isProgressing())
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
                @break
            @case(\App\Models\Webinar::$isDraft)
                <div class="d-flex-center badge bg-warning">
                    <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('public.draft') }}</span>
                </div>
                @break
            @case(\App\Models\Webinar::$pending)
                <div class="d-flex-center badge bg-warning">
                    <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('public.waiting') }}</span>
                </div>
                @break
            @case(\App\Models\Webinar::$inactive)
                <div class="d-flex-center badge bg-warning">
                    <x-iconsax-lin-note-2 class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('public.rejected') }}</span>
                </div>
                @break
        @endswitch
    @endif
</div>
