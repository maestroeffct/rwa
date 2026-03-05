@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $ctaCard8ColumnsBackgroundColor = "primary";
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        if (!empty($contents['background_color'])) {
            $ctaCard8ColumnsBackgroundColor = $contents['background_color'];
        }
    @endphp

    @if(!empty($contents))
        @push('styles_top')
            <link rel="stylesheet" href="{{ getLandingComponentStylePath("cta_card_8_columns") }}">
        @endpush

        <div class="cta-card-8-columns-section position-relative">
            <div class="cta-card-8-columns-section__bg-mask" style="background-color: var({{ "--".$ctaCard8ColumnsBackgroundColor }}); {{ (!empty($contents['background']) ? "background-image: url({$contents['background']}); " : '') }}"></div>

            <div class="container position-relative z-index-2">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="cta-card-8-columns-section__contents-box position-relative">
                            <div class="cta-card-8-columns-section__contents-box-mask"></div>

                            <div class="position-relative z-index-2 d-flex-center flex-column text-center bg-white px-24 py-48 p-lg-48 mt-48 rounded-32 w-100 h-100">

                                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                                    <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                                @endif

                                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                                    <h2 class="mt-12 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                                @endif

                                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                                    <p class="mt-16 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                                @endif

                                @if(!empty($contents['main_content']) and !empty($contents['main_content']['image']))
                                    <div class="d-flex-center w-100 mt-20 mt-lg-32 px-16 px-lg-32">
                                        <img src="{{ $contents['main_content']['image'] }}" alt="users image" class="img-fluid">
                                    </div>
                                @endif

                                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-16 mt-24 mt-lg-40">
                                    {{-- Primary Button --}}
                                    @if(!empty($contents['buttons']['primary_button']) and !empty($contents['buttons']['primary_button']['label']))
                                        <a href="{{ !empty($contents['buttons']['primary_button']['url']) ? $contents['buttons']['primary_button']['url'] : '' }}" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white" data-text="{{ $contents['buttons']['primary_button']['label'] }}">
                                            @if(!empty($contents['buttons']['primary_button']['icon']))
                                                @svg("iconsax-{$contents['buttons']['primary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                            @endif

                                            <span class="btn-flip-effect__text">{{ $contents['buttons']['primary_button']['label'] }}</span>
                                        </a>
                                    @endif

                                    {{-- Secondary Button --}}
                                    @if(!empty($contents['buttons']['secondary_button']) and !empty($contents['buttons']['secondary_button']['label']))
                                        <a href="{{ !empty($contents['buttons']['secondary_button']['url']) ? $contents['buttons']['secondary_button']['url'] : '' }}" class="btn-flip-effect btn-flip-effect__text-dark btn btn-xlg gap-8" data-text="{{ $contents['buttons']['secondary_button']['label'] }}">
                                            @if(!empty($contents['buttons']['secondary_button']['icon']))
                                                @svg("iconsax-{$contents['buttons']['secondary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                            @endif

                                            <span class="btn-flip-effect__text text-dark">{{ $contents['buttons']['secondary_button']['label'] }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div> {{-- En Col --}}
                </div> {{-- End Row --}}
            </div>
        </div>
    @endif
@endif
