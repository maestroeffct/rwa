@php
    $postGridBadges = $post->allBadges();
@endphp

<div class="post-card position-relative">

    <a href="{{ $post->getUrl() }}">
        <div class="post-card__image position-relative w-100 rounded-16 bg-gray-100">
            <img src="{{ $post->image }}" class="img-cover rounded-16" alt="{{ $post->title }}">


            @if(count($postGridBadges))
                <div class="post-card__badges d-flex flex-wrap align-items-center gap-12 p-12">
                    @foreach($postGridBadges as $postGridCardBadge)
                        <div class="d-inline-flex align-items-center gap-4 p-4 pr-8 rounded-32 font-12" style="background-color: {{ $postGridCardBadge->background }}; color: {{ $postGridCardBadge->color }};">
                            <x-iconsax-bul-note-2 class="icons" width="20px" height="20px"/>
                            <span class="">{{ $postGridCardBadge->title }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </a>

    <div class="position-relative px-12">
        <div class="post-card__mask"></div>

        <div class="post-card__body d-flex flex-column position-relative bg-white rounded-16 border-gray-200 z-index-2">
            <div class="p-12 mb-16">
                <a href="{{ $post->getUrl() }}">
                    <h3 class="post-card__title font-16 font-weight-bold text-dark">{{ $post->title }}</h3>
                </a>

                <div class="d-flex align-items-center mt-16">
                    <div class="size-32 rounded-circle">
                        <img src="{{ $post->author->getAvatar(32) }}" class="img-cover rounded-circle" alt="{{ $post->author->full_name }}">
                    </div>

                    <div class="ml-4">
                        <a href="{{ $post->author->getProfileUrl() }}" target="_blank" class="font-14 font-weight-bold text-dark">{{ $post->author->full_name }}</a>
                        <p class="mt-2 font-14 text-gray-500 text-ellipsis">{{ $post->author->bio }}</p>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-auto p-12 pb-16 border-top-gray-100">
                <div class="d-flex align-items-center">
                    <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="16px" height="16px"/>
                    <span class="ml-4 font-14 text-gray-500">{{ dateTimeFormat($post->created_at, 'j M Y') }}</span>
                </div>

                <div class="d-flex align-items-center gap-12">

                    @if(!empty($post->study_time))
                        <div class="d-flex align-items-center pr-12 border-right-gray-200">
                            <x-iconsax-lin-clock-1 class="icons text-gray-500" width="16px" heigh="16px"/>
                            <span class="ml-4 font-14 text-gray-500">{{ $post->study_time }} {{ trans('update.min') }}</span>
                        </div>
                    @endif

                    <div class="d-flex align-items-center">
                        <x-iconsax-lin-message class="icons text-gray-500" width="16px" heigh="16px"/>
                        <span class="ml-4 font-14 text-gray-500">{{ $post->comments_count }}</span>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
