@if(!empty($featuredCategories) and count($featuredCategories))
    <div class="position-relative mt-48">

        <div class="swiper-container js-make-swiper products-featured-categories-slider pb-0"
             data-item="products-featured-categories-slider"
             data-aufeaturedlay="true"
             data-loop="true"
             data-breakpoints="1440:5.2,769:3.2,320:1.3"
        >
            <div class="swiper-wrapper py-0 mx-16 mx-md-32">
                @foreach($featuredCategories as $featuredCategory)
                    <div class="swiper-slide">
                        <a href="{{ $featuredCategory->category->getUrl() }}" class="">
                            <div class="position-relative products-featured-category">
                                <img src="{{ $featuredCategory->image }}" alt="{{ $featuredCategory->category->title }}" class="img-cover rounded-24">

                                <div class="products-featured-category__content d-flex flex-column align-items-start rounded-24 py-16">
                                    <div class="px-16">
                                        <span class="products-featured-category__product-count d-inline-flex-center px-8 py-4 rounded-24 font-12 text-white">{{ trans('update.n_products', ['count' => $featuredCategory->category->products_count]) }}</span>
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
