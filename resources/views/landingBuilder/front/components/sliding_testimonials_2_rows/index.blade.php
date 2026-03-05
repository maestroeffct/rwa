@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
    @endphp


    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("sliding_testimonials_2_rows") }}">
    @endpush

    <div class="sliding-testimonials-2-rows-section position-relative">
        <div class="container">
            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="mt-16 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                    <p class="mt-20 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                @endif
            </div>
        </div>


        @php
            $ids = [];
            if (!empty($contents['testimonials']) and is_array($contents['testimonials'])) {
                foreach ($contents['testimonials'] as $testimonialData) {
                    if (!empty($testimonialData['testimonial_id'])) {
                        $ids[] = $testimonialData['testimonial_id'];
                    }
                }
            }

            $testimonials = $frontComponentsDataMixins->getTestimonialsByIds($ids);
        @endphp

        @if($testimonials->isNotEmpty())
            <div class="testimonials-scroll-wrapper position-relative w-100 overflow-hidden">
                <div class="testimonials-scroll-animation scroll-right-left mt-40">
                    @foreach([$testimonials, $testimonials] as $testimonialGroup)
                        @foreach($testimonialGroup as $testimonialRow)
                            @include('landingBuilder.front.components.sliding_testimonials_2_rows.testimonial_card',['testimonial' => $testimonialRow])
                        @endforeach
                    @endforeach
                </div>

                <div class="testimonials-scroll-animation scroll-left-right mt-32 pb-12">
                    @foreach([$testimonials, $testimonials] as $testimonialGroup)
                        @foreach($testimonialGroup as $testimonialRow)
                            @include('landingBuilder.front.components.sliding_testimonials_2_rows.testimonial_card',['testimonial' => $testimonialRow])
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endif

        @if(!empty($contents['cta_section']) and (!empty($contents['cta_section']['title_bold_text']) or !empty($contents['cta_section']['title_regular_text'])))
            <div class="container">
                <div class="d-flex align-items-center mt-40 {{ (empty($contents['cta_section']['floating_image'])) ? 'justify-content-center' : '' }}">
                    @if(!empty($contents['cta_section']['floating_image']))
                        <div class="sliding-testimonials-2-rows-section__cta-floating-img d-flex-center">
                            <img src="{{ $contents['cta_section']['floating_image'] }}" alt="floating_image" class="img-fluid" width="160px" height="160px">
                        </div>
                    @endif

                    <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-16 p-12 rounded-32 bg-white">
                        <div class="d-flex-center p-8 rounded-16 bg-warning">
                            @foreach([1,2,3,4,5] as $st)
                                <x-iconsax-bol-star-1 class="icons text-white" width="16px" height="16px"/>
                            @endforeach
                        </div>

                        <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-4">
                            @if(!empty($contents['cta_section']['title_bold_text']))
                                <h4 class="font-16 text-dark">{{ $contents['cta_section']['title_bold_text'] }}</h4>
                            @endif

                            @if(!empty($contents['cta_section']['title_regular_text']))
                                <div class="font-16 text-gray-500">{{ $contents['cta_section']['title_regular_text'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif
