@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("hybrid_information_section_full_width") }}">
    @endpush

    <div class="hybrid-information-section-full-width-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
        <div class="container position-relative h-100">
            <div class="row h-100 {{ (!empty($contents['reverse_direction']) and $contents['reverse_direction'] == "on") ? 'flex-row-reverse' : '' }} ">
                <div class="col-12 col-lg-4 ">
                    <div class="d-flex justify-content-center align-items-start flex-column h-100">
                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                            <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                        @endif

                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                            <h2 class="mt-12 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                        @endif

                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                            <p class="mt-20 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
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

                        {{-- Statistics --}}
                        <div class="d-flex align-items-center mt-32 gap-24 mt-lg-60 gap-lg-54">
                            @if(!empty($contents['statistic_1']))
                                <div class="hybrid-information-section-full-width-section__statistic-item">
                                    @if(!empty($contents['statistic_1']['title']))
                                        <div class="d-flex align-items-center gap-8">
                                            <span class="line"></span>
                                            <span class="font-24 font-weight-bold">{{ $contents['statistic_1']['title'] }}</span>
                                        </div>
                                    @endif

                                    @if(!empty($contents['statistic_1']['subtitle']))
                                        <h4 class="mt-8 font-16 text-dark">{{ $contents['statistic_1']['subtitle'] }}</h4>
                                    @endif

                                    @if(!empty($contents['statistic_1']['description']))
                                        <p class="mt-4 font-14 text-gray-500">{!! nl2br($contents['statistic_1']['description']) !!}</p>
                                    @endif
                                </div>
                            @endif

                            @if(!empty($contents['statistic_2']))
                                <div class="hybrid-information-section-full-width-section__statistic-item">
                                    @if(!empty($contents['statistic_2']['title']))
                                        <div class="d-flex align-items-center gap-8">
                                            <span class="line"></span>
                                            <span class="font-24 font-weight-bold">{{ $contents['statistic_2']['title'] }}</span>
                                        </div>
                                    @endif

                                    @if(!empty($contents['statistic_2']['subtitle']))
                                        <h4 class="mt-8 font-16 text-dark">{{ $contents['statistic_2']['subtitle'] }}</h4>
                                    @endif

                                    @if(!empty($contents['statistic_2']['description']))
                                        <p class="mt-4 font-14 text-gray-500">{!! nl2br($contents['statistic_2']['description']) !!}</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="col-lg-1"></div>

                {{-- Image --}}
                <div class="col-12 col-lg-7 h-100 mt-32 mt-lg-0">
                    <div class="position-relative w-100 h-100">
                        <div class="hybrid-information-section-full-width-section__main-image rounded-32">
                            @if(!empty($contents['image_content']))
                                @if(!empty($contents['image_content']['main_image']))
                                    <img src="{{ $contents['image_content']['main_image'] }}" alt="main_image" class="img-cover rounded-32">
                                @endif
                            @endif
                        </div>

                        @if(!empty($contents['image_content']['overlay_image']))
                            <div class="hybrid-information-section-full-width-section__overlay-image d-flex-center">
                                <img src="{{ $contents['image_content']['overlay_image'] }}" alt="overlay_image" class="img-fluid">
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endif
