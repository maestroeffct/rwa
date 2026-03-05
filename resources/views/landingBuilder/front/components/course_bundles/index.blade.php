@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
        $latestBundles = $frontComponentsDataMixins->getCourseBundlesData();
    @endphp

    @if($latestBundles->isNotEmpty())
        @push('styles_top')
            <link rel="stylesheet" href="{{ getLandingComponentStylePath("course_bundles") }}">
        @endpush

        <div class="course-bundles-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
            <div class="container position-relative h-100">

                @if(!empty($contents['floating_icon_1']))
                    <div class="course-bundles-section__floating-icon-1">
                        <img src="{{ $contents['floating_icon_1'] }}" alt="floating_icon_1">
                    </div>
                @endif

                <div class="row h-100">
                    <div class="col-12 col-lg-4 position-relative h-100 pt-40">
                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                            <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                        @endif

                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                            <h2 class="mt-8 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                        @endif

                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['subtitle']))
                            <p class="mt-20 font-16 text-gray-500">{!! nl2br($contents['main_content']['subtitle']) !!}</p>
                        @endif

                        {{-- Statistic --}}
                        @if(!empty($contents['statistics']) and (!empty($contents['statistics']['title']) or !empty($contents['statistics']['subtitle'])))
                            <div class="mt-32">
                                @if(!empty($contents['statistics']['title']))
                                    <div class="course-bundles-section__statistics-title d-flex align-items-center gap-8">
                                        <span class="line"></span>
                                        <span class="font-24 font-weight-bold text-dark">{{ $contents['statistics']['title'] }}</span>
                                    </div>
                                @endif

                                @if(!empty($contents['statistics']['subtitle']))
                                    <div class="mt-8 font-16 text-gray-500">{{ $contents['statistics']['subtitle'] }}</div>
                                @endif
                            </div>
                        @endif

                        {{-- Button --}}
                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                            <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white mt-24" data-text="{{ $contents['main_content']['button']['label'] }}">
                                @if(!empty($contents['main_content']['button']['icon']))
                                    @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                <span class="btn-flip-effect__text">{{ $contents['main_content']['button']['label'] }}</span>
                            </a>
                        @endif

                        @if(!empty($contents['floating_icon_2']))
                            <div class="course-bundles-section__floating-icon-2">
                                <img src="{{ $contents['floating_icon_2'] }}" alt="floating_icon_2">
                            </div>
                        @endif
                    </div>

                    {{-- Bundles --}}
                    <div class="col-12 col-lg-8">
                        <div class="row">
                            @include('design_1.web.bundles.components.cards.grids.index',['bundles' => $latestBundles, 'gridCardClassName' => "col-12 col-lg-6 mt-24"])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
