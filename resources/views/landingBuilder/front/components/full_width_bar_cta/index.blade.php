@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("full_width_bar_cta") }}">
    @endpush

    <div class="container">
        <div class="full-width-bar-cta-section position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between w-100" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-64 rounded-circle bg-info">
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['icon']))
                        @svg("iconsax-{$contents['main_content']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-white"])
                    @endif
                </div>
                <div class="ml-8">
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                        <h3 class="font-24 text-white">{{ $contents['main_content']['title'] }}</h3>
                    @endif

                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['subtitle']))
                        <p class="mt-4 font-16 text-white opacity-70">{!! nl2br($contents['main_content']['subtitle']) !!}</p>
                    @endif
                </div>
            </div>

            {{-- Button --}}
            @if(!empty($contents['main_content']) and !empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-accent btn-xlg gap-8 text-white mt-24 mt-lg-0" data-text="{{ $contents['main_content']['button']['label'] }}">
                    @if(!empty($contents['main_content']['button']['icon']))
                        @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                    @endif

                    <span class="btn-flip-effect__text">{{ $contents['main_content']['button']['label'] }}</span>
                </a>
            @endif
        </div>
    </div>
@endif
