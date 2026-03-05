<a href="{{ $post->getUrl() }}" class="text-decoration-none d-block">
    <div class="blog-section__post-card position-relative rounded-24 {{ !empty($className) ? $className : '' }}">
        <div class="position-relative h-100">
            <img src="{{ $post->image }}" alt="{{ $post->title }}" class="blog-section__post-card-img img-cover rounded-24">
        </div>

        <div class="blog-section__post-card-footer p-16">
            <div class="d-flex flex-column justify-content-end w-100 h-100">
                <h3 class="font-16 text-white">{{ $post->title }}</h3>

                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-12">
                    <div class="d-flex align-items-center">
                        <div class="size-36 rounded-circle bg-gray-100">
                            <img src="{{ $post->author->getAvatar(36) }}" alt="{{ $post->author->ful_name }}" class="img-cover rounded-circle">
                        </div>
                        <div class="ml-4">
                            <h5 class="font-14 text-white">{{ $post->author->full_name }}</h5>
                            <p class="font-12 text-white mt-2">{{ trans('public.in') }} {{ $post->category->title }}</p>
                        </div>
                    </div>

                    @if(!empty($showPostStats))
                        <div class="position-relative d-inline-flex align-items-center rounded-16 px-12 py-10 bg-dark-20">
                            <div class="d-flex align-items-center">
                                <x-iconsax-lin-calendar-2 class="icons text-white" width="16px" height="16px"/>
                                <span class="ml-4 font-14 text-white">{{ dateTimeFormat($post->created_at, 'j M Y') }}</span>
                            </div>

                            <div class="blog-section__post-card-footer-divider"></div>

                            <div class="d-flex align-items-center">
                                <x-iconsax-lin-clock-1 class="icons text-white" width="16px" height="16px"/>
                                <span class="ml-4 font-14 text-white">{{ $post->study_time }}</span>
                            </div>

                            <div class="blog-section__post-card-footer-divider"></div>

                            <div class="d-flex align-items-center">
                                <x-iconsax-lin-message class="icons text-white" width="16px" height="16px"/>
                                <span class="ml-4 font-14 text-white">{{ $post->comments_count }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</a>
