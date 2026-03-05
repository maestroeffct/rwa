<div class="flex-1 d-flex flex-wrap">
    @if(!empty($upcomingCourse->webinar_id))
        <div class="d-flex-center badge bg-accent">
            <x-iconsax-lin-trash class="icons text-white" width="20px" height="20px"/>
            <span class="ml-4 font-12 text-white">{{ trans('update.released') }}</span>
        </div>
    @else
        @switch($upcomingCourse->status)
            @case(\App\Models\UpcomingCourse::$active)
                <div class="d-flex-center badge bg-primary">
                    <x-iconsax-lin-trash class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('public.published') }}</span>
                </div>
                @break
            @case(\App\Models\UpcomingCourse::$isDraft)
                <div class="d-flex-center badge bg-danger">
                    <x-iconsax-lin-trash class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('public.draft') }}</span>
                </div>
                @break
            @case(\App\Models\UpcomingCourse::$pending)
                <div class="d-flex-center badge bg-warning">
                    <x-iconsax-lin-trash class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('public.waiting') }}</span>
                </div>
                @break
            @case(\App\Models\UpcomingCourse::$inactive)
                <div class="d-flex-center badge bg-danger">
                    <x-iconsax-lin-trash class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">{{ trans('public.rejected') }}</span>
                </div>
                @break
        @endswitch
    @endif
</div>
