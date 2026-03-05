@php
    $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
    $showOtherRegisterMethod = getFeaturesSettings('show_other_register_method') ?? false;
@endphp

@if($showOtherRegisterMethod)
    <div class="d-flex align-items-center gap-4 p-4 rounded-12 border-gray-300 mb-28">
        <div class="auth-register-method-item flex-1">
            <input type="radio" name="type" value="email" id="emailType" class="" {{ (($registerMethod == 'email' and empty(old('type'))) or old('type') == "email") ? 'checked' : '' }}>
            <label class="d-flex-center cursor-pointer" for="emailType">{{ trans('public.email') }}</label>
        </div>

        <div class="auth-register-method-item flex-1">
            <input type="radio" name="type" value="mobile" id="mobileType" class="" {{ (($registerMethod == 'mobile' and empty(old('type'))) or old('type') == "mobile") ? 'checked' : '' }}>
            <label class="d-flex-center cursor-pointer" for="mobileType">{{ trans('public.mobile') }}</label>
        </div>
    </div>

    <div class="js-email-fields form-group {{ (($registerMethod == 'email' and empty(old('type'))) or old('type') == "email") ? '' : 'd-none' }}">
        <label class="form-group-label" for="email">{{ trans('public.email') }}:</label>
        <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" aria-describedby="emailHelp">
        @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="js-mobile-fields {{ (($registerMethod == 'mobile' and empty(old('type'))) or old('type') == "mobile") ? '' : 'd-none' }}">
        @include('design_1.web.auth.theme_1.includes.mobile_field')
    </div>
@else
    @if($registerMethod == 'mobile')
        <input type="hidden" name="type" value="mobile">
        <div class="">
            @include('design_1.web.auth.theme_1.includes.mobile_field')
        </div>
    @else
        <input type="hidden" name="type" value="email">
        @include('design_1.web.auth.theme_1.includes.email_field')
    @endif
@endif
