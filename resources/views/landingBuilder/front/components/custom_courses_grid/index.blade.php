@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
    @endphp


    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("custom_courses_grid") }}">
    @endpush

    <div class="container">
        <div class="custom-courses-grid-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>

            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="mt-12 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                    <p class="mt-16 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                @endif
            </div>

            {{-- List --}}
            @php
                $ids = [];
                if(!empty($contents['featured_courses']) and is_array($contents['featured_courses'])) {
                    foreach ($contents['featured_courses'] as $courseData) {
                        if (!empty($courseData['course'])){
                            $ids[] = $courseData['course'];
                        }
                    }
                }

                $courses = $frontComponentsDataMixins->getCoursesByIds($ids);
            @endphp

            @if($courses->isNotEmpty())
                <div class="row">
                    @include('design_1.web.courses.components.cards.grids.index',['courses' => $courses, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-24"])
                </div>
            @endif

            {{-- Primary Button --}}
            @if(!empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                <div class="d-flex-center flex-column text-center mt-24">

                    <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white" data-text="{{ $contents['main_content']['button']['label'] }}">
                        @if(!empty($contents['main_content']['button']['icon']))
                            @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                        @endif

                        <span class="btn-flip-effect__text text-white">{{ $contents['main_content']['button']['label'] }}</span>
                    </a>

                </div>
            @endif

            {{-- floating_images --}}
            @if(!empty($contents['floating_images']))
                @if(!empty($contents['floating_images']['image_1']))
                    <div class="custom-courses-grid-section__floating-image-1 d-flex-center">
                        <img src="{{ $contents['floating_images']['image_1'] }}" alt="floating image 1">
                    </div>
                @endif

                @if(!empty($contents['floating_images']['image_2']))
                    <div class="custom-courses-grid-section__floating-image-2 d-flex-center">
                        <img src="{{ $contents['floating_images']['image_2'] }}" alt="floating image 2">
                    </div>
                @endif
            @endif
        </div>
    </div>
@endif
