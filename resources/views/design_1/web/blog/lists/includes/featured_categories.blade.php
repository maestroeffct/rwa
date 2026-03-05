@if(!empty($featuredCategories) and $featuredCategories->isNotEmpty())
    <div class="position-relative mt-28">
        <div class="swiper-container js-make-swiper blog-featured-categories-slider pb-0"
             data-item="blog-featured-categories-slider"
             data-autoplay="true"
             data-loop="true"
             data-breakpoints="1440:5,976:3,320:1.1"
        >
            <div class="swiper-wrapper py-0">
                @foreach($featuredCategories as $featuredCategory)
                    <div class="swiper-slide">
                        <a href="{{ $featuredCategory->category->getUrl() }}" class="">
                            <div class="position-relative blog-featured-category">
                                <img src="{{ $featuredCategory->thumbnail }}" alt="{{ $featuredCategory->category->title }}" class="img-cover rounded-24">

                                <div class="blog-featured-category__content d-flex flex-column align-items-start rounded-24 py-16">
                                    <div class="px-16">
                                        <span class="blog-featured-category__content-posts-count d-inline-flex-center px-8 py-4 rounded-16 font-12 text-white">{{ trans('update.n_posts', ['count' => $featuredCategory->category->blog_count]) }}</span>
                                    </div>

                                    <div class="card-before-line card-before-line__4-12 px-16 mt-auto">
                                        <h5 class="font-14 text-white">{{ $featuredCategory->category->title }}</h5>
                                        <p class="mt-4 text-white font-12">{{ truncate($featuredCategory->category->subtitle, 60) }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
