@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("cta_section_full_width") }}">
    @endpush

    <div class="cta-section-full-width-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
        <div class="container h-100 position-relative">
            <div class="d-flex align-items-lg-center justify-content-lg-center flex-column text-lg-center w-100">
                @if(!empty($contents['first_line']))
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-12 font-64 font-weight-bold text-dark">
                        @if(!empty($contents['first_line']['text_1']))
                            <span class="">{{ $contents['first_line']['text_1'] }}</span>
                        @endif

                        @if(!empty($contents['first_line']['image_1']))
                            <div class="cta-section-full-width-section__line-1-img-box d-flex-center bg-white p-12 rounded-pill">
                                <img src="{{ $contents['first_line']['image_1'] }}" alt="image" class="img-cover">
                            </div>
                        @endif

                        @if(!empty($contents['first_line']['text_2']))
                            <span class="">{{ $contents['first_line']['text_2'] }}</span>
                        @endif
                    </div>
                @endif

                @if(!empty($contents['second_line']))
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-16 font-64 font-weight-bold text-dark mt-20">
                        @if(!empty($contents['second_line']['text_1']))
                            <span class="">{{ $contents['second_line']['text_1'] }}</span>
                        @endif

                        @if(!empty($contents['second_line']['image_1']) or !empty($contents['second_line']['image_2']))
                            <div class="d-flex align-items-center overlay-avatars overlay-avatars-24">
                                @if(!empty($contents['second_line']['image_1']))
                                    <div class="overlay-avatars__item d-flex-center size-80 rounded-circle bg-white">
                                        <div class="size-64 bg-gray-100 rounded-circle">
                                            <img src="{{ $contents['second_line']['image_1'] }}" alt="avatar" class="img-cover rounded-circle">
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($contents['second_line']['image_2']))
                                    <div class="overlay-avatars__item d-flex-center size-80 rounded-circle bg-white">
                                        <div class="size-64 bg-gray-100 rounded-circle">
                                            <img src="{{ $contents['second_line']['image_2'] }}" alt="avatar" class="img-cover rounded-circle">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if(!empty($contents['second_line']['text_2']))
                            <span class="">{{ $contents['second_line']['text_2'] }}</span>
                        @endif
                    </div>
                @endif

                @if(!empty($contents['additional_information']) and !empty($contents['additional_information']['text_1']))
                    <div class="font-20 text-gray-500 mt-16">{!! nl2br($contents['additional_information']['text_1']) !!}</div>
                @endif

                @if(!empty($contents['primary_button']) or !empty($contents['secondary_button']))
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center mt-32 gap-16 gap-lg-24">
                        {{-- Primary Button --}}
                        @if(!empty($contents['primary_button']) and !empty($contents['primary_button']['label']))
                            <a href="{{ !empty($contents['primary_button']['url']) ? $contents['primary_button']['url'] : '' }}" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white" data-text="{{ $contents['primary_button']['label'] }}">
                                @if(!empty($contents['primary_button']['icon']))
                                    @svg("iconsax-{$contents['primary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                <span class="btn-flip-effect__text text-white">{{ $contents['primary_button']['label'] }}</span>
                            </a>
                        @endif

                        {{-- Secondary Button --}}
                        @if(!empty($contents['secondary_button']) and !empty($contents['secondary_button']['label']))
                            <a href="{{ !empty($contents['secondary_button']['url']) ? $contents['secondary_button']['url'] : '' }}" class="btn-flip-effect btn btn-accent btn-xlg gap-8 text-white" data-text="{{ $contents['secondary_button']['label'] }}">
                                @if(!empty($contents['secondary_button']['icon']))
                                    @svg("iconsax-{$contents['secondary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                <span class="btn-flip-effect__text text-white">{{ $contents['secondary_button']['label'] }}</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- floating_images --}}
            @if(!empty($contents['floating_images']))
                @if(!empty($contents['floating_images']['image_1']))
                    <div class="cta-section-full-width-section__floating-image-1 d-none d-lg-flex-center">
                        <img src="{{ $contents['floating_images']['image_1'] }}" alt="floating image 1">
                    </div>
                @endif

                @if(!empty($contents['floating_images']['image_2']))
                    <div class="cta-section-full-width-section__floating-image-2 d-none d-lg-flex-center">
                        <img src="{{ $contents['floating_images']['image_2'] }}" alt="floating image 2">
                    </div>
                @endif
            @endif

            {{-- side_images --}}
            @if(!empty($contents['side_images']))
                @if(!empty($contents['side_images']['image_1']))
                    <div class="cta-section-full-width-section__side-image-1 d-none d-lg-flex-center">
                        <img src="{{ $contents['side_images']['image_1'] }}" alt="side image 1">
                    </div>
                @endif

                @if(!empty($contents['side_images']['image_2']))
                    <div class="cta-section-full-width-section__side-image-2 d-none d-lg-flex-center">
                        <img src="{{ $contents['side_images']['image_2'] }}" alt="side image 2">
                    </div>
                @endif
            @endif

        </div>
    </div>
@endif
