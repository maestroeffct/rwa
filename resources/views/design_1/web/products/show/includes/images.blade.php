<div class="product-show-thumbnail-card position-relative bg-gray-100 rounded-24">
    <img src="{{ $product->thumbnail }}" alt="{{ $product->title }}" class="js-product-main-image img-cover rounded-24 p-16">

    @if(!empty($product->video_demo))
        <div class="product-show__has-video-icon d-flex-center size-120 rounded-circle">
            <x-iconsax-bol-play class="icons text-white" width="45px" height="45px"/>
        </div>
    @endif
</div>

<div class="product-show__slide-images-card mt-16" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
    <div class="d-flex align-items-center gap-16">
        <div class="js-product-other-image position-relative product-show__slide-image-item d-flex-center rounded-24 bg-gray-100 cursor-pointer">
            <img src="{{ $product->thumbnail }}" alt="{{ $product->title }}" class="img-cover rounded-24">

            @if(!empty($product->video_demo))
                <div class="product-show__has-video-icon d-flex-center size-48 rounded-circle">
                    <x-iconsax-bol-play class="icons text-white" width="18px" height="18px"/>
                </div>
            @endif
        </div>

        @if(!empty($product->images) and count($product->images))
            @foreach($product->images as $image)
                <div class="js-product-other-image position-relative product-show__slide-image-item d-flex-center rounded-24 bg-gray-100 cursor-pointer">
                    <img src="{{ $image->path }}" alt="{{ $product->title }}" class="img-cover rounded-24">
                </div>
            @endforeach
        @endif
    </div>
</div>
