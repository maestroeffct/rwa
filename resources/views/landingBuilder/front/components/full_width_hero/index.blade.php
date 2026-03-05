@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $fullWidthHeroBackground = null;
        $fullWidthHeroDarkBackground = null;

        if (!empty($contents['dark_mode_background'])) {
            $fullWidthHeroDarkBackground = $contents['dark_mode_background'];
        }

        if (!empty($contents['background'])) {
            $fullWidthHeroBackground = $contents['background'];
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("full_width_hero") }}">
    @endpush

    <div class="full-width-hero-section position-relative">
        <div class="full-width-hero-section__bg-wrapper light-only" @if(!empty($fullWidthHeroBackground)) style="background-image: url({{ $fullWidthHeroBackground }})" @endif></div>
        <div class="full-width-hero-section__bg-wrapper dark-only" @if(!empty($fullWidthHeroDarkBackground)) style="background-image: url({{ $fullWidthHeroDarkBackground }})" @endif></div>

        <div class="container position-relative z-index-2 d-flex-center flex-column text-center">

            {{-- Upper Call to Action --}}
            @if(!empty($contents['upper_cta']) and (!empty($contents['upper_cta']['main_text']) or !empty($contents['upper_cta']['badge_text'])))
                <a href="{{ !empty($contents['upper_cta']['url']) ? $contents['upper_cta']['url'] : '' }}" target="_blank" class="">
                    <div class="d-inline-flex align-items-center gap-8 p-8 pr-16 rounded-32 border-2 border-primary">
                        @if(!empty($contents['upper_cta']['badge_text']))
                            <div class="d-flex-center gap-4 p-6 pr-10 rounded-16 bg-primary">
                                @if(!empty($contents['upper_cta']['icon']))
                                    @svg("iconsax-{$contents['upper_cta']['icon']}", ['width' => '20px', 'height' => '20px', 'class' => "icons text-white full-width-hero-section__upper-cta-badge-icon"])
                                @endif

                                <span class="font-14 text-white">{{ $contents['upper_cta']['badge_text'] }}</span>
                            </div>
                        @endif

                        @if(!empty($contents['upper_cta']['main_text']))
                            <span class="font-14 text-white">{{ $contents['upper_cta']['main_text'] }}</span>
                        @endif

                        <x-iconsax-lin-arrow-right class="icons text-white" width="16px" height="16px"/>
                    </div>
                </a>
            @endif

            @if(!empty($contents['main_content']))
                <div class="position-relative d-flex-center flex-column text-center w-100">

                    <div class="row justify-content-center position-relative w-100">
                        <div class="col-12 col-lg-8">

                            {{-- Title --}}
                            @if(!empty($contents['main_content']['title_line_1']) or !empty($contents['main_content']['title_line_2']))
                                <h1 class="position-relative d-flex-center flex-column gap-4 text-center font-64 mt-32 text-white">
                                    @if(!empty($contents['main_content']['title_line_1']))
                                        <span>{{ $contents['main_content']['title_line_1'] }}</span>
                                    @endif

                                    @if(!empty($contents['main_content']['title_line_2']))
                                        <span class="">{{ $contents['main_content']['title_line_2'] }}</span>
                                    @endif
                                </h1>
                            @endif

                            @if(!empty($contents['main_content']['badge_text']))
                                <div class="full-width-hero-section__badge-text d-inline-flex-center px-16 py-8 rounded-32 bg-accent font-16 text-white">
                                    {{ $contents['main_content']['badge_text'] }}
                                </div>
                            @endif

                        </div>


                        <div class="col-12 col-lg-6">
                            {{-- Description --}}
                            @if(!empty($contents['main_content']['description']))
                                <p class="font-20 mt-16 text-white opacity-80">{!! nl2br($contents['main_content']['description']) !!}</p>
                            @endif
                        </div>
                    </div>


                    @if(!empty($contents['main_content']['primary_button']) or !empty($contents['main_content']['secondary_button']))
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center mt-32 gap-16">
                            {{-- Primary Button --}}
                            @if(!empty($contents['main_content']['primary_button']) and !empty($contents['main_content']['primary_button']['label']))
                                <a href="{{ !empty($contents['main_content']['primary_button']['url']) ? $contents['main_content']['primary_button']['url'] : '' }}" class="btn-flip-effect with-icon-48 btn btn-primary btn-xlg gap-12 p-8 rounded-32 {{ !empty($contents['main_content']['primary_button']['icon']) ? 'pr-28' : 'px-28' }}" data-text="{{ $contents['main_content']['primary_button']['label'] }}">
                                    @if(!empty($contents['main_content']['primary_button']['icon']))
                                        <div class="d-flex-center size-48 bg-white rounded-circle">
                                            @svg("iconsax-{$contents['main_content']['primary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-primary"])
                                        </div>
                                    @endif

                                    <span class="text-white btn-flip-effect__text">{{ $contents['main_content']['primary_button']['label'] }}</span>
                                </a>
                            @endif

                            {{-- Secondary Button --}}
                            @if(!empty($contents['main_content']['secondary_button']) and !empty($contents['main_content']['secondary_button']['label']))
                                <a href="{{ !empty($contents['main_content']['secondary_button']['url']) ? $contents['main_content']['secondary_button']['url'] : '' }}" class="btn-flip-effect btn btn-xlg gap-8" data-text="{{ $contents['main_content']['secondary_button']['label'] }}">

                                    @if(!empty($contents['main_content']['secondary_button']['icon']))
                                        @svg("iconsax-{$contents['main_content']['secondary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-white"])
                                    @endif
                                     <span class="btn-flip-effect__text text-white">{{ $contents['main_content']['secondary_button']['label'] }}</span>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            {{-- Checked Items --}}
            @if(!empty($contents['checked_items']) and is_array($contents['checked_items']))
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-24 gap-lg-48 mt-52">
                    @foreach($contents['checked_items'] as $checkedItem)
                        <div class="d-flex align-items-center gap-4">
                            <x-iconsax-lin-tick-circle class="icons text-success" width="24px" height="24px"/>
                            <span class="font-16 text-white">{{ $checkedItem }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Image/Video --}}
            <div class="row justify-content-center w-100">
                <div class="col-12 col-lg-10">
                    <div class="full-width-hero-section__images-content position-relative d-flex-center rounded-32 mt-64">
                        @if(!empty($contents['image_content']) and !empty($contents['image_content']['type']))
                            @if($contents['image_content']['type'] == "video" and !empty($contents['image_content']['video']))
                                <video id="" class="js-init-plyr-io plyr-io-video rounded-32" oncontextmenu="return false;" controlsList="nodownload" controls preload="auto" width="100%" data-setup='{"fluid": true}'>
                                    <source src="{{ $contents['image_content']['video'] }}" type="video/mp4"/>
                                </video>
                            @elseif($contents['image_content']['type'] == "image" and !empty($contents['image_content']['image']))
                                <img src="{{ $contents['image_content']['image'] }}" alt="image" class="img-cover rounded-32">
                            @endif
                        @endif

                        {{-- Overlay Image 1 --}}
                        @if(!empty($contents['image_content']) and !empty($contents['image_content']['overlay_image_1']))
                            <div class="full-width-hero-section__images-content-overlay-1 rounded-16">
                                <img src="{{ $contents['image_content']['overlay_image_1'] }}" alt="overlay 1" class="img-cover rounded-16">
                            </div>
                        @endif

                        {{-- Overlay Image 2 --}}
                        @if(!empty($contents['image_content']) and !empty($contents['image_content']['overlay_image_2']))
                            <div class="full-width-hero-section__images-content-overlay-2 rounded-16">
                                <img src="{{ $contents['image_content']['overlay_image_2'] }}" alt="overlay 2" class="img-cover rounded-16">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
