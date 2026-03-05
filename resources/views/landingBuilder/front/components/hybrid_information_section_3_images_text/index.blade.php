@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("hybrid_information_section_3_images_text") }}">
    @endpush

    <div class="hybrid-information-section-3-images_text-section position-relative">
        <div class="container position-relative h-100">
            <div class="row h-100 {{ (!empty($contents['reverse_direction']) and $contents['reverse_direction'] == "on") ? 'flex-row-reverse' : '' }} ">

                <div class="col-12 col-lg-5">
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
                            <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white mt-20" data-text="{{ $contents['main_content']['button']['label'] }}">
                                @if(!empty($contents['main_content']['button']['icon']))
                                    @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                <span class="btn-flip-effect__text">{{ $contents['main_content']['button']['label'] }}</span>
                            </a>
                        @endif

                        {{-- statistic --}}
                        <div class="d-flex align-items-center mt-60 gap-54">
                            @if(!empty($contents['statistic_1']))
                                <div class="hybrid-information-section-3-images_text-section__statistic-item">
                                    @if(!empty($contents['statistic_1']['title']))
                                        <div class="d-flex align-items-center gap-8">
                                            <span class="line"></span>
                                            <span class="font-24 font-weight-bold">{{ $contents['statistic_1']['title'] }}</span>
                                        </div>
                                    @endif

                                    @if(!empty($contents['statistic_1']['subtitle']))
                                        <h4 class="mt-12 font-16 text-dark">{{ $contents['statistic_1']['subtitle'] }}</h4>
                                    @endif

                                    @if(!empty($contents['statistic_1']['description']))
                                        <p class="mt-4 font-14 text-gray-500">{!! nl2br($contents['statistic_1']['description']) !!}</p>
                                    @endif
                                </div>
                            @endif

                            @if(!empty($contents['statistic_2']))
                                <div class="hybrid-information-section-3-images_text-section__statistic-item">
                                    @if(!empty($contents['statistic_2']['title']))
                                        <div class="d-flex align-items-center gap-8">
                                            <span class="line"></span>
                                            <span class="font-24 font-weight-bold">{{ $contents['statistic_2']['title'] }}</span>
                                        </div>
                                    @endif

                                    @if(!empty($contents['statistic_2']['subtitle']))
                                        <h4 class="mt-12 font-16 text-dark">{{ $contents['statistic_2']['subtitle'] }}</h4>
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
                <div class="col-12 col-lg-6 h-100 position-relative mt-32 mt-lg-0 px-24 px-lg-48">
                    <div class="d-flex align-items-center gap-16">
                        @if(!empty($contents['image_content']))

                            @if(!empty($contents['image_content']['image_1']))
                                <div class="hybrid-information-section-3-images_text-section__image-1 position-relative rounded-32">
                                    <img src="{{ $contents['image_content']['image_1'] }}" alt="image_1" class="img-cover rounded-32">
                                </div>
                            @endif

                            @if(!empty($contents['image_content']['image_2']))
                                <div class="hybrid-information-section-3-images_text-section__image-2 position-relative rounded-32">
                                    <img src="{{ $contents['image_content']['image_2'] }}" alt="image_2" class="img-cover rounded-32">
                                </div>
                            @endif

                            @if(!empty($contents['image_content']['revolver_image']))
                                <div class="hybrid-information-section-3-images_text-section__overlay-image">
                                    <img src="{{ $contents['image_content']['revolver_image'] }}" alt="revolver_image" class="img-cover">
                                </div>
                            @endif
                        @endif

                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['badge_title']))
                            <div class="hybrid-information-section-3-images_text-section__badge d-inline-flex-center text-center text-white bg-primary rounded-32">{{ $contents['main_content']['badge_title'] }}</div>
                        @endif

                    </div>
                </div>

            </div>

        </div>
    </div>
@endif
