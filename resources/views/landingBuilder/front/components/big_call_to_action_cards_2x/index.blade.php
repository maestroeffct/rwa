@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("big_call_to_action_cards_2x") }}">
    @endpush

    <div class="container">
        <div class="big-call-to-action-cards-2x-section position-relative">

            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="mt-12 font-44 text-dark">{{ $contents['main_content']['title'] }}</h2>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                    <p class="mt-20 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                @endif
            </div>

            <div class="row">
                @if(!empty($contents['cta_1']))
                    <div class="col-12 col-lg-6 mt-48">
                        <div class="big-call-to-action-cards-2x-section__cta-card position-relative z-index-3 d-flex-center flex-column text-center p-60 rounded-24 bg-white">
                            @if(!empty($contents['cta_1']['icon']))
                                <div class="d-flex-center size-96 bg-primary rounded-circle">
                                    @svg("iconsax-{$contents['cta_1']['icon']}", ['width' => '48px', 'height' => '48px', 'class' => "icons text-white"])
                                </div>
                            @endif

                            @if(!empty($contents['cta_1']['title']))
                                <h3 class="font-24 text-dark mt-20">{{ $contents['cta_1']['title'] }}</h3>
                            @endif

                            @if(!empty($contents['cta_1']['description']))
                                <p class="font-16 text-gray-500 mt-16">{!! nl2br($contents['cta_1']['description']) !!}</p>
                            @endif

                            @if(!empty($contents['cta_1']['link_title']) and !empty($contents['cta_1']['url']))
                                <a href="{{ $contents['cta_1']['url'] }}" target="_blank" class="btn-flip-effect btn-flip-effect__right-0 btn-flip-effect__text-dark d-inline-flex align-items-center gap-4 mt-16 text-dark" data-text="{{ $contents['cta_1']['link_title'] }}">
                                    <x-iconsax-lin-arrow-right class="icons text-dark" width="16px" height="16px"/>
                                    <span class="btn-flip-effect__text">{{ $contents['cta_1']['link_title'] }}</span>
                                </a>
                            @endif
                        </div>

                        @if(!empty($contents['cta_1_confirmation_section']) and !empty($contents['cta_1_confirmation_section']['title']))
                            <div class="big-call-to-action-cards-2x-section__cta-card-confirmation bg-white-60 d-flex align-items-center p-16">
                                <div class="d-flex align-items-center overlay-avatars overlay-avatars-20">
                                    @if(!empty($contents['cta_1_confirmation_section']['image_1']))
                                        <div class="overlay-avatars__item d-flex-center size-48 rounded-circle bg-gray-200">
                                            <div class="size-32 bg-gray-100 rounded-circle">
                                                <img src="{{ $contents['cta_1_confirmation_section']['image_1'] }}" alt="avatar" class="img-cover rounded-circle">
                                            </div>
                                        </div>
                                    @endif

                                    @if(!empty($contents['cta_1_confirmation_section']['image_2']))
                                        <div class="overlay-avatars__item d-flex-center size-56 rounded-circle bg-gray-200">
                                            <div class="size-40 bg-gray-100 rounded-circle">
                                                <img src="{{ $contents['cta_1_confirmation_section']['image_2'] }}" alt="avatar" class="img-cover rounded-circle">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="ml-8">
                                    <h4 class="font-16 text-dark">{{ $contents['cta_1_confirmation_section']['title'] }}</h4>

                                    @if(!empty($contents['cta_1_confirmation_section']['subtitle']))
                                        <p class="mt-4 font-14 text-gray-500">{{ $contents['cta_1_confirmation_section']['subtitle'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endif


                @if(!empty($contents['cta_2']))
                    <div class="col-12 col-lg-6 mt-48">
                        <div class="big-call-to-action-cards-2x-section__cta-card position-relative z-index-3 d-flex-center flex-column text-center p-60 rounded-24 bg-white">
                            @if(!empty($contents['cta_2']['icon']))
                                <div class="d-flex-center size-96 bg-primary rounded-circle">
                                    @svg("iconsax-{$contents['cta_2']['icon']}", ['width' => '48px', 'height' => '48px', 'class' => "icons text-white"])
                                </div>
                            @endif

                            @if(!empty($contents['cta_2']['title']))
                                <h3 class="font-24 text-dark mt-20">{{ $contents['cta_2']['title'] }}</h3>
                            @endif

                            @if(!empty($contents['cta_2']['description']))
                                <p class="font-16 text-gray-500 mt-16">{!! nl2br($contents['cta_2']['description']) !!}</p>
                            @endif

                            @if(!empty($contents['cta_2']['link_title']) and !empty($contents['cta_2']['url']))
                                <a href="{{ $contents['cta_2']['url'] }}" target="_blank" class="btn-flip-effect btn-flip-effect__right-0 btn-flip-effect__text-dark d-inline-flex align-items-center gap-4 mt-16 text-dark" data-text="{{ $contents['cta_2']['link_title'] }}">
                                    <x-iconsax-lin-arrow-right class="icons text-dark" width="16px" height="16px"/>
                                    <span class="btn-flip-effect__text">{{ $contents['cta_2']['link_title'] }}</span>
                                </a>
                            @endif
                        </div>

                        @if(!empty($contents['cta_2_confirmation_section']) and !empty($contents['cta_2_confirmation_section']['title']))
                            <div class="big-call-to-action-cards-2x-section__cta-card-confirmation bg-white-60 d-flex align-items-center p-16">
                                @if(!empty($contents['cta_2_confirmation_section']['image']))
                                    <div class="d-flex-center size-56 rounded-circle bg-gray-200">
                                        <div class="size-40 bg-gray-100 rounded-circle">
                                            <img src="{{ $contents['cta_2_confirmation_section']['image'] }}" alt="avatar" class="img-cover rounded-circle">
                                        </div>
                                    </div>
                                @endif

                                <div class="ml-8">
                                    <h4 class="font-16 text-dark">{{ $contents['cta_2_confirmation_section']['title'] }}</h4>

                                    @if(!empty($contents['cta_2_confirmation_section']['subtitle']))
                                        <p class="mt-4 font-14 text-gray-500">{{ $contents['cta_2_confirmation_section']['subtitle'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
@endif
