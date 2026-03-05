@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
        $upcomingCourses = $frontComponentsDataMixins->getUpcomingCoursesData();
    @endphp

    @if($upcomingCourses->isNotEmpty())
        @push('styles_top')
            <link rel="stylesheet" href="{{ getLandingComponentStylePath("upcoming_courses") }}">
        @endpush

        <div class="container">
            <div class="upcoming-courses-section position-relative " @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>

                <div class="d-flex-center flex-column text-center">
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                        <div class="d-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                    @endif

                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                        <h2 class="mt-8 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                    @endif

                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['subtitle']))
                        <p class="mt-16 font-16 text-gray-500">{!! nl2br($contents['main_content']['subtitle']) !!}</p>
                    @endif
                </div>

                <div class="row">
                    @include('design_1.web.upcoming_courses.components.cards.grids.index',['upcomingCourses' => $upcomingCourses, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-28"])
                </div>

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                    <div class="d-flex-center flex-column mt-40">
                        <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white" data-text="{{ $contents['main_content']['button']['label'] }}">
                            @if(!empty($contents['main_content']['button']['icon']))
                                @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                            @endif

                            <span class="btn-flip-effect__text">{{ $contents['main_content']['button']['label'] }}</span>
                        </a>
                    </div>
                @endif

            </div>
        </div>
    @endif
@endif
