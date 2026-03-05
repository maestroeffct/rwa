@php
    $cookieSecuritySettings = getCookieSettings();
@endphp

@if(!empty($cookieSecuritySettings['dialog_title']) and !empty($cookieSecuritySettings['cookie_settings_modal_message']))

    <div class="js-cookie-security-dialog-box">
        <div class="cookie-security-dialog d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-secondary rounded-24 soft-shadow-5">
            <div class="d-flex align-items-center flex-1 mr-12">
                <div class="size-24">
                    <x-iconsax-bul-shield-tick class="icons text-white" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <h6 class="font-14 text-white">{{ $cookieSecuritySettings['dialog_title'] }}</h6>
                    <div class="mt-4 font-12 text-white opacity-50">{!! nl2br($cookieSecuritySettings['dialog_description']) !!}</div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-12 mt-16 mt-lg-0 flex-1">
                <button type="button" class="js-show-cookie-customize-settings btn btn-lg btn-transparent font-14 text-white opacity-50 flex-1">{{ trans('update.more_details') }}</button>
                <button type="button" class="js-accept-all-cookies btn btn-lg bg-white text-secondary font-14 flex-1">{{ trans('update.accept_all') }}</button>
            </div>
        </div>
    </div>

    @push('scripts_bottom')
        <script>
            var cookieInformationLang = '{{ trans('update.cookie_information') }}';
            var confirmMyChoicesLang = '{{ trans('update.confirm_my_choices') }}';
            var acceptAllLang = '{{ trans('update.accept_all') }}';
        </script>
        <script src="{{ getDesign1ScriptPath("cookie_security") }}"></script>
    @endpush
@endif
