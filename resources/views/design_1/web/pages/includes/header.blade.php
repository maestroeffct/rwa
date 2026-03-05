<div class="pages-header position-relative">
    <div class="pages-header__mask"></div>
    <div class="position-relative d-flex align-items-start bg-white rounded-32 z-index-2">
        <div class="d-flex flex-column p-32">

            @if(!empty($page->icon))
                <div class="d-flex-center size-64 rounded-12 bg-gray-100">
                    <img src="{{ $page->icon }}" alt="icon" class="img-fluid">
                </div>
            @else
                <div class="d-flex-center size-64 rounded-12 bg-gray-400-30">
                    <x-iconsax-bul-note-2 class="icons text-primary" width="32px" height="32px"/>
                </div>
            @endif


            <div class="d-flex align-items-center mt-16 text-gray-500">
                <a href="/" class="text-gray-500">{{ getPlatformName() }}</a>
                <x-iconsax-lin-arrow-right-1 class="mx-4" width="16px" height="16px"/>
                <span class="">{{ $page->title }}</span>
            </div>


            <h1 class="font-24 font-weight-bold mt-12">{{ $page->title }}</h1>
            <div class="font-12 text-gray-500 mt-8">{{ $page->subtitle }}</div>
        </div>

        @if(!empty($page->header_icon))
            <div class="pages-header__overlay-img">
                <img src="{{ $page->header_icon }}" alt="{{ $page->title }}" class="img-cover">
            </div>
        @endif
    </div>
</div>
