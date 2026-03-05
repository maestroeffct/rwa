<div class="js-language-select">
    <form action="/locale" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="locale" value="{{ app()->getLocale() }}">
    </form>

    <div class="theme-header-mobile__drawer mobile-language-drawer">
        <div class="theme-header-mobile__drawer-back-drop"></div>

        <div class="theme-header-mobile__drawer-body py-16">
            <div class="d-flex align-items-center justify-content-between px-16 mb-12">
                <h4 class="font-22">{{ trans('update.select_a_language') }}</h4>

                <div class="js-close-header-drawer d-flex-center size-48 rounded-12 border-gray-300">
                    <x-iconsax-lin-add class="close-icon text-gray-500" width="24px" height="24px"/>
                </div>
            </div>

            @foreach($getUserLanguageAndLocale as $localeSign => $language)
                <div class="js-language-dropdown-item cursor-pointer d-flex align-items-center w-100 px-16 py-8 text-dark {{ (mb_strtolower(app()->getLocale()) == mb_strtolower($localeSign)) ? 'bg-gray-100 font-weight-bold' : '' }}" data-value="{{ $localeSign }}" data-title="{{ $language }}">
                    <div class="size-32">
                        <img src="{{ asset('vendor/blade-country-flags/4x3-'. mb_strtolower(localeToCountryCode(mb_strtoupper($localeSign))) .'.svg') }}" class="img-cover" alt="{{ $language }} {{ trans('flag') }}"/>
                    </div>
                    <span class="ml-8 font-20">{{ $language }}</span>
                </div>
            @endforeach

        </div>
    </div>
</div>
