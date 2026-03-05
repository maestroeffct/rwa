@if($subscribe->target == "all_courses")
    <a href="/classes?sort=newest" target="_blank" class="d-flex align-items-center justify-content-between p-16 rounded-8 border-gray-200">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-200 rounded-circle">
                <x-iconsax-bul-teacher class="icons text-gray-500" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h6 class="font-14 text-dark">{{ trans('update.explore_all_courses') }}</h6>
                <p class="font-12 text-gray-500">{{ trans('update.n_courses', ['count' => \App\Models\Webinar::query()->where('status', 'active')->count()]) }}</p>
            </div>
        </div>

        <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
    </a>
@elseif($subscribe->target == "live_classes")
    <a href="/classes?type[]=webinar" target="_blank" class="d-flex align-items-center justify-content-between p-16 rounded-8 border-gray-200">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-200 rounded-circle">
                <x-iconsax-bul-video class="icons text-gray-500" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h6 class="font-14 text-dark">{{ trans('update.explore_all_live_courses') }}</h6>
                <p class="font-12 text-gray-500">{{ trans('update.n_courses', ['count' => \App\Models\Webinar::query()->where('type', 'webinar')->where('status', 'active')->count()]) }}</p>
            </div>
        </div>

        <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
    </a>
@elseif($subscribe->target == "video_courses")
    <a href="/classes?type[]=course" target="_blank" class="d-flex align-items-center justify-content-between p-16 rounded-8 border-gray-200">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-200 rounded-circle">
                <x-iconsax-bul-video-play class="icons text-gray-500" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h6 class="font-14 text-dark">{{ trans('update.explore_all_video_courses') }}</h6>
                <p class="font-12 text-gray-500">{{ trans('update.n_courses', ['count' => \App\Models\Webinar::query()->where('type', 'course')->where('status', 'active')->count()]) }}</p>
            </div>
        </div>

        <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
    </a>
@elseif($subscribe->target == "text_courses")
    <a href="/classes?type[]=text_lesson" target="_blank" class="d-flex align-items-center justify-content-between p-16 rounded-8 border-gray-200">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-200 rounded-circle">
                <x-iconsax-bul-note-2 class="icons text-gray-500" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h6 class="font-14 text-dark">{{ trans('update.explore_all_text_courses') }}</h6>
                <p class="font-12 text-gray-500">{{ trans('update.n_courses', ['count' => \App\Models\Webinar::query()->where('type', 'text_lesson')->where('status', 'active')->count()]) }}</p>
            </div>
        </div>

        <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
    </a>
@elseif($subscribe->target == "specific_categories")
    @foreach($subscribe->categories as $subscribeCategory)
        <a href="{{ $subscribeCategory->getUrl() }}" target="_blank" class="d-flex align-items-center justify-content-between p-16 rounded-8 border-gray-200">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 bg-gray-200 rounded-circle">
                    @if(!empty($subscribeCategory->icon))
                        <img src="{{ $subscribeCategory->icon }}" alt="{{ $subscribeCategory->title }}" class="img-fluid">
                    @else
                        <x-iconsax-lin-sidebar-right class="icons text-gray-500" width="24px" height="24px"/>
                    @endif
                </div>
                <div class="ml-8">
                    <h6 class="font-14 text-dark">{{ $subscribeCategory->title }}</h6>

                    @if($subscribe->target_type == "courses")
                        <p class="font-12 text-gray-500">{{ trans('update.n_courses', ['count' => \App\Models\Webinar::query()->where('category_id', $subscribeCategory->id)->where('status', 'active')->count()]) }}</p>
                    @else
                        <p class="font-12 text-gray-500">{{ trans('update.n_bundles', ['count' => \App\Models\Bundle::query()->where('category_id', $subscribeCategory->id)->where('status', 'active')->count()]) }}</p>
                    @endif
                </div>
            </div>

            <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
        </a>
    @endforeach

@elseif($subscribe->target == "specific_instructors")
    @foreach($subscribe->instructors as $subscribeInstructor)
        <a href="{{ ($subscribe->target_type == "courses") ? "/classes?instructor={$subscribeInstructor->id}" : "/bundles?instructor={$subscribeInstructor->id}" }}" target="_blank" class="d-flex align-items-center justify-content-between p-16 rounded-8 border-gray-200">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 bg-gray-200 rounded-circle">
                    <img src="{{ $subscribeInstructor->getAvatar(40) }}" alt="{{ $subscribeInstructor->full_name }}" class="img-cover rounded-circle">
                </div>
                <div class="ml-8">
                    <h6 class="font-14 text-dark">{{ $subscribeInstructor->full_name }}</h6>

                    @if($subscribe->target_type == "courses")
                        <p class="font-12 text-gray-500">{{ trans('update.n_courses', ['count' => \App\Models\Webinar::query()->where('teacher_id', $subscribeInstructor->id)->where('status', 'active')->count()]) }}</p>
                    @else
                        <p class="font-12 text-gray-500">{{ trans('update.n_bundles', ['count' => \App\Models\Bundle::query()->where('teacher_id', $subscribeInstructor->id)->where('status', 'active')->count()]) }}</p>
                    @endif
                </div>
            </div>

            <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
        </a>
    @endforeach

@elseif($subscribe->target == "specific_courses")
    @foreach($subscribe->courses as $subscribeCourse)
        <a href="{{ $subscribeCourse->getUrl() }}" target="_blank" class="d-flex align-items-center justify-content-between p-16 rounded-8 border-gray-200">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 bg-gray-200 rounded-circle">
                    <img src="{{ $subscribeCourse->getIcon() }}" alt="{{ $subscribeCourse->title }}" class="img-cover rounded-circle">
                </div>
                <div class="ml-8">
                    <h6 class="font-14 text-dark">{{ $subscribeCourse->title }}</h6>
                    <p class="font-12 text-gray-500">{{ trans('public.by') }} {{ $subscribeCourse->teacher->full_name }}</p>
                </div>
            </div>

            <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
        </a>
    @endforeach

@elseif($subscribe->target == "all_bundles")
    <a href="/bundles?sort=newest" target="_blank" class="d-flex align-items-center justify-content-between p-16 rounded-8 border-gray-200">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-200 rounded-circle">
                <x-iconsax-bul-box class="icons text-gray-500" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h6 class="font-14 text-dark">{{ trans('update.explore_all_bundles') }}</h6>
                <p class="font-12 text-gray-500">{{ trans('update.n_bundles', ['count' => \App\Models\Bundle::query()->where('status', 'active')->count()]) }}</p>
            </div>
        </div>

        <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
    </a>

@elseif($subscribe->target == "specific_bundles")
    @foreach($subscribe->bundles as $subscribeBundle)
        <a href="{{ $subscribeBundle->getUrl() }}" target="_blank" class="d-flex align-items-center justify-content-between p-16 rounded-8 border-gray-200">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 bg-gray-200 rounded-circle">
                    <img src="{{ $subscribeBundle->getImage() }}" alt="{{ $subscribeBundle->title }}" class="img-cover rounded-circle">
                </div>
                <div class="ml-8">
                    <h6 class="font-14 text-dark">{{ $subscribeBundle->title }}</h6>
                    <p class="font-12 text-gray-500">{{ trans('public.by') }} {{ $subscribeBundle->teacher->full_name }}</p>
                </div>
            </div>

            <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
        </a>
    @endforeach

@endif
