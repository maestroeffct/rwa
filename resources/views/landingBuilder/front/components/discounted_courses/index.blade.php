@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
        $hasDiscountWebinars = $frontComponentsDataMixins->getDiscountedCoursesData();
    @endphp

    @if($hasDiscountWebinars->isNotEmpty())
        @push('styles_top')
            <link rel="stylesheet" href="{{ getLandingComponentStylePath("discounted_courses") }}">
        @endpush

        <div class="container">
            <div class="discounted-courses-section position-relative " @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>

                <div class="">
                    @if(!empty($contents['main_content']))
                        @if(!empty($contents['main_content']['title']))
                            <h2 class="font-32 text-white">{{ $contents['main_content']['title'] }}</h2>
                        @endif

                        @if(!empty($contents['main_content']['subtitle']))
                            <p class="mt-12 font-16 text-white opacity-70">{!! nl2br($contents['main_content']['subtitle']) !!}</p>
                        @endif

                        @if(!empty($contents['main_content']['icon']))
                            <div class="discounted-courses-section__floating-icon d-flex-center">
                                <img src="{{ $contents['main_content']['icon'] }}" alt="icon">
                            </div>
                        @endif
                    @endif
                </div>

                <div class="row">
                    @include('design_1.web.courses.components.cards.grids.index',['courses' => $hasDiscountWebinars, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-24"])
                </div>

                @if(!empty($contents['cta_section']))
                    @php
                        $discountedCourseHasCtaContents = true;

                        if (
                            (empty($contents['cta_section']['button']) or empty($contents['cta_section']['button']['label'])) and
                            empty($contents['cta_section']['icon']) and
                            empty($contents['cta_section']['title_bold_text']) and
                            empty($contents['cta_section']['title_regular_text'])
                        ) {
                            $discountedCourseHasCtaContents = false;
                        }
                    @endphp

                    @if($discountedCourseHasCtaContents)
                        <div class="d-flex-center flex-column text-center mt-32">
                            @if(!empty($contents['cta_section']['button']) and !empty($contents['cta_section']['button']['label']))
                                <a href="{{ !empty($contents['cta_section']['button']['url']) ? $contents['cta_section']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white mb-16" data-text="{{ $contents['cta_section']['button']['label'] }}">
                                    @if(!empty($contents['cta_section']['button']['icon']))
                                        @svg("iconsax-{$contents['cta_section']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                    @endif

                                    <span class="btn-flip-effect__text">{{ $contents['cta_section']['button']['label'] }}</span>
                                </a>
                            @endif

                            <div class="d-flex align-items-center gap-4 text-white">
                                @if(!empty($contents['cta_section']['icon']))
                                    @svg("iconsax-{$contents['cta_section']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                @if(!empty($contents['cta_section']['title_bold_text']))
                                    <span class="font-weight-bold font-16 opacity-70">{{ $contents['cta_section']['title_bold_text'] }}</span>
                                @endif

                                @if(!empty($contents['cta_section']['title_regular_text']))
                                    <span class="opacity-70 font-16">{{ $contents['cta_section']['title_regular_text'] }}</span>
                                @endif
                            </div>

                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif
@endif
