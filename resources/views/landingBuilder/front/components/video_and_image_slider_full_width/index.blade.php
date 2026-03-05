@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("video_and_image_slider_full_width") }}">
    @endpush

    @push('scripts_bottom')
        <script src="{{ getLandingComponentScriptPath("video_and_image_slider_full_width") }}"></script>
    @endpush

    <div class="video-and-image-slider-full-width-section position-relative">
        <div class="container">
            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="mt-8 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                    <p class="mt-16 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                @endif

            </div>
        </div>


        @if(!empty($contents['specific_sliders']) and is_array($contents['specific_sliders']))
            @if(!empty($contents['enable_slider']) and $contents['enable_slider'] == "on")
                <div class="position-relative mt-24">
                    <div class="swiper-container js-make-swiper video-and-image-slider-full-width-swiper pb-24 px-24"
                         data-item="video-and-image-slider-full-width-swiper"
                         data-autoplay="true"
                         data-loop="true"
                         data-centered-slides="true"
                         data-autoplay-delay="5000"
                         data-breakpoints="1200:1.4,991:1.1,660:1.1"
                    >
                        <div class="swiper-wrapper py-8">
                            @foreach($contents['specific_sliders'] as $sliderDataRow)
                                @if(!empty($sliderDataRow['title']) and !empty($sliderDataRow['description']) and !empty($sliderDataRow['enable']) and $sliderDataRow['enable'] == "on")
                                    <div class="swiper-slide">
                                        @include('landingBuilder.front.components.video_and_image_slider_full_width.slider_item', ['sliderData' => $sliderDataRow])
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="container">
                    @foreach($contents['specific_sliders'] as $sliderDataRow)
                        @if(!empty($sliderDataRow['title']) and !empty($sliderDataRow['description']) and !empty($sliderDataRow['enable']) and $sliderDataRow['enable'] == "on")
                            <div class="mt-24">
                                @include('landingBuilder.front.components.video_and_image_slider_full_width.slider_item', ['sliderData' => $sliderDataRow])
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        @endif
    </div>
@endif
