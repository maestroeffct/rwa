@if(!empty($themeFooterData['contents']))
    @php
        $themeFooterContent = $themeFooterData['contents'];
        $themeFooterBackground = null;
        $themeFooterDarkBackground = null;
        $themeFooterBackgroundColor = "secondary";
        $themeFooterHasNewsletter = (!empty($themeFooterContent['newsletter']) and $themeFooterContent['newsletter']['enable'] == "on");

        if (!empty($themeFooterContent['dark_mode_background'])) {
            $themeFooterDarkBackground = $themeFooterContent['dark_mode_background'];
        }

        if (!empty($themeFooterContent['background'])) {
            $themeFooterBackground = $themeFooterContent['background'];
        }

        if (!empty($themeFooterContent['background_color'])) {
            $themeFooterBackgroundColor = $themeFooterContent['background_color'];
        }
    @endphp

    <div class="theme-footer-1 position-relative {{ $themeFooterHasNewsletter ? 'has-newsletter' : '' }}">
        <div class="theme-footer-1__section position-relative">
            <div class="theme-footer-1__section-bg-wrapper light-only" style="background-color: var({{ "--".$themeFooterBackgroundColor }}); {{ (!empty($themeFooterBackground) ? "background-image: url({$themeFooterBackground}); " : '') }}"></div>
            <div class="theme-footer-1__section-bg-wrapper dark-only" style="background-color: var({{ "--".$themeFooterBackgroundColor }}); {{ (!empty($themeFooterDarkBackground) ? "background-image: url({$themeFooterDarkBackground}); " : '') }}"></div>


            {{-- Newsletter --}}
            @if($themeFooterHasNewsletter)
                @include('design_1.web.theme.footers.footer_1.newsletter', ['newsletterData' => $themeFooterContent['newsletter']])
            @endif

            <div class="position-relative z-index-2">

                <div class="container position-relative">
                    <div class="row">
                        <div class="col-12 col-lg-5">
                            @if(!empty($themeFooterContent['cta']))
                                <div class="d-inline-flex-center gap-8 border-2 border-white rounded-32 bg-white-10 text-white px-16 py-12">
                                    @if(!empty($themeFooterContent['cta']['emoji']))
                                        <div class="size-24">
                                            <img src="{{ $themeFooterContent['cta']['emoji'] }}" alt="footer cta btn icon" class="img-fluid" width="24px" height="24px">
                                        </div>
                                    @endif

                                    @if(!empty($themeFooterContent['cta']['pre_title']))
                                        <span class="">{{ $themeFooterContent['cta']['pre_title'] }}</span>
                                    @endif
                                </div>

                                @if(!empty($themeFooterContent['cta']['title']))
                                    <h3 class="mt-16 font-44 text-white mr-0 mr-lg-48">{{ $themeFooterContent['cta']['title'] }}</h3>
                                @endif

                                @if(!empty($themeFooterContent['cta']['button']) and !empty($themeFooterContent['cta']['button']['label']))
                                    <a href="{{ (!empty($themeFooterContent['cta']['button']['url'])) ? $themeFooterContent['cta']['button']['url'] : '' }}" class="btn-flip-effect btn btn-xlg btn-primary gap-8 mt-32" data-text="{{ $themeFooterContent['cta']['button']['label'] }}">
                                        @if(!empty($themeFooterContent['cta']['button']['icon']))
                                            @svg("iconsax-{$themeFooterContent['cta']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                        @endif

                                        <span class="btn-flip-effect__text">{{ $themeFooterContent['cta']['button']['label'] }}</span>
                                    </a>
                                @endif
                            @endif
                        </div>

                        <div class="col-6 col-lg-2 mt-32 mt-lg-0">
                            @if(!empty($themeFooterContent['links_1_section_title']))
                                <h4 class="font-16 text-white">{{ $themeFooterContent['links_1_section_title'] }}</h4>
                            @endif

                            @if(!empty($themeFooterContent['specific_links']) and is_array($themeFooterContent['specific_links']))
                                @foreach($themeFooterContent['specific_links'] as $specificLink1Data)
                                    @if(!empty($specificLink1Data['title']) and !empty($specificLink1Data['url']))
                                        <a href="{{ $specificLink1Data['url'] }}" target="_blank" class="d-block font-16 text-white opacity-70 {{ $loop->first ? 'mt-16' : 'mt-12' }}">
                                            <span class="">{{ $specificLink1Data['title'] }}</span>
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <div class="col-6 col-lg-2 mt-32 mt-lg-0">
                            @if(!empty($themeFooterContent['links_2_section_title']))
                                <h4 class="font-16 text-white">{{ $themeFooterContent['links_2_section_title'] }}</h4>
                            @endif

                            @if(!empty($themeFooterContent['specific_links_2']) and is_array($themeFooterContent['specific_links_2']))
                                @foreach($themeFooterContent['specific_links_2'] as $specificLink2Data)
                                    @if(!empty($specificLink2Data['title']) and !empty($specificLink2Data['url']))
                                        <a href="{{ $specificLink2Data['url'] }}" target="_blank" class="d-block font-16 text-white opacity-70 {{ $loop->first ? 'mt-16' : 'mt-12' }}">
                                            <span class="">{{ $specificLink2Data['title'] }}</span>
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <div class="col-12 col-lg-3 mt-32 mt-lg-0">
                            @if(!empty($themeFooterContent['contact']))
                                @if(!empty($themeFooterContent['contact']['section_title']))
                                    <h4 class="font-16 text-white">{{ $themeFooterContent['contact']['section_title'] }}</h4>
                                @endif

                                @if(!empty($themeFooterContent['contact']['address']))
                                    <div class="d-flex align-items-start gap-8 mt-20">
                                        <div class="size-24">
                                            <x-iconsax-lin-location class="text-white" width="24px" height="24px"/>
                                        </div>
                                        <span class="font-16 text-white opacity-70">{{ $themeFooterContent['contact']['address'] }}</span>
                                    </div>
                                @endif

                                @if(!empty($themeFooterContent['contact']['phone']))
                                    <div class="d-flex align-items-start gap-8 mt-16">
                                        <div class="size-24">
                                            <x-iconsax-lin-call-calling class="text-white" width="24px" height="24px"/>
                                        </div>
                                        <span class="font-16 text-white opacity-70">{{ $themeFooterContent['contact']['phone'] }}</span>
                                    </div>
                                @endif

                                @if(!empty($themeFooterContent['contact']['mobile']))
                                    <div class="d-flex align-items-start gap-8 mt-16">
                                        <div class="size-24">
                                            <x-iconsax-lin-mobile class="text-white" width="24px" height="24px"/>
                                        </div>
                                        <span class="font-16 text-white opacity-70">{{ $themeFooterContent['contact']['mobile'] }}</span>
                                    </div>
                                @endif

                                @if(!empty($themeFooterContent['contact']['email']))
                                    <div class="d-flex align-items-start gap-8 mt-16">
                                        <div class="size-24">
                                            <x-iconsax-lin-sms class="text-white" width="24px" height="24px"/>
                                        </div>
                                        <span class="font-16 text-white opacity-70">{{ $themeFooterContent['contact']['email'] }}</span>
                                    </div>
                                @endif
                            @endif
                        </div>


                    </div>
                </div>

                <div class="theme-footer-1__bottom-section-divider"></div>

                <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between py-24 px-16 gap-16">
                    @if(!empty($themeFooterContent['copyright_text']))
                        <div class="font-14 text-white opacity-70">{{ $themeFooterContent['copyright_text'] }}</div>
                    @endif

                    <div class="d-flex align-items-center justify-content-center gap-16 gap-lg-24">
                        @if(!empty($themeFooterContent['social_media']) and is_array($themeFooterContent['social_media']))
                            @php
                                $appSocials = getSocials();
                                if (!empty($appSocials) and count($appSocials)) {
                                    $appSocials = collect($appSocials)->sortBy('order')->toArray();
                                }
                            @endphp

                            @foreach($appSocials as $socialKey => $socialValue)
                                @if(in_array($socialKey, $themeFooterContent['social_media']))
                                    @if(!empty($socialValue['title']) and !empty($socialValue['link']) and !empty($socialValue['image']))
                                        <a href="{{ $socialValue['link'] }}" target="_blank" rel="nofollow" title="{{ $socialValue['title'] }}" class="d-flex-center size-24">
                                            <img src="{{ $socialValue['image'] }}" alt="{{ $socialValue['title'] }}" class="img-cover">
                                        </a>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endif
