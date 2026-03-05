<div class="theme-header-1__top-navbar bg-primary pb-54 pt-12">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-12 col-lg-4">
                <div class="d-flex align-items-center gap-24">
                    {{-- Phone --}}
                    @if(!empty($themeHeaderTopNavData['phone']))
                        <div class="d-flex align-items-center gap-8 opacity-75">
                            <x-iconsax-lin-call-calling class="icons text-white" width="16px" height="16x"/>
                            <span class="text-white">{{ $themeHeaderTopNavData['phone'] }}</span>
                        </div>
                    @endif

                    {{-- Email --}}
                    @if(!empty($themeHeaderTopNavData['email']))
                        <div class="d-flex align-items-center gap-8 opacity-75">
                            <x-iconsax-lin-sms class="icons text-white" width="16px" height="16x"/>
                            <span class="text-white">{{ $themeHeaderTopNavData['email'] }}</span>
                        </div>
                    @endif

                    {{-- Multi Color (Dark,Light) --}}
                    @if(!empty($themeHeaderTopNavData['show_color_mode']))
                        <div class="js-theme-color-toggle theme-color-toggle {{ "{$userThemeColorMode}-mode" }} d-flex-center size-16 opacity-75">
                            <x-iconsax-lin-moon class="dark-icon icons text-white" width="16px" height="16px"/>
                            <x-iconsax-lin-sun-1 class="light-icon icons text-white" width="16px" height="16px"/>
                        </div>
                    @endif

                </div>
            </div>

            <div class="col-12 col-lg-8 mt-12 mt-lg-0">
                <div class="row">
                    {{-- Search --}}
                    <div class="col-12 col-lg-4">
                        <form action="/search" method="get" class="theme-header-1__top-navbar-search position-relative">
                            <input class="form-control bg-transparent opacity-75" type="text" name="search" placeholder="{{ trans('navbar.search_anything') }}" aria-label="Search">

                            <button type="submit" class="btn-transparent d-flex-center search-icon">
                                <x-iconsax-lin-search-normal class="icons text-white opacity-75" width="16px" height="16px"/>
                            </button>
                        </form>
                    </div>
                     @if(auth()->check())
                        <div class="col-12 col-lg-8 mt-12 mt-lg-4">
                    @else
                    <div class="col-12 col-lg-8 mt-12 mt-lg-8">
                     @endif    
                        <div class="d-flex align-items-center justify-content-between gap-12 gap-lg-24">
                            <div class="d-flex align-items-center gap-12 gap-lg-24">
                                {{-- Language --}}
                                @include('design_1.web.theme.headers.header_1.includes.language')

                                {{-- Currency --}}
                                @include('design_1.web.theme.headers.header_1.includes.currency')

                                {{-- Cart --}}
                                @if(!isFreeModeEnabled() || isFreeModeShowCartEnabled())
                                    <div class="js-view-cart-drawer position-relative d-flex-center size-32 bg-white-10 rounded-8 cursor-pointer">
                                        <x-iconsax-lin-bag class="icons text-white opacity-75" width="20px" height="20px"/>
                                        <span class="js-cart-counter theme-header-1__top-navbar-cart-counter d-inline-flex-center font-12 text-white {{ ($userCartCount < 1) ? 'd-none' : '' }}">{{ $userCartCount }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex align-items-center">
                                @if(auth()->check())
                                    @include('design_1.web.theme.headers.header_1.includes.auth_user_info')
                                @else
                                    @if(!empty($themeHeaderTopNavData['link_1']) and !empty($themeHeaderTopNavData['link_1']['title']))
                                        <a href="{{ !empty($themeHeaderTopNavData['link_1']['url']) ? $themeHeaderTopNavData['link_1']['url'] : '#!' }}" class="d-flex align-items-center text-white opacity-75">
                                            <span class="">{{ $themeHeaderTopNavData['link_1']['title'] }}</span>
                                        </a>
                                    @endif

                                    @if(!empty($themeHeaderTopNavData['link_2']) and !empty($themeHeaderTopNavData['link_2']['title']))
                                        <a href="{{ !empty($themeHeaderTopNavData['link_2']['url']) ? $themeHeaderTopNavData['link_2']['url'] : '#!' }}" class="d-flex align-items-center text-white opacity-75 ml-32">
                                            <span class="">{{ $themeHeaderTopNavData['link_2']['title'] }}</span>
                                        </a>
                                    @endif
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
