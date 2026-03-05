@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $slidingEventsBackgroundColor = "secondary";
        $slidingEventsBackground = null;
        $slidingEventsDarkBackground = null;

        if (!empty($contents['dark_mode_background'])) {
            $slidingEventsDarkBackground = $contents['dark_mode_background'];
        }

        if (!empty($contents['background'])) {
            $slidingEventsBackground = $contents['background'];
        }

        if (!empty($contents['background_color'])) {
            $slidingEventsBackgroundColor = $contents['background_color'];
        }

        $slidingEventsSource = !empty($contents['events_source']) ? $contents['events_source'] : 'newest_events';
        $slidingEventsCount = !empty($contents['number_of_events']) ? $contents['number_of_events'] : 8;

        $slidingEventsComponentMixins = (new \App\Mixins\LandingBuilder\SlidingEventsComponentMixins());
        $slidingEvents = $slidingEventsComponentMixins->getEventsBySource($slidingEventsSource, $slidingEventsCount);
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("sliding_events") }}">
    @endpush

    <section class="sliding-events-section position-relative">
        <div class="container sliding-events-section__contents position-relative rounded-32" style="background-color: var({{ "--".$slidingEventsBackgroundColor }});">
            <div class="sliding-events-section__contents-bg-wrapper rounded-32  {{ (!empty($slidingEventsDarkBackground)) ? 'light-only' : '' }}" @if(!empty($slidingEventsBackground)) style="background-image: url({{ $slidingEventsBackground }})" @endif></div>

            @if(!empty($slidingEventsDarkBackground))
                <div class="sliding-events-section__contents-bg-wrapper rounded-32 dark-only" style="background-image: url({{ $slidingEventsDarkBackground }})" ></div>
            @endif

            <div class="row justify-content-center w-100 position-relative z-index-2 w-100 h-100">
                <div class="col-12 col-lg-6 position-relative  text-center">
                    @if(!empty($contents['main_content']))
                        @if(!empty($contents['main_content']['pre_title']))
                            <div class="d-inline-flex-center gap-8 py-8 px-16 rounded-8 border-white bg-white-20 font-12 text-white mb-8">
                                <span class="">{{ $contents['main_content']['pre_title'] }}</span>
                            </div>
                        @endif

                        @if(!empty($contents['main_content']['title']))
                            <h2 class="font-32 text-white mb-16">{{ $contents['main_content']['title'] }}</h2>
                        @endif

                        @if(!empty($contents['main_content']['description']))
                            <p class="font-16 text-white">{{ $contents['main_content']['description'] }}</p>
                        @endif
                    @endif

                    {{-- Links --}}
                    @if($contents['specific_links'] and is_array($contents['specific_links']))
                        <div class="d-flex-center flex-wrap gap-16 mt-24">
                            @foreach($contents['specific_links'] as $specificLinkData)
                                @if(!empty($specificLinkData['title']) and !empty($specificLinkData['url']))
                                    <a href="{{ $specificLinkData['url'] }}" target="_blank" class="font-16 font-weight-bold text-white opacity-70">{{ $specificLinkData['title'] }}</a>

                                    @if(!$loop->last)
                                        <div class="sliding-events-section__circle-dot-separator"></div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($contents['buttons']['primary_button']) or !empty($contents['buttons']['secondary_button']))
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-center mt-24 gap-8">
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
                                <a href="{{ !empty($contents['buttons']['secondary_button']['url']) ? $contents['buttons']['secondary_button']['url'] : '' }}" class="btn-flip-effect btn  btn-xlg gap-8" data-text="{{ $contents['buttons']['secondary_button']['label'] }}">
                                    @if(!empty($contents['buttons']['secondary_button']['icon']))
                                        @svg("iconsax-{$contents['buttons']['secondary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-white"])
                                    @endif

                                    <span class="btn-flip-effect__text text-white">{{ $contents['buttons']['secondary_button']['label'] }}</span>
                                </a>
                            @endif
                        </div>
                    @endif

                </div>{{-- End Col --}}
            </div> {{-- End Row --}}
        </div>

        {{-- Slider --}}
        @if(!empty($slidingEvents) and count($slidingEvents))
            <div class="sliding-events-section__slider-container">
                @if(count($slidingEvents) <= 4)
                    <div class="container">
                        <div class="row">
                            @include('design_1.web.events.components.cards.grids.index',['events' => $slidingEvents, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-24 mt-lg-0"])
                        </div>
                    </div>
                @else

                    <div class="swiper-container js-make-swiper js-sliding-events-swiper"
                         data-item="js-sliding-events-swiper"
                         data-space-between="24"
                         data-autoplay="true"
                         data-loop="false"
                         data-speed="3000"
                         data-breakpoints="1600:6,1200:5,991:3,660:2"
                    >
                        <div class="swiper-wrapper">
                            @include('design_1.web.events.components.cards.grids.index',['events' => $slidingEvents, 'gridCardClassName' => "swiper-slide"])
                        </div>
                    </div>
                @endif
            </div>
        @endif

    </section>
@endif
