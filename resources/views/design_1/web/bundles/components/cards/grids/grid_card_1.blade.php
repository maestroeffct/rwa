<a href="{{ $bundle->getUrl() }}" class="text-decoration-none d-block">
<div class="bundle-card position-relative">
        <div class="bundle-card__image bg-gray-200 rounded-16">
            <img src="{{ $bundle->getImage() }}" class="img-cover rounded-16" alt="{{ $bundle->title }}">
        </div>

    <div class="bundle-card__content d-flex flex-column rounded-16 p-16 bg-white">
            <h3 class="bundle-card__title font-16 text-dark">{{ $bundle->title }}</h3>

            <div>
                <a href="{{ $bundle->getUrl() }}" class="text-decoration-none">
        @include("design_1.web.components.rate", [
            'rate' => round($bundle->getRate(),1),
            'rateCount' => $bundle->getRateCount(),
            'rateClassName' => 'mt-8',
            'rateCountFont' => 'font-12',
        ])
                </a>
            </div>

            <div class="d-flex align-items-center mt-28 mb-20" onclick="event.stopPropagation()">
                <a href="{{ $bundle->teacher->getProfileUrl() }}" target="_blank" class="size-32 rounded-circle" onclick="event.stopPropagation()">
                    <img src="{{ $bundle->teacher->getAvatar(32) }}" class="img-cover rounded-circle" alt="{{ $bundle->teacher->full_name }}">
                </a>

            <div class="d-flex flex-column ml-4">
                    <a href="{{ $bundle->teacher->getProfileUrl() }}" target="_blank" class="font-14 font-weight-bold text-dark" onclick="event.stopPropagation()">{{ $bundle->teacher->full_name }}</a>

                @if(!empty($bundle->category))
                    <div class="d-inline-flex align-items-center gap-4 mt-2 font-14 text-gray-500">
                        <span class="">{{ trans('public.in') }}</span>
                            <a href="{{ $bundle->category->getUrl() }}" target="_blank" class="font-12 text-gray-500 text-ellipsis" onclick="event.stopPropagation()">{{ $bundle->category->title }}</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-auto">
            <div class="d-flex align-items-center">
                    <a href="{{ $bundle->getUrl() }}" class="d-flex align-items-center text-decoration-none" style="color: inherit;">
                <x-iconsax-lin-video-play class="icons text-gray-500" width="16px" height="16px"/>
                <span class="ml-4 font-14 text-gray-500">{{ count($bundle->bundleWebinars) }} {{ trans('product.courses') }}</span>
                    </a>
            </div>

            <div class="d-flex align-items-center font-16 font-weight-bold text-primary">
                    <a href="{{ $bundle->getUrl() }}" class="text-decoration-none text-primary">
                @include("design_1.web.bundles.components.price")
                    </a>
                </div>
            </div>
        </div>
    </div>
</a>
