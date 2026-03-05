@php
    $themeSpecificLinks = (new \App\Mixins\Themes\ThemeHeaderMixins())->getHeader1NavbarSpecificLinks($themeHeaderData['contents']);
    $themeSpecificButton = (new \App\Mixins\Themes\ThemeHeaderMixins())->getHeader1NavbarSpecificButton($themeHeaderData['contents']);
    $hasThemeSpecificButton = (!empty($themeSpecificButton) and !empty($themeSpecificButton['title']));
@endphp

<div class="theme-header-mobile__main-menu-drawer mobile-main-menu-drawer">
    {{-- First Section --}}
    <div class="d-flex align-items-center justify-content-between bg-white p-8 w-100 border-bottom-gray-200">
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
            <div class="js-view-cart-drawer position-relative d-flex-center size-32 bg-gray-100 rounded-8">
                <x-iconsax-lin-bag class="icons text-gray-500" width="20px" height="20px"/>
                <span class="js-cart-counter theme-header-mobile__cart-counter d-inline-flex-center font-12 text-white {{ ($userCartCount < 1) ? 'd-none' : '' }}">{{ $userCartCount }}</span>
            </div>
        </div>

        {{-- Close --}}
        <div class="js-close-header-main-menu-drawer d-flex-center size-48 rounded-12 border-gray-300">
            <x-iconsax-lin-add class="close-icon text-gray-500" width="24px" height="24px"/>
        </div>
    </div>

    {{-- Second Section --}}
    @if(!empty($authUser))
        <div class="js-mobile-header-show-specific-drawer d-flex align-items-center justify-content-between bg-white p-12 w-100 border-bottom-gray-200 cursor-pointer" data-drawer="auth-user">
            <div class="d-flex align-items-center">
                <div class="size-40 rounded-circle bg-gray-100">
                    <img src="{{ $authUser->getAvatar(32) }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}">
                </div>
                <div class="ml-12">
                    <h5 class="font-20 text-dark">{{ $authUser->full_name }}</h5>
                    <div class="mt-4 text-gray-500">{{ $authUser->role->caption }}</div>
                </div>
            </div>

            <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
        </div>
    @else
        <div class="d-flex align-items-center bg-white p-12 w-100 border-bottom-gray-200 ">
            <a href="/" class="theme-header-mobile__logo">
                @if(!empty($generalSettings['logo']))
                    <img src="{{ $generalSettings['logo'] }}" class="img-fluid light-only" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
                @endif

                @if(!empty($generalSettings['dark_mode_logo']))
                    <img src="{{ $generalSettings['dark_mode_logo'] }}" class="img-fluid dark-only" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
                @endif
            </a>
        </div>
    @endif

    {{-- Tabs --}}
    <div class="custom-tabs">
        <div class="px-16 border-bottom-gray-200">
            <div class="position-relative d-flex align-items-center">
                <div class="navbar-item tab-toggle-item d-flex-center cursor-pointer py-12 px-32 flex-1 active" data-tab-toggle data-tab-href="#mobileHeaderCategories">
                    <span class="">{{ trans('update.categories') }}</span>
                </div>

                <div class="tab-toggle-item-separator"></div>

                <div class="navbar-item tab-toggle-item d-flex-center cursor-pointer py-12 px-32 flex-1" data-tab-toggle data-tab-href="#mobileHeaderLinks">
                    <span class="">{{ trans('update.links') }}</span>
                </div>
            </div>
        </div>

        <div class="custom-tabs-body">
            <div class="custom-tabs-content {{ $hasThemeSpecificButton ? '' : 'without-bottom-specific-button' }} active" id="mobileHeaderCategories">
                @include('design_1.web.theme.headers.mobile.includes.main_menu.categories')
            </div>

            <div class="custom-tabs-content {{ $hasThemeSpecificButton ? '' : 'without-bottom-specific-button' }}" id="mobileHeaderLinks">
                @if(!empty($themeSpecificLinks) and count($themeSpecificLinks))
                    @foreach($themeSpecificLinks as $themeSpecificLink)
                        <a href="{{ $themeSpecificLink['url'] }}" class="d-block font-20 text-gray-500 py-12">{{ $themeSpecificLink['title'] }}</a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @if($hasThemeSpecificButton)
        <div class="border-top-gray-200 p-12">
            <a href="{{ $themeSpecificButton['url'] }}" class="btn btn-primary btn-lg btn-block gap-8 text-white">
                @if(!empty($themeSpecificButton['icon']))
                    @svg("iconsax-{$themeSpecificButton['icon']}", ['width' => '20px', 'height' => '20px', 'class' => "icons"])
                @endif

                <span class="text-white">{{ $themeSpecificButton['title'] }}</span>
            </a>
        </div>
    @endif

</div>
