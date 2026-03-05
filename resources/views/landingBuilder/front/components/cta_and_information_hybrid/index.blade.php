@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $ctaAndInformationHybridBackgroundColor = "primary";
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        if (!empty($contents['background_color'])) {
            $ctaAndInformationHybridBackgroundColor = $contents['background_color'];
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("cta_and_information_hybrid") }}">
    @endpush

    <div class="cta-and-information-hybrid-section position-relative" style="background-color: var({{ "--".$ctaAndInformationHybridBackgroundColor }}); {{ (!empty($contents['background']) ? "background-image: url({$contents['background']}); " : '') }}">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-5 col-lg-4 pt-40">
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                        <div class="d-inline-flex-center py-8 px-16 rounded-8 border-white bg-white-20 font-12 text-white">{{ $contents['main_content']['pre_title'] }}</div>
                    @endif

                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                        <h2 class="mt-12 font-44 text-white">{{ $contents['main_content']['title'] }}</h2>
                    @endif

                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                        <p class="mt-16 font-16 text-white opacity-70">{!! nl2br($contents['main_content']['description']) !!}</p>
                    @endif

                    {{-- Primary Button --}}
                    @if(!empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                        <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" class="btn-flip-effect btn-flip-effect__text-primary btn bg-white text-primary btn-xlg gap-8 mt-32" data-text="{{ $contents['main_content']['button']['label'] }}">
                            @if(!empty($contents['main_content']['button']['icon']))
                                @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                            @endif

                            <span class="btn-flip-effect__text">{{ $contents['main_content']['button']['label'] }}</span>
                        </a>
                    @endif
                </div>

                <div class="col-md-2 col-lg-4"></div>

                <div class="col-12 col-md-5 col-lg-4 d-flex justify-content-end">
                    <div class="cta-and-information-hybrid-section__content-img d-flex-center">
                        @if(!empty($contents['main_content']['icon']))
                            <img src="{{ $contents['main_content']['icon'] }}" alt="icon" class="img-fluid" width="440px" height="440px">
                        @endif
                    </div>
                </div>
            </div>

            @if(!empty($contents['specific_information']) and is_array($contents['specific_information']))
                @php
                    if (!empty($contents['specific_information']['record'])) {
                        unset($contents['specific_information']['record']);
                    }
                @endphp

                @if(count($contents['specific_information']))
                    <div class="bg-white p-32 rounded-32 mt-32">
                        <div class="row">
                            @foreach($contents['specific_information'] as $informationData)
                                @if(!empty($informationData['title']) and !empty($informationData['description']))
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <h4 class="font-24 text-dark">{{ $informationData['title'] }}</h4>
                                        <p class="mt-16 text-gray-500">{!! nl2br($informationData['description']) !!}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endif
