<div class="blog-lists-header position-relative">
    <div class="blog-lists-header__mask"></div>
    <div class="position-relative d-flex align-items-start bg-white rounded-32 z-index-2">
        <div class="d-flex flex-column p-32">
            @if(!empty($selectedAuthor))
                <div class="d-flex-center size-64 rounded-12 bg-gray-200">
                    <img src="{{ $selectedAuthor->getAvatar(64) }}" alt="{{ $selectedAuthor->full_name }}" class="img-cover rounded-12">
                </div>
            @elseif(!empty($selectedCategory) and !empty($selectedCategory->icon2))
                <div class="d-flex-center size-64 rounded-12 " style="background-color: {{ $selectedCategory->icon2_box_color }}">
                    <img src="{{ $selectedCategory->icon2 }}" alt="{{ $selectedCategory->title }}" class="img-fluid" width="32px" height="32px">
                </div>
            @else
                <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                    <x-iconsax-bul-note-2 class="icons text-primary" width="32px" height="32px"/>
                </div>
            @endif

            <div class="d-flex align-items-center mt-16 text-gray-500">
                <a href="/" class="text-gray-500">{{ getPlatformName() }}</a>
                <x-iconsax-lin-arrow-right-1 class="mx-4" width="16px" height="16px"/>
                <span class="">{{ trans('home.blog') }}</span>
            </div>

            @if(!empty($selectedAuthor))
                <h1 class="font-24 font-weight-bold mt-12">{{ $selectedAuthor->full_name }}</h1>
                <div class="font-12 text-gray-500 mt-8">{{ trans('update.n_posts', ['count' => $selectedAuthor->blog_count]) }}</div>
            @elseif(!empty($selectedCategory))
                <h1 class="font-24 font-weight-bold mt-12">{{ $selectedCategory->title }}</h1>
                    <div class="font-12 text-gray-500 mt-8">{{ trans('update.n_posts', ['count' => $selectedCategory->blog_count]) }}</div>
            @else
                <h1 class="font-24 font-weight-bold mt-12">{{ trans('update.blog_&_articles') }}</h1>
                <div class="font-12 text-gray-500 mt-8">{{ trans('update.all_things_about_programming_development_web_and_mobile_apps_and_other_stuff') }}</div>
            @endif
        </div>

        @if(!empty($selectedCategory) and !empty($selectedCategory->overlay_image))
            <div class="blog-lists-header__overlay-img">
                <img src="{{ $selectedCategory->overlay_image }}" alt="{{ $selectedCategory->title }}" class="img-cover">
            </div>
        @elseif(!empty($pageOverlayImage))
            <div class="blog-lists-header__overlay-img">
                <img src="{{ $pageOverlayImage }}" alt="{{ trans('update.overlay_image') }}" class="img-cover">
            </div>
        @endif
    </div>
</div>
