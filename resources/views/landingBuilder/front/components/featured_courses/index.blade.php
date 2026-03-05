@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("featured_courses") }}">
    @endpush

    <div class="featured-courses-section position-relative">

        @if(!empty($contents['floating_background']))
            <div class="featured-courses-section__floating-background d-flex-center">
                <img src="{{ $contents['floating_background'] }}" alt="floating_background" class="">
            </div>
        @endif

        <div class="container">
            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="mt-8 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                    <p class="mt-16 font-16 text-gray-500">{{ $contents['main_content']['description'] }}</p>
                @endif
            </div>

            @if(!empty($contents['featured_courses']) and is_array($contents['featured_courses']))
                @if(!empty($contents['enable_slider']) and $contents['enable_slider'] == "on")
                    <div class="position-relative mt-16">
                        <div class="swiper-container js-make-swiper featured-courses-swiper pb-24"
                             data-item="featured-courses-swiper"
                             data-autoplay="true"
                             data-autoplay-delay="5000"
                        >
                            <div class="swiper-wrapper py-8">
                                @foreach($contents['featured_courses'] as $featuredRow)
                                    <div class="swiper-slide">
                                        @include('landingBuilder.front.components.featured_courses.featured_card', ['featured' => $featuredRow, 'disableOverlayImage' => true ])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    @foreach($contents['featured_courses'] as $featuredRow)
                        <div class="{{ $loop->first ? 'mt-24' : '' }} {{ (!empty($featuredRow['overlay_image'])) ? 'mb-56' : 'mb-16' }}">
                            @include('landingBuilder.front.components.featured_courses.featured_card', ['featured' => $featuredRow])
                        </div>
                    @endforeach
                @endif
            @endif
        </div>
    </div>
@endif
