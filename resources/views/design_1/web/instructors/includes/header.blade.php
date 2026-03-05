<div class="instructors-lists-header position-relative">
    <div class="instructors-lists-header__mask"></div>
    <div class="position-relative d-flex align-items-start bg-white rounded-32 z-index-2">
        <div class="d-flex flex-column p-32">
            <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                <x-iconsax-bol-teacher class="icons text-primary" width="32px" height="32px"/>
            </div>

            <div class="d-flex align-items-center mt-16 text-gray-500">
                <a href="/" class="text-gray-500">{{ getPlatformName() }}</a>
                <x-iconsax-lin-arrow-right-1 class="mx-4" width="16px" height="16px"/>
                <span class="">{{ trans('home.instructors') }}</span>
            </div>

            <h1 class="font-24 font-weight-bold mt-12">{{ trans('home.instructors') }}</h1>
            <div class="font-12 text-gray-500 mt-8">{{ trans('update.explore_all_instructors_in_one_place_and_find_your_desired_instructor') }}</div>
        </div>

        @if(!empty($pageOverlayImage))
            <div class="instructors-lists-header__overlay-img">
                <img src="{{ $pageOverlayImage }}" alt="{{ trans('update.overlay_image') }}" class="img-cover">
            </div>
        @endif
    </div>
</div>
