@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $countdownCtaContainerWidth = (!empty($contents['section_style']) and $contents['section_style'] == "full_width") ? 'full' : 'container';
        $countdownCtaContainerColor = (!empty($contents['background_color']) and $contents['background_color'] == "primary") ? 'primary' : 'secondary';
        $countdownCtaContainerBackground = (!empty($contents['background'])) ? $contents['background'] : null;
        $countdownCtaContainerDarkBackground = (!empty($contents['dark_mode_background'])) ? $contents['dark_mode_background'] : null;
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("countdown_call_to_action") }}">
    @endpush

    @push('scripts_bottom')
        <script src="{{ getLandingComponentScriptPath("countdown_call_to_action") }}"></script>
    @endpush

    <div class="countdown-call-to-action-section position-relative {{ ($countdownCtaContainerWidth == "full") ? 'container-fluid' : 'container' }}" style="background-color: var({{ "--".$countdownCtaContainerColor }});">
        <div class="countdown-call-to-action-section__bg-wrapper  {{ !empty($countdownCtaContainerDarkBackground) ? 'light-only' : '' }}" @if(!empty($countdownCtaContainerBackground)) style="background-image: url({{ $countdownCtaContainerBackground }})" @endif></div>

        @if(!empty($countdownCtaContainerDarkBackground))
            <div class="countdown-call-to-action-section__bg-wrapper dark-only" style="background-image: url({{ $countdownCtaContainerDarkBackground }})"></div>
        @endif

        <div class="row justify-content-lg-center position-relative z-index-3">
            <div class="col-12 col-lg-6 d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']))
                    @if(!empty($contents['main_content']['pre_title']))
                        <div class="d-inline-flex-center gap-8 py-8 px-16 rounded-8 border-white bg-white-20 font-12 text-white mb-16">
                            <span class="">{{ $contents['main_content']['pre_title'] }}</span>
                        </div>
                    @endif

                    @if(!empty($contents['main_content']['title']))
                        <h2 class="font-32 text-white mb-8">{{ $contents['main_content']['title'] }}</h2>
                    @endif

                    @if(!empty($contents['main_content']['description']))
                        <p class="font-16 text-white">{{ $contents['main_content']['description'] }}</p>
                    @endif
                @endif

                {{-- Image --}}
                @if(!empty($contents['main_image']))
                    <div class="countdown-call-to-action-section__main-image position-relative rounded-16 mt-24">
                        <img src="{{ $contents['main_image'] }}" alt="{{ trans('update.countdown_call_to_action_component_title') }}" class="img-cover rounded-16">


                        @if(!empty($contents['image_2']))
                            <div class="countdown-call-to-action-section__overlay-image">
                                <img src="{{ $contents['image_2'] }}" alt="{{ trans('update.overlay_image') }}" class="img-fluid">
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Statistics --}}
                @if((!empty($contents['statistic_1']) and !empty($contents['statistic_1']['title'])) or (!empty($contents['statistic_2']) and !empty($contents['statistic_2']['title'])) or (!empty($contents['statistic_3']) and !empty($contents['statistic_3']['title'])))
                    <div class="d-flex align-items-center text-left gap-16 gap-lg-32 {{ (!empty($contents['main_image'])) ? 'mt-24' : 'mt-32' }}">
                        @if(!empty($contents['statistic_1']) and !empty($contents['statistic_1']['title']))
                            <div class="">
                                <div class="font-24 font-weight-bold text-white">{{ $contents['statistic_1']['title'] }}</div>

                                @if(!empty($contents['statistic_1']['subtitle']))
                                    <p class="mt-4 font-16 text-white opacity-70">{{ $contents['statistic_1']['subtitle'] }}</p>
                                @endif
                            </div>
                        @endif

                        @if((!empty($contents['statistic_1']) and !empty($contents['statistic_1']['title'])) and (!empty($contents['statistic_2']) and !empty($contents['statistic_2']['title'])))
                            <div class="countdown-call-to-action-section__statistic-divider"></div>
                        @endif

                        @if(!empty($contents['statistic_2']) and !empty($contents['statistic_2']['title']))
                            <div class="">
                                <div class="font-24 font-weight-bold text-white">{{ $contents['statistic_2']['title'] }}</div>

                                @if(!empty($contents['statistic_2']['subtitle']))
                                    <p class="mt-4 font-16 text-white opacity-70">{{ $contents['statistic_2']['subtitle'] }}</p>
                                @endif
                            </div>
                        @endif


                        @if((!empty($contents['statistic_2']) and !empty($contents['statistic_2']['title'])) and (!empty($contents['statistic_3']) and !empty($contents['statistic_3']['title'])))
                            <div class="countdown-call-to-action-section__statistic-divider"></div>
                        @endif

                        @if(!empty($contents['statistic_3']) and !empty($contents['statistic_3']['title']))
                            <div class="">
                                <div class="font-24 font-weight-bold text-white">{{ $contents['statistic_3']['title'] }}</div>

                                @if(!empty($contents['statistic_3']['subtitle']))
                                    <p class="mt-4 font-16 text-white opacity-70">{{ $contents['statistic_3']['subtitle'] }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Countdown --}}
                @if(!empty($contents['countdown_end_time']))
                    @php
                        $countdownEndTimestamp = strtotime($contents['countdown_end_time']);
                        $countdownRemainingTimes = $countdownEndTimestamp - time();

                        $countdownRemainingTimesStrings = ($countdownRemainingTimes > 0) ? time2string($countdownRemainingTimes) : null;
                    @endphp

                    @if(!empty($countdownRemainingTimesStrings))
                        <div class="js-call-to-action-countdown-card position-relative d-flex-center mt-32 gap-16 w-100"
                             data-day="{{ $countdownRemainingTimesStrings['day'] }}"
                             data-hour="{{ $countdownRemainingTimesStrings['hour'] }}"
                             data-minute="{{ $countdownRemainingTimesStrings['minute'] }}"
                             data-second="{{ $countdownRemainingTimesStrings['second'] }}"
                        >
                            <div class="d-flex flex-column text-white">
                                <span class="font-32 font-weight-bold days">00</span>
                                <span class="font-12 opacity-70">{{ trans('public.day') }}</span>
                            </div>

                            <span class="font-32 font-weight-bold text-white">:</span>

                            <div class="d-flex flex-column text-white">
                                <span class="font-32 font-weight-bold hours">00</span>
                                <span class="font-12 opacity-70">{{ trans('home.hours') }}</span>
                            </div>

                            <span class="font-32 font-weight-bold text-white">:</span>

                            <div class="d-flex flex-column text-white">
                                <span class="font-32 font-weight-bold minutes">00</span>
                                <span class="font-12 opacity-70">{{ trans('update.mins') }}</span>
                            </div>
                            <span class="font-32 font-weight-bold text-white">:</span>

                            <div class="d-flex flex-column text-white">
                                <span class="font-32 font-weight-bold seconds">00</span>
                                <span class="font-12 opacity-70">{{ trans('update.secs') }}</span>
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Buttons --}}
                @if(!empty($contents['buttons']['primary_button']) or !empty($contents['buttons']['secondary_button']))
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-center mt-24 gap-8">
                        {{-- Primary Button --}}
                        @if(!empty($contents['buttons']['primary_button']) and !empty($contents['buttons']['primary_button']['label']))
                            <a href="{{ !empty($contents['buttons']['primary_button']['url']) ? $contents['buttons']['primary_button']['url'] : '' }}" class="btn-flip-effect btn-flip-effect__text-primary btn bg-white btn-xlg gap-8 text-primary" data-text="{{ $contents['buttons']['primary_button']['label'] }}">
                                @if(!empty($contents['buttons']['primary_button']['icon']))
                                    @svg("iconsax-{$contents['buttons']['primary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                <span class="btn-flip-effect__text">{{ $contents['buttons']['primary_button']['label'] }}</span>
                            </a>
                        @endif

                        {{-- Secondary Button --}}
                        @if(!empty($contents['buttons']['secondary_button']) and !empty($contents['buttons']['secondary_button']['label']))
                            <a href="{{ !empty($contents['buttons']['secondary_button']['url']) ? $contents['buttons']['secondary_button']['url'] : '' }}" class="btn-flip-effect btn  btn-xlg gap-8" data-text="{{ $contents['buttons']['secondary_button']['label'] }}">
                                @if(!empty($contents['buttons']['secondary_button']['icon']))
                                    @svg("iconsax-{$contents['buttons']['secondary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-white"])
                                @endif

                                <span class="btn-flip-effect__text text-white">{{ $contents['buttons']['secondary_button']['label'] }}</span>
                            </a>
                        @endif
                    </div>
                @endif

            </div>
        </div>

    </div>
@endif
