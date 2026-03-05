@php
    $themeSpecificLinks = (new \App\Mixins\Themes\ThemeHeaderMixins())->getHeader1NavbarSpecificLinks($themeHeaderData['contents']);
    $themeSpecificButton = (new \App\Mixins\Themes\ThemeHeaderMixins())->getHeader1NavbarSpecificButton($themeHeaderData['contents']);
@endphp

<div id="themeHeaderSticky" class="theme-header-2__main bg-white w-100">
    <div class="theme-header-2__main-mask"></div>
    <div class="position-relative z-index-2 bg-white w-100 h-100">
        <div class="container h-100 position-relative">
            <div class="row align-items-center h-100">
                {{-- Logo --}}
                <div class="col-6 col-lg-2">
                    <a href="/" class="theme-header-2__logo text-left d-block">
                        @if(!empty($generalSettings['logo']))
                            <img src="{{ $generalSettings['logo'] }}" class="img-fluid light-only" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
                        @endif

                        @if(!empty($generalSettings['dark_mode_logo']))
                            <img src="{{ $generalSettings['dark_mode_logo'] }}" class="img-fluid dark-only" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
                        @endif
                    </a>
                </div>

                {{-- Category --}}
                <div class="col-6 col-lg-2">
                    @include('design_1.web.theme.headers.header_2.includes.categories')
                </div>

                {{-- Links --}}
                <div class="col-6 col-lg-5">
                    @if(!empty($themeSpecificLinks) and count($themeSpecificLinks))
                        <div class="d-flex align-items-center gap-16 gap-lg-32">
                            @foreach($themeSpecificLinks as $themeSpecificLink)
                                <a href="{{ $themeSpecificLink['url'] }}" class="text-dark">{{ $themeSpecificLink['title'] }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Right Button --}}
                <div class="col-6 col-lg-3 d-flex align-items-center justify-content-end">
                    @if(!empty($themeSpecificButton) and !empty($themeSpecificButton['title']))
                        <a href="{{ $themeSpecificButton['url'] }}" class="btn-flip-effect {{ (!empty($themeSpecificButton['icon'])) ? '' : 'btn-flip-effect__no-side' }} btn btn-primary btn-lg gap-8 text-white" data-text="{{ $themeSpecificButton['title'] }}">
                            @if(!empty($themeSpecificButton['icon']))
                                @svg("iconsax-{$themeSpecificButton['icon']}", ['width' => '20px', 'height' => '20px', 'class' => "icons"])
                            @endif

                            <span class="btn-flip-effect__text text-white">{{ $themeSpecificButton['title'] }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
