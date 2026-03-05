<a href="{{ $product->getUrl() }}" class="text-decoration-none d-block">
    <div class="product-card position-relative">
        <div class="product-card__mask"></div>

        <div class="position-relative d-flex p-12 rounded-16 bg-white z-index-2">
            <div class="product-card__image rounded-16">
                <img src="{{ $product->thumbnail }}" class="img-cover rounded-16" alt="{{ $product->title }}">
            </div>

            <div class="product-card__content ml-12 flex-1 d-flex flex-column">
                <h3 class="product-card__title font-weight-bold font-16 text-dark">{{ clean($product->title,'title') }}</h3>

                <div class="d-flex align-items-center my-16" onclick="event.stopPropagation()">
                    <a href="{{ $product->creator->getProfileUrl() }}" target="_blank" class="size-32 rounded-circle" onclick="event.stopPropagation()">
                        <img src="{{ $product->creator->getAvatar(32) }}" class="img-cover rounded-circle" alt="{{ $product->creator->full_name }}">
                    </a>

                    <div class="d-flex flex-column ml-4">
                        <a href="{{ $product->creator->getProfileUrl() }}" target="_blank" class="font-14 font-weight-bold text-dark" onclick="event.stopPropagation()">{{ $product->creator->full_name }}</a>

                        @if(!empty($product->category))
                            <div class="d-inline-flex align-items-center gap-4 mt-2 font-14 text-gray-500">
                                <span class="">{{ trans('public.in') }}</span>
                                <a href="{{ $product->category->getUrl() }}" target="_blank" class="font-14 text-gray-500 text-ellipsis" onclick="event.stopPropagation()">{{ $product->category->title }}</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-auto d-flex align-items-center justify-content-between">

                    <div class="">
                        <a href="{{ $product->getUrl() }}" class="text-decoration-none">
                            @include("design_1.web.components.rate", [
                                'rate' => round($product->getRate(),1),
                                'rateCount' => $product->getRateCount(),
                                'rateClassName' => '',
                            ])
                        </a>
                    </div>

                    @if($product->getAvailability() > 0)
                        <div class="d-flex align-items-center font-16 font-weight-bold text-primary">
                            <a href="{{ $product->getUrl() }}" class="text-decoration-none text-primary">
                                @if($product->price > 0)
                                    @php
                                        $productPriceWithActiveDiscountPrice = $product->getPriceWithActiveDiscountPrice();
                                    @endphp

                                    @if($productPriceWithActiveDiscountPrice < $product->price)
                                        <span class="">{{ ($productPriceWithActiveDiscountPrice > 0) ? handlePrice($productPriceWithActiveDiscountPrice, true, true, false, null, true, 'store') : trans('public.free') }}</span>
                                        <span class="font-14 font-weight-400 text-gray-500 ml-8 text-decoration-line-through">{{ handlePrice($product->price, true, true, false, null, true, 'store') }}</span>
                                    @else
                                        <span class="">{{ handlePrice($product->price, true, true, false, null, true, 'store') }}</span>
                                    @endif
                                @else
                                    <span class="">{{ trans('public.free') }}</span>
                                @endif
                            </a>
                        </div>
                    @else
                        <div class="d-flex align-items-center font-16 font-weight-bold text-gray-500">
                            <a href="{{ $product->getUrl() }}" class="text-decoration-none" style="color: inherit;">
                                <span class="">{{ trans('update.out_of_stock') }}</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</a>
