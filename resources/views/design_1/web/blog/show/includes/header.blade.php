<div class="blog-show-body__header position-relative px-24">
    <div class="blog-show-body__header-mask"></div>

    <div class="position-relative bg-white p-24 rounded-32 z-index-2">
        <div class="d-flex align-items-center text-white">
            <a href="/" class="text-gray-500">{{ getPlatformName() }}</a>
            <x-iconsax-lin-arrow-right-1 class="icons text-gray-500 mx-2" width="16px" height="16px"/>
            <a href="/blog" class="text-gray-500">{{ trans('home.blog') }}</a>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-12 mt-12">
            <h1 class="font-24 ">{{ $post->title }}</h1>

            @php
                $postBadges = $post->allBadges();
            @endphp

            @if(count($postBadges))
                <div class="d-flex flex-wrap align-items-center gap-12">
                    @foreach($postBadges as $postBadge)
                        <div class="d-inline-flex align-items-center gap-4 p-4 pr-8 rounded-32 font-12" style="background-color: {{ $postBadge->background }}; color: {{ $postBadge->color }};">
                            <x-iconsax-bul-note-2 class="icons" width="20px" height="20px"/>
                            <span class="">{{ $postBadge->title }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <p class="mt-12 font-12 text-gray-500">{{ $post->subtitle }}</p>

        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-24">
            <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center">

                <a href="{{ $post->author->getProfileUrl() }}" target="_blank" class="d-flex align-items-center">
                    <div class="size-40 rounded-circle">
                        <img src="{{ $post->author->getAvatar(40) }}" alt="{{ $post->author->full_name }}" class="img-cover rounded-circle">
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.written_by') }}</span>
                        <span class="d-block font-weight-bold text-gray-500 mt-2">{{ $post->author->full_name }}</span>
                    </div>
                </a>

                <div class="blog-show-body__header-first-item-line ml-lg-24 pl-lg-12 d-flex align-items-center mt-16 mt-lg-0">
                    <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                        <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.published_on') }}</span>
                        <span class="d-block font-weight-bold text-gray-500 mt-2">{{ dateTimeFormat($post->created_at, 'j M Y') }}</span>
                    </div>
                </div>

                @if(!empty($post->study_time))
                    <div class="d-flex align-items-center ml-lg-24 mt-16 mt-lg-0">
                        <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                            <x-iconsax-lin-clock-1 class="icons text-gray-500" width="20px" height="20px"/>
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-12 text-gray-400">{{ trans('public.study_time') }}</span>
                            <span class="d-block font-weight-bold text-gray-500 mt-2">{{ $post->study_time }} {{ trans('update.mins') }}</span>
                        </div>
                    </div>
                @endif

                <div class="d-flex align-items-center ml-lg-24 mt-16 mt-lg-0">
                    <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                        <x-iconsax-lin-note-2 class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('public.category') }}</span>
                        <a href="{{ $post->category->getUrl() }}" class="d-block font-weight-bold text-gray-500 mt-2">{{ $post->category->title }}</a>
                    </div>
                </div>

            </div>

            <div class="js-share-post d-flex-center size-40 bg-gray-100 rounded-circle cursor-pointer mt-16 mt-lg-0"
                 data-path="/blog/{{ $post->slug }}/share-modal" data-tippy-content="{{ trans('update.share_this_post_with_others') }}"
            >
                <x-iconsax-lin-share class="icons text-primary" width="20px" height="20px"/>
            </div>

        </div>
    </div>
</div>
