@php
    if($itemType == 'product') {
        $itemImage = $item->thumbnail;
        $itemCreator = $item->creator;
        $taxType = "store";
    } else {
        $itemImage = $item->getImage();
        $itemCreator = $item->teacher;
        $taxType = "general";
    }
@endphp

<div class="gift-item-card">

    <a href="{{ $item->getUrl() }}">
        <div class="gift-item-card__image bg-gray-200">
            <img src="{{ $itemImage }}" class="img-cover" alt="{{ $item->title }}">
        </div>
    </a>

    <div class="gift-item-card__body d-flex flex-column p-12">
        <a href="{{ $item->getUrl() }}">
            <h3 class="course-title font-16 font-weight-bold text-dark">{{ clean($item->title,'title') }}</h3>
        </a>

        @include('design_1.web.components.rate', ['rate' => $item->getRate(), 'rateClassName' => 'mt-12'])

        <div class="d-flex align-items-center my-16">
            <div class="size-32 rounded-circle">
                <img src="{{ $itemCreator->getAvatar(32) }}" class="img-cover rounded-circle" alt="{{ $itemCreator->full_name }}">
            </div>

            <div class="ml-4">
                <a href="{{ $itemCreator->getProfileUrl() }}" target="_blank" class="font-14 font-weight-bold text-dark">{{ $itemCreator->full_name }}</a>
                <p class="mt-2 font-14 text-gray-500 text-ellipsis">{{ $itemCreator->bio }}</p>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-auto pt-12 border-top-gray-100">
            <div class="d-flex align-items-center font-16 font-weight-bold text-primary">
                @if($itemType == 'product')
                    @if($item->price > 0)
                        @php
                            $itemPriceWithActiveDiscountPrice = $item->getPriceWithActiveDiscountPrice();
                        @endphp

                        @if($itemPriceWithActiveDiscountPrice < $item->price)
                            <span class="">{{ ($itemPriceWithActiveDiscountPrice > 0) ? handlePrice($itemPriceWithActiveDiscountPrice, true, true, false, null, true, 'store') : trans('public.free') }}</span>
                            <span class="font-14 font-weight-400 text-gray-500 ml-8 text-decoration-line-through">{{ handlePrice($item->price, true, true, false, null, true, 'store') }}</span>
                        @else
                            <span class="">{{ handlePrice($item->price, true, true, false, null, true, 'store') }}</span>
                        @endif
                    @else
                        <span class="">{{ trans('public.free') }}</span>
                    @endif
                @else
                    @if($item->price > 0)
                        @if($item->bestTicket() < $item->price)
                            <span class="">{{ handlePrice($item->bestTicket(), true, true, false, null, true, $taxType) }}</span>
                            <span class="font-14 font-weight-400 text-gray-500 ml-8 text-decoration-line-through">{{ handlePrice($item->price, true, true, false, null, true, $taxType) }}</span>
                        @else
                            <span class="">{{ handlePrice($item->price, true, true, false, null, true, $taxType) }}</span>
                        @endif
                    @else
                        <span class="">{{ trans('public.free') }}</span>
                    @endif
                @endif
            </div>
        </div>

    </div>
</div>
