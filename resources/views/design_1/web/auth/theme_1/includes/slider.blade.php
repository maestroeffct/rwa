@php
    $themeSettings = getThemeAuthenticationPagesSettings();
    $sliderBg = (!empty($themeSettings) and !empty($themeSettings['slider_background_image'])) ? $themeSettings['slider_background_image'] : null;
    $sliders = (!empty($themeSettings) and !empty($themeSettings['slider_contents']) and is_array($themeSettings['slider_contents'])) ? $themeSettings['slider_contents'] : [];
@endphp

<div class="auth-slider-container w-100 rounded-16 bg-gray-100" @if(!empty($sliderBg)) style="background-image: url('{{ $sliderBg }}')" @endif>
    @if(!empty($sliders))
        <div class="position-relative h-100 w-100">
            <div class="swiper-container js-make-swiper auth-theme-slider pb-0 h-100"
                 data-item="auth-theme-slider"
                 data-autoplay="true"
                 data-loop="true"
                 data-pagination="auth-theme-slider-pagination"
            >
                <div class="swiper-wrapper py-0 ">
                    @foreach($sliders as $slider)
                        <div class="swiper-slide">
                            <div class="d-flex-center flex-column text-center h-90 p-16">
                                @if(!empty($slider['image']))
                                    <div class="auth-slider-image d-flex-center">
                                        <img src="{{ $slider['image'] }}" alt="image" class="img-fluid">
                                    </div>
                                @endif

                                @if(!empty($slider['title']))
                                    <h4 class="font-16 mt-16">{{ $slider['title'] }}</h4>
                                @endif

                                @if(!empty($slider['subtitle']))
                                    <div class="font-14 mt-8 text-gray-500">{{ $slider['subtitle'] }}</div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="swiper-pagination auth-theme-slider-pagination"></div>
            </div>
        </div>
    @endif
</div>
