@if(!empty($themeHeaderData['contents']))
    @php
        $getUserLanguageAndLocale = getUserLanguagesLists();
        $userCurrency = currency();
        $themeHeaderTopNavData = !empty($themeHeaderData['contents']['top_navbar']) ? $themeHeaderData['contents']['top_navbar'] : [];
    @endphp

    <div id="themeHeaderVacuum"></div>
    <div class="theme-header-mobile">
        {{-- First Section --}}
        <div class="d-flex align-items-center gap-8 bg-white p-8 w-100">
            <form action="/search" method="get" class="theme-header-mobile__search-form position-relative flex-1">
                <input class="form-control bg-transparent" type="text" name="search" placeholder="{{ trans('navbar.search_anything') }}" aria-label="Search">

                <button type="submit" class="btn-transparent d-flex-center search-icon">
                    <x-iconsax-lin-search-normal class="icons text-gray-400" width="16px" height="16px"/>
                </button>
            </form>

            {{-- Multi Color (Dark,Light) --}}
            @if(!empty($themeHeaderTopNavData) and !empty($themeHeaderTopNavData['show_color_mode']))
                <div class="js-theme-color-toggle theme-color-toggle {{ "{$userThemeColorMode}-mode" }} d-flex-center size-48 border-gray-300 rounded-12">
                    <x-iconsax-lin-moon class="dark-icon icons text-gray-500" width="16px" height="16px"/>
                    <x-iconsax-lin-sun-1 class="light-icon icons text-gray-500" width="16px" height="16px"/>
                </div>
            @endif
        </div>

        {{-- Second Section --}}
        <div class="d-flex align-items-center justify-content-between bg-white p-8 w-100 border-top-gray-200 border-bottom-gray-200">
            <div class="d-flex align-items-center gap-8">
                {{-- Language --}}
                @foreach($getUserLanguageAndLocale as $localeSign => $language)
                    @if(mb_strtolower(app()->getLocale()) == mb_strtolower($localeSign))
                        <div class="js-mobile-header-show-specific-drawer d-flex-center size-32 bg-gray-100 rounded-8" data-drawer="language">
                            <img src="{{ asset('vendor/blade-country-flags/4x3-'. mb_strtolower(localeToCountryCode(mb_strtoupper($localeSign))) .'.svg') }}" class="img-fluid" width="16px" height="16px" alt="{{ $language }} {{ trans('flag') }}"/>
                        </div>
                    @endif
                @endforeach

                {{-- Currency --}}
                @if(!empty($currencies) and count($currencies))
                    @foreach($currencies as $currencyItem)
                        @if($userCurrency == $currencyItem->currency)
                            <div class="js-mobile-header-show-specific-drawer d-flex-center size-32 bg-gray-100 rounded-8" data-drawer="currency">
                                <span class="text-gray-500">{{ currencySign($currencyItem->currency) }}</span>
                            </div>
                        @endif
                    @endforeach
                @endif

                {{-- Cart --}}
                @if(!isFreeModeEnabled() || isFreeModeShowCartEnabled())
                    <div class="js-view-cart-drawer position-relative d-flex-center size-32 bg-gray-100 rounded-8">
                        <x-iconsax-lin-bag class="icons text-gray-500" width="20px" height="20px"/>
                        <span class="js-cart-counter theme-header-mobile__cart-counter d-inline-flex-center font-12 text-white {{ ($userCartCount < 1) ? 'd-none' : '' }}">{{ $userCartCount }}</span>
                    </div>
                @endif
            </div>

            {{-- Link Or User --}}
            @if(auth()->check())
                <div class="js-mobile-header-show-specific-drawer d-flex align-items-center gap-8" data-drawer="auth-user">
                    <div class="size-32 rounded-circle bg-gray-100">
                        <img src="{{ $authUser->getAvatar(32) }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}">
                    </div>
                    <div class="text-dark">{{ $authUser->full_name }}</div>
                </div>
            @else
                <div class="d-flex align-items-center gap-8">
                    @if(!empty($themeHeaderTopNavData['link_1']) and !empty($themeHeaderTopNavData['link_1']['title']))
                        <a href="{{ !empty($themeHeaderTopNavData['link_1']['url']) ? $themeHeaderTopNavData['link_1']['url'] : '#!' }}" class="d-flex align-items-center text-gray-500">
                            <span class="">{{ $themeHeaderTopNavData['link_1']['title'] }}</span>
                        </a>
                    @endif

                    @if(!empty($themeHeaderTopNavData['link_1']) and !empty($themeHeaderTopNavData['link_1']['title']) and !empty($themeHeaderTopNavData['link_2']) and !empty($themeHeaderTopNavData['link_2']['title']))
                        <div class="theme-header-mobile__dot-separator"></div>
                    @endif

                    @if(!empty($themeHeaderTopNavData['link_2']) and !empty($themeHeaderTopNavData['link_2']['title']))
                        <a href="{{ !empty($themeHeaderTopNavData['link_2']['url']) ? $themeHeaderTopNavData['link_2']['url'] : '#!' }}" class="d-flex align-items-center text-gray-500">
                            <span class="">{{ $themeHeaderTopNavData['link_2']['title'] }}</span>
                        </a>
                    @endif
                </div>
            @endif
        </div>

        {{-- Third Section --}}
        <div id="themeHeaderSticky" class="theme-header-mobile__sticky d-flex align-items-center justify-content-between bg-white p-8 w-100 border-bottom-gray-200">
            <a href="/" class="theme-header-mobile__logo">
                @if(!empty($generalSettings['logo']))
                    <img src="{{ $generalSettings['logo'] }}" class="img-fluid light-only" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
                @endif

                @if(!empty($generalSettings['dark_mode_logo']))
                    <img src="{{ $generalSettings['dark_mode_logo'] }}" class="img-fluid dark-only" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
                @endif
            </a>

            <div class="js-mobile-header-show-specific-drawer d-flex-center size-40 rounded-10 border-gray-300 cursor-pointer" data-drawer="main-menu">
                <x-iconsax-lin-menu class="icons text-gray-500" width="20px" height="20px"/>
            </div>
        </div>
    </div>

    {{-- Drawers --}}
    @include('design_1.web.theme.headers.mobile.includes.language_drawer')
    @include('design_1.web.theme.headers.mobile.includes.main_menu_drawer')

    @if(!empty($currencies) and count($currencies))
        @include('design_1.web.theme.headers.mobile.includes.currency_drawer')
    @endif

    @if(!empty($authUser))
        @include('design_1.web.theme.headers.mobile.includes.auth_user_drawer')
    @endif

@endif

@push('scripts_bottom')
    <script src="/assets/design_1/js/parts/theme/headers/mobile.min.js"></script>
@endpush
