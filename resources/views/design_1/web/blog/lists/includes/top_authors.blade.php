@if(!empty($featuredAuthors) and $featuredAuthors->isNotEmpty())
    <div class="blog-top-authors position-relative mt-36">
        <div class="blog-top-authors__image-1">
            <img src="/assets/design_1/img/blog/top_authors_2.svg" alt="image 1" class="img-fluid">
        </div>

        <div class="blog-top-authors__image-2">
            <img src="/assets/design_1/img/blog/top_authors_3.svg" alt="image 1" class="img-fluid">
        </div>


        <div class="bg-white rounded-24">
            <div class="d-flex-center flex-column text-center pt-16">
                <div class="d-flex align-items-center">
                    <h2 class="font-32 text-dark mr-4">{{ trans('update.top_authors') }}</h2>
                    <img src="/assets/design_1/img/blog/top_authors.svg" alt="{{ trans('update.top_authors') }}" class="img-fluid" width="28px" height="28px">
                </div>
                <p class="mt-8 text-gray-500">{{ trans('update.we_have_many_customers_around_the_world_used_our_product_for_their_employees') }}</p>
            </div>

            <div class="blog-top-authors__slider-card position-relative mt-16 pt-12 ">
                <div class="blog-top-authors__slider-card-mask"></div>

                <div class="position-relative z-index-2 h-100">
                    <div class="swiper-container js-make-swiper blog-featured-authors-slider pb-8 h-100"
                         data-item="blog-featured-authors-slider"
                         data-autoplay="true"
                         data-loop="true"
                         data-breakpoints="1440:6.3,769:4.4,320:2.1"
                    >
                        <div class="swiper-wrapper py-0 mx-16 mx-md-32 h-100">
                            @foreach($featuredAuthors as $featuredAuthor)
                                <div class="swiper-slide d-flex align-items-center ">
                                    <a href="/blog?author={{ $featuredAuthor->username }}" class="d-flex align-items-center">
                                        <div class="size-56 rounded-circle border-4 border-gray-200">
                                            <img src="{{ $featuredAuthor->getAvatar(48) }}" alt="{{ $featuredAuthor->full_name }}" class="img-cover rounded-circle">
                                        </div>
                                        <div class="ml-8">
                                            <span class="d-block font-weight-bold text-gray-500">{{ truncate($featuredAuthor->full_name, 13) }}</span>
                                            <span class="d-block font-12 text-gray-500">{{ trans("update.n_posts", ["count" => $featuredAuthor->blog_count]) }}</span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
