@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
        $newestCourses = $frontComponentsDataMixins->getNewestCoursesData();
    @endphp

    @if($newestCourses->isNotEmpty())

        @push('styles_top')
            <link rel="stylesheet" href="{{ getLandingComponentStylePath("newest_courses") }}">
        @endpush

        <div class="newest-courses-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
            <div class="container position-relative">

                <div class="d-flex-center flex-column text-center">
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                        <div class="d-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                    @endif

                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                        <h2 class="mt-8 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                    @endif

                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['subtitle']))
                        <p class="mt-12 font-16 text-gray-500">{{ $contents['main_content']['subtitle'] }}</p>
                    @endif
                </div>

                {{-- Newest Courses --}}
                <div class="row">
                    @include('design_1.web.courses.components.cards.grids.index',['courses' => $newestCourses, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-24"])
                </div>


                {{-- CTA --}}
                @if(!empty($contents['cta_section']))
                    <div class="d-flex flex-column flex-lg-row gap-24 align-items-lg-center justify-content-lg-between mt-32">
                        <div class="">
                            <div class="d-flex align-items-center font-16 gap-4">
                                @if(!empty($contents['cta_section']['icon']))
                                    @svg("iconsax-{$contents['cta_section']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                @if(!empty($contents['cta_section']['title_bold_text']))
                                    <span class="font-weight-bold">{{ $contents['cta_section']['title_bold_text'] }}</span>
                                @endif

                                @if(!empty($contents['cta_section']['title_regular_text']))
                                    <span class="">{{ $contents['cta_section']['title_regular_text'] }}</span>
                                @endif
                            </div>

                            @if(!empty($contents['cta_section']['subtitle']))
                                <p class="mt-16 font-14 text-gray-500">{{ $contents['cta_section']['subtitle'] }}</p>
                            @endif
                        </div>

                        @if(!empty($contents['cta_section']['button']) and !empty($contents['cta_section']['button']['label']))
                            <a href="{{ !empty($contents['cta_section']['button']['url']) ? $contents['cta_section']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white" data-text="{{ $contents['cta_section']['button']['label'] }}">
                                @if(!empty($contents['cta_section']['button']['icon']))
                                    @svg("iconsax-{$contents['cta_section']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                <span class="btn-flip-effect__text">{{ $contents['cta_section']['button']['label'] }}</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif
@endif
