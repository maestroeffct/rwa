@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("centered_hero_with_four_images_two_icons") }}">
    @endpush

    <div class="centered-hero-with-four-images-two-icons-section " @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
        <div class="container d-flex flex-column align-items-center text-center h-100">

            {{-- Upper Call to Action --}}
            @if(!empty($contents['upper_cta']) and (!empty($contents['upper_cta']['badge_text']) or !empty($contents['upper_cta']['main_text'])))
                <a href="{{ !empty($contents['upper_cta']['url']) ? $contents['upper_cta']['url'] : '' }}" target="_blank" class="">
                    <div class="d-inline-flex align-items-center gap-8 py-12 pl-16 pr-20 rounded-32 border-2 border-secondary">
                        @if(!empty($contents['upper_cta']['icon']))
                            @svg("iconsax-{$contents['upper_cta']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-primary centered-hero-with-four-images-two-icons-section__upper-cta-badge-icon"])
                        @endif

                        @if(!empty($contents['upper_cta']['main_text']))
                            <span class="font-14 text-dark">{{ $contents['upper_cta']['main_text'] }}</span>
                        @endif
                    </div>
                </a>
            @endif

            {{-- Main Contents --}}
            @if(!empty($contents['main_content']))
                {{-- Title --}}
                <h1 class="position-relative d-inline-flex flex-column align-items-center text-center font-64 mt-32">
                    @if(!empty($contents['main_content']['title_line_1']))
                        <span class="text-dark">{{ $contents['main_content']['title_line_1'] }}</span>
                    @endif

                    @if(!empty($contents['main_content']['highlighted_word']))
                        <div class="title-highlighted-word d-inline-flex-center py-12 px-24 rounded-24 bg-accent font-64 text-white mt-8">
                            {{ $contents['main_content']['highlighted_word'] }}
                        </div>
                    @endif

                    @if(!empty($contents['main_content']['title_line_2']))
                        <span class="mt-8 text-dark">{{ $contents['main_content']['title_line_2'] }}</span>
                    @endif

                    {{-- Floating Icons --}}
                    @if(!empty($contents['main_content']['floating_icon_1']))
                        <div class="section-floating-icon-1">
                            <img src="{{ $contents['main_content']['floating_icon_1'] }}" alt="{{ trans('update.floating_icon') }}" class="img-fluid">
                        </div>
                    @endif

                    @if(!empty($contents['main_content']['floating_icon_2']))
                        <div class="section-floating-icon-2">
                            <img src="{{ $contents['main_content']['floating_icon_2'] }}" alt="{{ trans('update.floating_icon') }}" class="img-fluid">
                        </div>
                    @endif
                </h1>


                {{-- Description --}}
                @if(!empty($contents['main_content']['description']))
                    <p class="mt-24 font-16 text-gray-500">{{ $contents['main_content']['description'] }}</p>
                @endif

                {{-- Buttons --}}
                @if(!empty($contents['main_content']['primary_button']) or !empty($contents['main_content']['secondary_button']))
                    <div class="d-flex align-items-lg-center flex-column flex-lg-row mt-32 gap-16">
                        {{-- Primary Button --}}
                        @if(!empty($contents['main_content']['primary_button']) and !empty($contents['main_content']['primary_button']['label']))
                            <a href="{{ !empty($contents['main_content']['primary_button']['url']) ? $contents['main_content']['primary_button']['url'] : '' }}" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white" data-text="{{ $contents['main_content']['primary_button']['label'] }}">
                                @if(!empty($contents['main_content']['primary_button']['icon']))
                                    @svg("iconsax-{$contents['main_content']['primary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                <span class="btn-flip-effect__text text-white">{{ $contents['main_content']['primary_button']['label'] }}</span>
                            </a>
                        @endif

                        {{-- Secondary Button --}}
                        @if(!empty($contents['main_content']['secondary_button']) and !empty($contents['main_content']['secondary_button']['label']))
                            <a href="{{ !empty($contents['main_content']['secondary_button']['url']) ? $contents['main_content']['secondary_button']['url'] : '' }}" class="btn-flip-effect btn-flip-effect__text-dark btn btn-xlg gap-8" data-text="{{ $contents['main_content']['secondary_button']['label'] }}">
                                @if(!empty($contents['main_content']['secondary_button']['icon']))
                                    @svg("iconsax-{$contents['main_content']['secondary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                <span class="btn-flip-effect__text text-dark">{{ $contents['main_content']['secondary_button']['label'] }}</span>
                            </a>
                        @endif
                    </div>
                @endif
            @endif

        </div>

        {{-- Images Container --}}
        <div class="container d-flex-center flex-wrap flex-lg-nowrap gap-16 gap-lg-32 mt-32 mt-lg-64">
            @if(!empty($contents['image_1']))
                <div class="centered-hero-with-four-images-two-icons-section__main-images">
                    <img src="{{ $contents['image_1'] }}" alt="{{ trans('public.image') }} #1" class="img-cover">
                </div>
            @endif

            @if(!empty($contents['image_2']))
                <div class="centered-hero-with-four-images-two-icons-section__main-images">
                    <img src="{{ $contents['image_2'] }}" alt="{{ trans('public.image') }} #2" class="img-cover">
                </div>
            @endif

            @if(!empty($contents['image_3']))
                <div class="centered-hero-with-four-images-two-icons-section__main-images">
                    <img src="{{ $contents['image_3'] }}" alt="{{ trans('public.image') }} #3" class="img-cover">
                </div>
            @endif

            @if(!empty($contents['image_4']))
                <div class="centered-hero-with-four-images-two-icons-section__main-images">
                    <img src="{{ $contents['image_4'] }}" alt="{{ trans('public.image') }} #4" class="img-cover">
                </div>
            @endif
        </div>
    </div>
@endif
