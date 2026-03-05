@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("sliding_courses_hero") }}">
    @endpush

    <div class="sliding-courses-hero-section " @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
        <div class="container d-flex flex-column align-items-center text-center h-100">

            {{-- Upper Call to Action --}}
            @if(!empty($contents['upper_cta']))
                @if(!empty($contents['upper_cta']['icon']))
                    <div class="d-flex-center size-80 bg-white rounded-circle">
                        <img src="{{ $contents['upper_cta']['icon'] }}" alt="icon" class="img-fluid sliding-courses-hero-section__upper-cta-badge-icon">
                    </div>
                @endif

                @if(!empty($contents['upper_cta']['main_text']))
                    <a href="{{ !empty($contents['upper_cta']['url']) ? $contents['upper_cta']['url'] : '' }}" target="_blank" class="text-dark mt-12">{{ $contents['upper_cta']['main_text'] }}</a>
                @endif
            @endif

            {{-- Main Contents --}}
            @if(!empty($contents['main_content']))
                {{-- Title --}}
                <h1 class="d-inline-flex flex-column align-items-center text-center font-64 mt-24">
                    <div class="d-inline-flex align-items-center gap-8 font-64">
                        @if(!empty($contents['main_content']['title_line_1']))
                            <span class="text-dark">{{ $contents['main_content']['title_line_1'] }}</span>
                        @endif

                        @if(!empty($contents['main_content']['highlighted_word']))
                            <span class="text-primary">{{ $contents['main_content']['highlighted_word'] }}</span>
                        @endif
                    </div>

                    @if(!empty($contents['main_content']['title_line_2']))
                        <span class="mt-8 text-dark">{{ $contents['main_content']['title_line_2'] }}</span>
                    @endif
                </h1>

                {{-- Description --}}
                @if(!empty($contents['main_content']['description']))
                    <p class="mt-16 font-16 text-gray-500">{{ $contents['main_content']['description'] }}</p>
                @endif

                {{-- Buttons --}}
                @if(!empty($contents['main_content']['primary_button']) or !empty($contents['main_content']['secondary_button']))
                    <div class="d-flex align-items-lg-center flex-column flex-lg-row mt-32 gap-16">
                        {{-- Primary Button --}}
                        @if(!empty($contents['main_content']['primary_button']) and !empty($contents['main_content']['primary_button']['label']))
                            <a href="{{ !empty($contents['main_content']['primary_button']['url']) ? $contents['main_content']['primary_button']['url'] : '' }}" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white" data-text="{{ $contents['main_content']['primary_button']['label'] }}">
                                @if(!empty($contents['main_content']['primary_button']['icon']))
                                    @svg("iconsax-{$contents['main_content']['primary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                <span class="btn-flip-effect__text text-white">{{ $contents['main_content']['primary_button']['label'] }}</span>
                            </a>
                        @endif

                        {{-- Secondary Button --}}
                        @if(!empty($contents['main_content']['secondary_button']) and !empty($contents['main_content']['secondary_button']['label']))
                            <a href="{{ !empty($contents['main_content']['secondary_button']['url']) ? $contents['main_content']['secondary_button']['url'] : '' }}" class="btn-flip-effect btn-flip-effect__text-dark btn btn-xlg gap-8" data-text="{{ $contents['main_content']['secondary_button']['label'] }}">
                                @if(!empty($contents['main_content']['secondary_button']['icon']))
                                    @svg("iconsax-{$contents['main_content']['secondary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                <span class="btn-flip-effect__text text-dark">{{ $contents['main_content']['secondary_button']['label'] }}</span>
                            </a>
                        @endif
                    </div>
                @endif
            @endif
        </div>

        {{-- Courses --}}
        @php
            $ids = [];
            if(!empty($contents['featured_courses']) and is_array($contents['featured_courses'])) {
                foreach ($contents['featured_courses'] as $courseData) {
                    if (!empty($courseData['course'])){
                        $ids[] = $courseData['course'];
                    }
                }
            }

            $slidingCourses = $frontComponentsDataMixins->getCoursesByIds($ids);
        @endphp

        @if($slidingCourses->isNotEmpty())
            @if($slidingCourses->count() <= 4)
                <div class="container mt-0 mt-lg-40">
                    <div class="row">
                        @include('design_1.web.courses.components.cards.grids.index',['courses' => $slidingCourses, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-24"])
                    </div>
                </div>
            @else
                {{-- Slider --}}
                <div class="swiper-container js-make-swiper js-sliding-courses-hero-swiper mt-24 mt-lg-64"
                     data-item="js-sliding-courses-hero-swiper"
                     data-space-between="24"
                     data-autoplay="true"
                     data-loop="false"
                     data-speed="3000"
                     data-breakpoints="1600:6,1200:5,991:3,660:2,480:1.2"
                >
                    <div class="swiper-wrapper">
                        @include('design_1.web.courses.components.cards.grids.index',['courses' => $slidingCourses, 'gridCardClassName' => "swiper-slide"])
                    </div>
                </div>
            @endif
        @endif
    </div>
@endif
