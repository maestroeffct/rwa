@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $boxedCtsFullWidthBackground = null;
        $boxedCtsFullWidthDarkBackground = null;

        if (!empty($contents['dark_mode_background'])) {
            $boxedCtsFullWidthDarkBackground = $contents['dark_mode_background'];
        }

        if (!empty($contents['background'])) {
            $boxedCtsFullWidthBackground = $contents['background'];
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("boxed_cta_full_width") }}">
    @endpush

    <div class="boxed-cta-full-width-section container position-relative rounded-32">
        <div class="boxed-cta-full-width-section__bg-wrapper rounded-32 light-only" @if(!empty($boxedCtsFullWidthBackground)) style="background-image: url({{ $boxedCtsFullWidthBackground }})" @endif></div>
        <div class="boxed-cta-full-width-section__bg-wrapper rounded-32 dark-only" @if(!empty($boxedCtsFullWidthDarkBackground)) style="background-image: url({{ $boxedCtsFullWidthDarkBackground }})" @endif></div>

        <div class="position-relative z-index-2 w-100 h-100">
            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']))
                    @if(!empty($contents['main_content']['pre_title']))
                        <div class="d-inline-flex-center gap-8 py-12 px-16 rounded-32 border-white border-2 bg-white-10 font-14 text-white">
                            @if(!empty($contents['main_content']['pre_title_icon']))
                                <div class="boxed-cta-full-width-section__pre-title-icon d-flex-center size-24">
                                    @svg("iconsax-{$contents['main_content']['primary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-white"])
                                </div>
                            @endif

                            <span class="">{{ $contents['main_content']['pre_title'] }}</span>
                        </div>
                    @endif

                    <div class="row justify-content-center mt-32 w-100">
                        <div class="col-12 col-lg-6 position-relative">
                            @if(!empty($contents['main_content']['main_title_line_1']) or !empty($contents['main_content']['main_title_line_2']))
                                <h2 class="d-inline-flex-center flex-column gap-4 font-44 text-white">
                                    @if(!empty($contents['main_content']['main_title_line_1']))
                                        <span class="">{{ $contents['main_content']['main_title_line_1'] }}</span>
                                    @endif

                                    @if(!empty($contents['main_content']['main_title_line_2']))
                                        <span class="">{{ $contents['main_content']['main_title_line_2'] }}</span>
                                    @endif
                                </h2>
                            @endif

                            @if(!empty($contents['main_content']['badge_title']))
                                <div class="boxed-cta-full-width-section__badge-box d-inline-flex-center py-8 px-16 rounded-32 bg-primary font-14 text-white">{{ $contents['main_content']['badge_title'] }}</div>
                            @endif

                            @if(!empty($contents['main_content']['description']))
                                <p class="mt-20 font-16 text-white opacity-80">{!! nl2br($contents['main_content']['description']) !!}</p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Links --}}
                @if($contents['specific_links'] and is_array($contents['specific_links']))
                    <div class="d-flex align-items-center flex-wrap gap-16 mt-32">
                        @foreach($contents['specific_links'] as $specificLinkData)
                            @if(!empty($specificLinkData['title']) and !empty($specificLinkData['url']))
                                <a href="{{ $specificLinkData['url'] }}" target="_blank" class="font-16 font-weight-bold text-white opacity-70">{{ $specificLinkData['title'] }}</a>

                                @if(!$loop->last)
                                    <div class="boxed-cta-full-width-section__circle-dot-separator"></div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                @endif

                @if(!empty($contents['main_content']['primary_button']) or !empty($contents['main_content']['secondary_button']))
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-center mt-32 gap-16">
                        {{-- Primary Button --}}
                        @if(!empty($contents['main_content']['primary_button']) and !empty($contents['main_content']['primary_button']['label']))
                            <a href="{{ !empty($contents['main_content']['primary_button']['url']) ? $contents['main_content']['primary_button']['url'] : '' }}" class="btn-flip-effect btn btn-primary btn-xlg gap-12 p-8  rounded-32 {{ !empty($contents['main_content']['primary_button']['icon']) ? 'pr-28' : 'px-28' }}" data-text="{{ $contents['main_content']['primary_button']['label'] }}">
                                @if(!empty($contents['main_content']['primary_button']['icon']))
                                    <div class="d-flex-center size-48 bg-white rounded-circle">
                                        @svg("iconsax-{$contents['main_content']['primary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-primary"])
                                    </div>
                                @endif

                                <span class="btn-flip-effect__text text-white">{{ $contents['main_content']['primary_button']['label'] }}</span>
                            </a>
                        @endif

                        {{-- Secondary Button --}}
                        @if(!empty($contents['main_content']['secondary_button']) and !empty($contents['main_content']['secondary_button']['label']))
                            <a href="{{ !empty($contents['main_content']['secondary_button']['url']) ? $contents['main_content']['secondary_button']['url'] : '' }}" class="btn-flip-effect btn  btn-xlg gap-8" data-text="{{ $contents['main_content']['secondary_button']['label'] }}">
                                @if(!empty($contents['main_content']['secondary_button']['icon']))
                                    @svg("iconsax-{$contents['main_content']['secondary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-white"])
                                @endif

                                <span class="btn-flip-effect__text text-white">{{ $contents['main_content']['secondary_button']['label'] }}</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
