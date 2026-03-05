@if(!empty($themeFooterData['contents']))
    @php
        $themeFooterContent = $themeFooterData['contents'];
        $themeFooterBackground = null;
        $themeFooterDarkBackground = null;
        $themeFooterBackgroundColor = "secondary";

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

    <div class="theme-footer-2 position-relative ">
        <div class="theme-footer-2__bg-wrapper light-only" style="background-color: var({{ "--".$themeFooterBackgroundColor }}); {{ (!empty($themeFooterBackground) ? "background-image: url({$themeFooterBackground}); " : '') }}"></div>
        <div class="theme-footer-2__bg-wrapper dark-only" style="background-color: var({{ "--".$themeFooterBackgroundColor }}); {{ (!empty($themeFooterDarkBackground) ? "background-image: url({$themeFooterDarkBackground}); " : '') }}"></div>

        <div class="position-relative z-index-3 container pt-40 pb-16">

            {{-- Back to top --}}
            @if(!empty($themeFooterContent['enable_back_to_top']))
                <div class="js-back-to-top-btn theme-footer-2__back-to-top d-flex-center size-48 rounded-circle bg-primary cursor-pointer" data-speed="2000">
                    <x-iconsax-bul-arrow-circle-up class="icons text-white" width="28px" height="28px"/>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-12 col-lg-6 d-flex flex-column align-items-center text-center">

                    @if(!empty($themeFooterContent['enable_logo']))
                        <a href="/" class="theme-footer-2__logo">
                            @if(!empty($generalSettings['logo']))
                                <img src="{{ $generalSettings['logo'] }}" class="img-fluid light-only" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
                            @endif

                            @if(!empty($generalSettings['dark_mode_logo']))
                                <img src="{{ $generalSettings['dark_mode_logo'] }}" class="img-fluid dark-only" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
                            @endif
                        </a>
                    @endif

                    @if(!empty($themeFooterContent['title']))
                        <h3 class="font-24 text-white mt-16">{{ $themeFooterContent['title'] }}</h3>
                    @endif

                    @if(!empty($themeFooterContent['description']))
                        <p class="font-16 text-white mt-8 opacity-70">{!! nl2br($themeFooterContent['description']) !!}</p>
                    @endif
                </div>
            </div>

            {{-- Links --}}
            @if(!empty($themeFooterContent['specific_links']) and is_array($themeFooterContent['specific_links']))
                <div class="d-flex-center flex-wrap gap-20 gap-lg-60 mt-48">
                    @foreach($themeFooterContent['specific_links'] as $specificLink1Data)
                        @if(!empty($specificLink1Data['title']) and !empty($specificLink1Data['url']))
                            <a href="{{ $specificLink1Data['url'] }}" target="_blank" class="btn-flip-effect btn-flip-effect__slow-effect btn-flip-effect__left-minus-54 text-white" data-text="{{ $specificLink1Data['title'] }}">
                                <span class="btn-flip-effect__text">{{ $specificLink1Data['title'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- Copy --}}
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between w-100 mt-28 rounded-16 p-16 p-lg-24 border-gray-500">
                @if(!empty($themeFooterContent['copyright_text']))
                    <div class="font-14 text-white opacity-70">{{ $themeFooterContent['copyright_text'] }}</div>
                @endif

                {{-- Social --}}
                <div class="d-flex-center flex-wrap flex-lg-nowrap gap-16 gap-lg-24 mt-20 mt-lg-0">
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
@endif
