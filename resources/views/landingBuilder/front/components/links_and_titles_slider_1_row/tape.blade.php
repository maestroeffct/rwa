<div class="swiper-container js-make-swiper marquee-swiper js-marquee-swiper-{{ $className }} {{ $className }}"
     data-item="js-marquee-swiper-{{ $className }}"
     data-slides-per-view="auto"
     data-space-between="32"
     data-autoplay="true"
     data-loop="true"
     data-centered-slides="true"
     data-freeMode="true"
     data-reverse-direction="{{ $reverseDirection ? 'true' : 'false' }}"
     data-autoplay-delay="0"
     data-speed="3000"
     data-disable-touch-move="true"
>
    <div class="swiper-wrapper">

        @foreach($contents['title_items'] as $marqueItem)
            @if(!empty($marqueItem['title']) and !empty($marqueItem['url']))
                <div class="swiper-slide">
                    <a href="{{ $marqueItem['url'] }}" target="_blank" class="marquee-item">{{ $marqueItem['title'] }}</a>

                    @if(!empty($contents['separator_icon']))
                        <div class="tape-separator-icon">
                            @svg("iconsax-{$contents['separator_icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-white"])
                        </div>
                    @endif
                </div>
            @endif
        @endforeach

    </div>
</div>
