@php
    $getUserLanguageAndLocale = getUserLanguagesLists();
@endphp

<div class="js-language-select language-select position-relative {{ !empty($langClassName) ? $langClassName : '' }}">
    <form action="/locale" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="locale" value="{{ app()->getLocale() }}">

        @foreach($getUserLanguageAndLocale as $localeSign => $language)
            @if(mb_strtolower(app()->getLocale()) == mb_strtolower($localeSign))
                <div class="language-toggle d-flex align-items-center">
                    <div class="size-32 d-flex-center bg-gray-100 rounded-8">
                        <x-iconsax-lin-global class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                </div>
            @endif
        @endforeach
    </form>

    <div class="language-dropdown py-8">

        <div class="py-8 px-16 font-12 text-gray-500">{{ trans('update.select_a_language') }}</div>

        @foreach($getUserLanguageAndLocale as $localeSign => $language)
            <div class="js-language-dropdown-item language-dropdown__item cursor-pointer {{ (mb_strtolower(app()->getLocale()) == mb_strtolower($localeSign)) ? 'active' : '' }}" data-value="{{ $localeSign }}" data-title="{{ $language }}">
                <div class=" d-flex align-items-center w-100 px-16 py-8 text-dark bg-transparent">
                    <div class="language-dropdown__flag">
                        <img src="{{ asset('vendor/blade-country-flags/4x3-'. mb_strtolower(localeToCountryCode(mb_strtoupper($localeSign))) .'.svg') }}" class="img-cover" alt="{{ $language }} {{ trans('flag') }}"/>
                    </div>
                    <span class="ml-8 font-14">{{ $language }}</span>
                </div>
            </div>
        @endforeach

    </div>
</div>
