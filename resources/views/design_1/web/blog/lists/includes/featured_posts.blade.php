@if(!empty($featuredPosts) and $featuredPosts->isNotEmpty())
    <div class="d-flex-center flex-column text-center mt-48">
        <div class="d-flex align-items-center">
            <h2 class="font-32 text-dark mr-4">{{ trans('update.featured_posts') }}</h2>
            <img src="/assets/design_1/img/blog/featured_posts.svg" alt="{{ trans('update.featured_posts') }}" class="img-fluid" width="28px" height="28px">
        </div>
        <p class="mt-8 text-gray-500">{{ trans('update.we_have_many_customers_around_the_world_used_our_product_for_their_employees') }}</p>
    </div>

    <div class="position-relative mt-28">
        <div class="swiper-container js-make-swiper blog-featured-posts-slider pb-8"
             data-item="blog-featured-posts-slider"
             data-autoplay="true"
             data-loop="true"
             data-breakpoints="1440:2,769:1,320:1.1"
             data-pagination="blog-featured-posts-swiper-pagination"
        >
            <div class="swiper-wrapper py-0">
                @foreach($featuredPosts as $featuredPost)
                    <div class="swiper-slide ">
                        <a href="{{ $featuredPost->getUrl() }}" class="">
                            <div class="position-relative blog-featured-post">
                                <img src="{{ $featuredPost->image }}" alt="{{ $featuredPost->title }}" class="img-cover rounded-32">

                                <div class="blog-featured-post__content d-flex flex-column align-items-start justify-content-end p-16">
                                    <h4 class="font-16 text-white">{{ $featuredPost->title }}</h4>

                                    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-lg-between mt-12 w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="size-36 rounded-circle">
                                                <img src="{{ $featuredPost->author->getAvatar(36) }}" alt="{{ $featuredPost->author->full_name }}" class="img-cover rounded-circle">
                                            </div>
                                            <div class="ml-4">
                                                <span class="d-block font-12 font-weight-bold text-white">{{ $featuredPost->author->full_name }}</span>
                                                <span class="d-block font-12 text-white ">{{ truncate($featuredPost->author->bio, 38) }}</span>
                                            </div>
                                        </div>

                                        <div class="blog-featured-post__content-stats d-inline-flex align-items-center px-12 py-10 mt-8 mt-lg-0">
                                            <div class="d-flex align-items-center">
                                                <x-iconsax-lin-calendar-2 class="icons text-white" width="16px" height="16px"/>
                                                <span class="ml-4 font-12 text-white">{{ dateTimeFormat($featuredPost->created_at, 'j M Y') }}</span>
                                            </div>

                                            @if(!empty($featuredPost->study_time))
                                                <div class="blog-featured-post__content-stats-separator"></div>

                                                <div class="d-flex align-items-center">
                                                    <x-iconsax-lin-clock-1 class="icons text-white" width="16px" height="16px"/>
                                                    <span class="ml-4 font-12 text-white">{{ $featuredPost->study_time }} {{ trans('update.min') }}</span>
                                                </div>
                                            @endif

                                            <div class="blog-featured-post__content-stats-separator"></div>

                                            <div class="d-flex align-items-center">
                                                <x-iconsax-lin-message class="icons text-white" width="16px" height="16px"/>
                                                <span class="ml-4 font-12 text-white">{{ $featuredPost->comments_count }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="position-relative d-flex justify-content-center mt-40">
                <div class="swiper-pagination blog-featured-posts-swiper-pagination"></div>
            </div>

        </div>
    </div>
@endif
