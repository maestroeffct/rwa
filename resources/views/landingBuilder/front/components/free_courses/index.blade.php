@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $freeCoursesBackgroundColor = "secondary";
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        if (!empty($contents['background_color'])) {
            $freeCoursesBackgroundColor = $contents['background_color'];
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
        $freeWebinars = $frontComponentsDataMixins->getFreeCoursesData();
    @endphp

    @if($freeWebinars->isNotEmpty())
        @push('styles_top')
            <link rel="stylesheet" href="{{ getLandingComponentStylePath("free_courses") }}">
        @endpush

        <div class="free-courses-section position-relative" style="background-color: var({{ "--".$freeCoursesBackgroundColor }}); {{ (!empty($contents['background']) ? "background-image: url({$contents['background']}); " : '') }}"v>
            <div class="container py-40 py-lg-80">
                <div class="d-flex-center flex-column text-center">
                    @if(!empty($contents['main_content']))
                        @if(!empty($contents['main_content']['title']))
                            <h2 class="font-32 text-white">{{ $contents['main_content']['title'] }}</h2>
                        @endif

                        @if(!empty($contents['main_content']['subtitle']))
                            <p class="mt-12 font-16 text-white opacity-70">{!! nl2br($contents['main_content']['subtitle']) !!}</p>
                        @endif
                    @endif
                </div>

                <div class="row">
                    @include('design_1.web.courses.components.cards.grids.index',['courses' => $freeWebinars, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-28"])
                </div>

                @if(!empty($contents['cta_section']))
                    @php
                        $freeCourseHasCtaContents = true;

                        if (
                            empty($contents['cta_section']['title']) and
                            empty($contents['cta_section']['description']) and
                            empty($contents['cta_section']['link_title']) and
                            empty($contents['cta_section']['icon'])
                        ) {
                            $freeCourseHasCtaContents = false;
                        }
                    @endphp

                    @if($freeCourseHasCtaContents)
                        <div class="row justify-content-center mt-48 mt-lg-64">
                            <div class="col-12 col-md-8 col-lg-6">
                                <div class="d-flex align-items-start justify-content-between gap-40 gap-lg-80">
                                    <div class="">
                                        @if(!empty($contents['cta_section']['title']))
                                            <h3 class="font-24 text-white">{{ $contents['cta_section']['title'] }}</h3>
                                        @endif

                                        @if(!empty($contents['cta_section']['description']))
                                            <p class="text-white font-16 opacity-70 mt-8">{{ $contents['cta_section']['description'] }}</p>
                                        @endif

                                        @if(!empty($contents['cta_section']['link_title']))
                                            <a href="{{ !empty($contents['cta_section']['url']) ? $contents['cta_section']['url'] : '' }}" class="font-16 btn-flip-effect btn-flip-effect__right-0 d-inline-flex align-items-center mt-12 font-weight-bold text-white" data-text="{{ $contents['cta_section']['link_title'] }}">
                                                <x-iconsax-lin-arrow-right class="icons text-white" width="24px" height="24px"/>
                                                <span class="btn-flip-effect__text ml-4">{{ $contents['cta_section']['link_title'] }}</span>
                                            </a>
                                        @endif
                                    </div>

                                    @if(!empty($contents['cta_section']['icon']))
                                        <div class="free-courses-section__cta-icon d-flex-center">
                                            <img src="{{ $contents['cta_section']['icon'] }}" alt="icon">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif
@endif
