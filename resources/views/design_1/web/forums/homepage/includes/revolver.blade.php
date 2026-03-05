@php
    $revolverSettings = getForumsHomepageRevolverSettings();
@endphp

@if(!empty($revolverSettings) and !empty($revolverSettings['revolver_items']) and count($revolverSettings['revolver_items']))
    <div class="forum-revolver position-relative bg-primary z-index-1">
        <div class="forum-revolver__slider position-relative">
            <div class="forum-revolver__slider-infinite d-flex align-items-center gap-32 flex-wrap">

                <div class="swiper-container js-make-swiper marquee-swiper js-marquee-swiper-forums"
                     data-item="js-marquee-swiper-forums"
                     data-slides-per-view="auto"
                     data-space-between="32"
                     data-autoplay="true"
                     data-loop="true"
                     data-centered-slides="true"
                     data-freeMode="true"
                     data-autoplay-delay="0"
                     data-speed="3000"
                     data-disable-touch-move="true"
                >
                    <div class="swiper-wrapper">

                        @foreach($revolverSettings['revolver_items'] as $revolverItem)
                            @if(!empty($revolverItem['title']) and !empty($revolverItem['link']))
                                <div class="swiper-slide">
                                    <a href="{{ $revolverItem['link'] }}" target="_blank" class="font-24 font-weight-bold text-white">{{ $revolverItem['title'] }}</a>

                                    <div class="size-24">
                                        @if(!empty($revolverSettings['separator_image']))
                                            <img src="{{ $revolverSettings['separator_image'] }}" alt="{{ trans('update.separator_image') }}" class="img-cover">
                                        @else
                                            <x-iconsax-bul-triangle class="icons text-white" width="24px" height="24px"/>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                </div>


            </div>
        </div>
    </div>
@endif
