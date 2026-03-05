<div class="panel-header d-flex align-items-center bg-white px-24 px-lg-0">

    <div class="panel-header__logo-box">
        <a href="/" class="panel-header__logo d-inline-flex-center mr-16 mr-lg-0 ml-lg-32">
            @if(!empty($generalSettings['logo']))
                <img src="{{ $generalSettings['logo'] }}" class="img-fluid light-only" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
            @endif

            @if(!empty($generalSettings['dark_mode_logo']))
                <img src="{{ $generalSettings['dark_mode_logo'] }}" class="img-fluid dark-only" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
            @endif
        </a>
    </div>

    <div class="panel-header__contents d-flex align-items-center justify-content-between h-100 border-bottom-gray-200">

        <div class="d-flex align-items-center">

            {{-- Multi Color (Dark,Light) --}}
            <div class="js-theme-color-toggle theme-color-toggle theme-color-toggle__panel {{ "{$userThemeColorMode}-mode" }} d-flex-center size-16 bg-gray-100 rounded-8 mr-16 mr-lg-32">
                <x-iconsax-lin-moon class="dark-icon icons text-gray-500" width="20px" height="20px"/>
                <x-iconsax-lin-sun-1 class="light-icon icons text-gray-500" width="20px" height="20px"/>
            </div>

            @if(!empty($panelNavbarLinks))
                @php
                    $panelNavbarLinksItems = handleNavbarLinks($panelNavbarLinks)
                @endphp

                @if(!empty($panelNavbarLinksItems) and count($panelNavbarLinksItems))
                    <div class="d-none d-lg-flex align-items-center">
                        @foreach($panelNavbarLinksItems as $panelNavbarLinkItem)
                            <a href="{{ $panelNavbarLinkItem['link'] }}" class="navbar-item navbar-item-h-70 d-flex align-items-center mr-16 mr-lg-32 text-gray-500">{{ $panelNavbarLinkItem['title'] }}</a>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        <div class="d-flex align-items-center">

            @if($authUser->checkAccessToAIContentFeature())
                <div class="js-show-ai-content-drawer d-none d-lg-flex align-items-center justify-content-center size-32 rounded-8 bg-gray-100 mr-16 cursor-pointer">
                    <x-iconsax-lin-cpu-charge class="icons text-gray-500" width="20px" height="20px"/>
                </div>
            @endif


            @include('design_1.panel.includes.header.currency')


            <div class="mx-16">
                @include('design_1.panel.includes.header.language')
            </div>

            <div class="panel-header__contents-with-line d-flex align-items-center gap-16 mr-16">

                @if(!isFreeModeEnabled() || isFreeModeShowCartEnabled())
                    <div class="js-view-cart-drawer size-32 position-relative d-flex-center bg-gray-100 rounded-8 cursor-pointer">
                        <x-iconsax-lin-bag-happy class="icons text-gray-500" width="20px" height="20px"/>
                        <span class="js-cart-counter panel-header__badge-counter badge-counter bg-success font-12 {{ ($userCartCount < 1) ? 'd-none' : '' }}">{{ $userCartCount }}</span>
                    </div>
                @endif

                {{-- Notification --}}
                <div class="d-none d-lg-flex">
                    @include('design_1.panel.includes.header.notification')
                </div>
            </div>

            @include('design_1.panel.includes.header.auth_user_info')
        </div>
    </div>
</div>
