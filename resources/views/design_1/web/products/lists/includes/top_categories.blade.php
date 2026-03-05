@if(!empty($topCategories) and count($topCategories))
    <div class="position-relative products-lists-top-categories z-index-1">

        <div class="swiper-button-next products-lists-top-categories__slider-navigation bg-transparent">
            <x-iconsax-lin-arrow-right-1 class="icons text-dark" width="16px" height="16px"/>
        </div>

        <div class="swiper-button-prev products-lists-top-categories__slider-navigation bg-transparent">
            <x-iconsax-lin-arrow-left-1 class="icons text-dark" width="16px" height="16px"/>
        </div>

        <div class="swiper-container js-make-swiper products-top-categories-slider pb-0 mx-16 mx-md-32"
             data-item="products-top-categories-slider"
             data-autoplay="true"
             data-loop="true"
             data-breakpoints="1440:5,769:3,320:1.1"
             data-navigation="true"
        >
            <div class="swiper-wrapper py-0">
                @foreach($topCategories as $topCategory)
                    <div class="swiper-slide">
                        <a href="{{ $topCategory->category->getUrl() }}" class="position-relative products-lists-top-category d-flex align-items-center p-12">
                            <div class="size-48 rounded-circle">
                                <img src="{{ $topCategory->image }}" alt="{{ $topCategory->category->title }}" class="img-cover rounded-circle">
                            </div>

                            <div class="ml-8">
                                <h5 class="font-14 text-dark">{{ $topCategory->category->title }}</h5>
                                <p class="mt-4 font-12 text-gray-500">{{ trans('update.n_products', ['count' => $topCategory->category->products_count]) }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
