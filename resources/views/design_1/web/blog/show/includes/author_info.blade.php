<div class="post-author-info-card position-relative mt-32 mt-lg-60">
    <div class="post-author-info-card__mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row align-items-start gap-lg-24 bg-white px-16 rounded-24 z-index-3">
        <div class="post-author-info-card__details d-flex flex-column flex-1 py-16">
            <div class="d-flex align-items-center {{ empty($post->author->about) ? 'mb-24' : '' }}">
                <div class="d-flex-center size-80 rounded-12 bg-gray-200">
                    <a href="{{ $post->author->getProfileUrl() }}" target="_blank">
                        <img src="{{ $post->author->getAvatar(80) }}" alt="{{ $post->author->full_name }}" class="img-cover rounded-12">
                    </a>
                </div>
                <div class="ml-12 flex-1">
                    <h6 class="font-14 font-weight-bold text-dark">
                        <a href="{{ $post->author->getProfileUrl() }}" target="_blank" class="text-dark">{{ $post->author->full_name }}</a>
                    </h6>

                    @php
                        $authorRates = $post->author->rates(true);
                    @endphp

                    @if(!empty($authorRates['rate']))
                        @include('design_1.web.components.rate', [
                            'rate' => $authorRates['rate'],
                            'rateCount' => $authorRates['count'],
                            'rateClassName' => 'mt-4',
                        ])
                    @endif

                    <div class="d-flex align-items-center gap-12 mt-8">
                        <div class="d-flex align-items-center p-8 rounded-16 border-gray-200 bg-gray-100 text-gray-500 font-12">
                            <x-iconsax-lin-video-play class="icons text-gray-400" width="16px" height="16px"/>
                            <span class="mx-4 font-weight-bold">{{ $post->author->getTeacherCoursesCount() }}</span>
                            <span class="">{{ trans('update.courses') }}</span>
                        </div>

                        <div class="d-flex align-items-center p-8 rounded-16 border-gray-200 bg-gray-100 text-gray-500 font-12">
                            <x-iconsax-lin-note-2 class="icons text-gray-400" width="16px" height="16px"/>
                            <span class="mx-4 font-weight-bold">{{ $post->author->blog_count }}</span>
                            <span class="">{{ trans('update.articles') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if(!empty($post->author->about))
                <div class="post-author-info-card__details-about my-16 text-gray-500 {{ (!empty($post->author->someRandomPosts) and count($post->author->someRandomPosts)) ? 'mb-16' : '' }}">{!! $post->author->about !!}</div>
            @endif

            @if(!empty($post->author->someRandomPosts) and count($post->author->someRandomPosts))
                <div class="mt-auto">
                    <h5 class="font-14">{{ trans('update.more_from_user', ['user' => $post->author->full_name]) }}</h5>
                    <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-24 mt-8 ">
                        @foreach($post->author->someRandomPosts as $authorPost)
                            <div class="d-flex align-items-center">
                                <div class="author-random-post-image rounded-8 bg-gray-200">
                                    <img src="{{ $authorPost->image }}" alt="{{ $authorPost->title }}" class="img-cover rounded-8">
                                </div>
                                <div class="ml-8">
                                    <a href="{{ $authorPost->getUrl() }}">
                                        <h3 class="font-14 text-dark">{{ truncate($authorPost->title, 30) }}</h3>
                                    </a>
                                    <span class="mt-8 font-12 text-gray-500">{{ dateTimeFormat($authorPost->created_at, 'j M Y') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="post-author-info-card__secondary-img position-relative">
            <img src="{{ $post->author->getProfileSecondaryImage() }}" alt="{{ $post->author->full_name }}" class="img-cover">
        </div>
    </div>
</div>
