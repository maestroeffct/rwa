@extends('design_1.web.auth.theme_1.layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@php
    $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
    $showOtherRegisterMethod = getFeaturesSettings('show_other_register_method') ?? false;
    $showCertificateAdditionalInRegister = getFeaturesSettings('show_certificate_additional_in_register') ?? false;
    $selectRolesDuringRegistration = getFeaturesSettings('select_the_role_during_registration') ?? null;
@endphp

@section('page_content')
    <form method="Post" action="/register" class="mt-16">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="pl-16 ">
            <div class="font-16 font-weight-bold">{{ trans('update.join_us_now!') }} 😊</div>
            <h1 class="font-24 mt-4">{{ trans('update.create_an_account') }}</h1>
        </div>

        <div class="auth-page-form-container pr-16 mt-16 pt-16" data-simplebar @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>
            {{-- Role --}}
            @if(!empty($selectRolesDuringRegistration) and count($selectRolesDuringRegistration))
                <div class="mb-28">
                    <div class="font-12 text-gray-500">{{ trans('update.select_a_role') }}</div>

                    <div class="d-flex align-items-center gap-4 p-4 rounded-12 border-gray-300 mt-8">
                        <div class="auth-register-method-item flex-1">
                            <input type="radio" name="account_type" value="user" id="userRole" class="" {{ (empty(old('account_type')) or old('account_type') == "user") ? 'checked' : '' }}>
                            <label class="d-flex-center cursor-pointer" for="userRole">{{ trans('update.role_user') }}</label>
                        </div>

                        @foreach($selectRolesDuringRegistration as $selectRole)
                            <div class="auth-register-method-item flex-1">
                                <input type="radio" name="account_type" value="{{ $selectRole }}" id="{{ $selectRole }}Role" class="" {{ (old('account_type') == $selectRole) ? 'checked' : '' }}>
                                <label class="d-flex-center cursor-pointer" for="{{ $selectRole }}Role">{{ trans("update.role_{$selectRole}") }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($registerMethod == 'mobile')
                @include('design_1.web.auth.theme_1.includes.mobile_field')

                @if($showOtherRegisterMethod)
                    @include('design_1.web.auth.theme_1.includes.email_field', ['optional' => true])
                @endif
            @else
                @include('design_1.web.auth.theme_1.includes.email_field')

                @if($showOtherRegisterMethod)
                    @include('design_1.web.auth.theme_1.includes.mobile_field', ['optional' => true])
                @endif
            @endif


            <div class="form-group">
                <label class="form-group-label" for="full_name">{{ trans('auth.full_name') }}:</label>
                <input name="full_name" type="text" value="{{ old('full_name') }}" class="form-control @error('full_name') is-invalid @enderror">
                @error('full_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-group-label" for="password">{{ trans('auth.password') }}:</label>
                <input name="password" type="password"
                       class="form-control @error('password') is-invalid @enderror" id="password"
                       aria-describedby="passwordHelp">

                <div class="password-input-visibility cursor-pointer size-24">
                    <x-iconsax-lin-eye-slash class="icons-eye-slash text-gray-400 d-none" width="24px" height="24px"/>
                    <x-iconsax-lin-eye class="icons-eye text-gray-400 " width="24px" height="24px"/>
                </div>

                @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group ">
                <label class="form-group-label" for="confirm_password">{{ trans('auth.retype_password') }}:</label>
                <input name="password_confirmation" type="password"
                       class="form-control @error('password_confirmation') is-invalid @enderror" id="confirm_password"
                       aria-describedby="confirmPasswordHelp">

                <div class="password-input-visibility cursor-pointer size-24">
                    <x-iconsax-lin-eye-slash class="icons-eye-slash text-gray-400 d-none" width="24px" height="24px"/>
                    <x-iconsax-lin-eye class="icons-eye text-gray-400 " width="24px" height="24px"/>
                </div>

                @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            @if($showCertificateAdditionalInRegister)
                <div class="form-group">
                    <label class="form-group-label" for="certificate_additional">{{ trans('update.certificate_additional') }}</label>
                    <input type="text" name="certificate_additional" id="certificate_additional" class="form-control @error('certificate_additional') is-invalid @enderror"/>
                    @error('certificate_additional')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            @endif

            @if(getFeaturesSettings('timezone_in_register'))
                @php
                    $selectedTimezone = getGeneralSettings('default_time_zone');
                @endphp

                <div class="form-group js-auth-timezone-container">
                    <label class="form-group-label">{{ trans('update.timezone') }}</label>
                    <select name="timezone" class="form-control select2" data-allow-clear="false" data-dropdown-parent=".js-auth-timezone-container">
                        <option value="" {{ empty($user->timezone) ? 'selected' : '' }} disabled>{{ trans('public.select') }}</option>
                        @foreach(getListOfTimezones() as $timezone)
                            <option value="{{ $timezone }}" @if($selectedTimezone == $timezone) selected @endif>{{ $timezone }}</option>
                        @endforeach
                    </select>
                    @error('timezone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            @endif

            @if(!empty($referralSettings) and $referralSettings['status'])
                <div class="form-group ">
                    <label class="form-group-label" for="referral_code">{{ trans('financial.referral_code') }}:</label>
                    <input name="referral_code" type="text"
                           class="form-control @error('referral_code') is-invalid @enderror" id="referral_code"
                           value="{{ !empty($referralCode) ? $referralCode : old('referral_code') }}"
                           aria-describedby="confirmPasswordHelp" autocomplete="off">
                    @error('referral_code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            @endif

            <div class="js-form-fields-card">
                @if(!empty($formFields))
                    {!! $formFields !!}
                @endif
            </div>

            @if(!empty(getGeneralSecuritySettings('captcha_for_register')))
                <div class="mt-28 ">
                    @include('design_1.web.includes.captcha_input')
                </div>
            @endif

            <div class="mr-24">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="term" value="1" id="termCheckbox" class="custom-control-input" {{ (old('term') == '1') ? 'checked' : '' }}>
                    <label class="custom-control__label cursor-pointer" for="termCheckbox">
                        {{ trans('auth.i_agree_with') }}
                        <a href="pages/terms" target="_blank" class="font-weight-bold text-dark ml-4">{{ trans('auth.terms_and_rules') }}</a>
                    </label>
                </div>

                @error('term')
                <div class="invalid-feedback d-block mt-8">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="pl-16">
            <button type="button" class="js-submit-form-btn btn btn-primary btn-lg btn-block mt-24">{{ trans('auth.signup') }}</button>

            <div class="d-flex-center flex-column text-center mt-24">
                <span class="text-gray-500">{{ trans('auth.already_have_an_account') }}</span>
                <a href="/login" class="font-weight-bold text-dark mt-8">{{ trans('auth.login') }}</a>
            </div>
        </div>
    </form>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>

    <script src="{{ getDesign1ScriptPath("forms") }}"></script>
@endpush
