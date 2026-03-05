@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    <form method="Post" action="/reset-password" class="">
        {{ csrf_field() }}

        <input hidden name="rs_token" placeholder="token" value="{{ $token }}">

        <div class="pl-16 pt-16">
            <div class="font-16 font-weight-bold">{{ trans('auth.reset_your_password') }} 🤔</div>
            <h1 class="font-24 mt-4 mb-32">{{ trans('update.recover_your_password') }}</h1>

            <div class="bg-gray-100 p-12 rounded-16 border-gray-300 mt-24 text-gray-500 mb-28">
                {{ trans('update.reset_your_password_alert_hint') }}
            </div>

            @include('design_1.web.auth.theme_1.includes.email_field', ['defaultEmailValue' => request()->get('email')])

            <div class="position-relative form-group mt-28 mb-0">
                <label class="form-group-label" for="password">{{ trans('auth.new_password') }}:</label>
                <input type="password" name="password" class="form-control @error('password')  is-invalid @enderror" id="password" aria-describedby="passwordHelp">

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

            <div class="position-relative form-group mt-28 mb-0">
                <label class="form-group-label" for="password_confirmation">{{ trans('auth.retype_new_password') }}:</label>
                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation')  is-invalid @enderror" id="password_confirmation" aria-describedby="password_confirmationHelp">

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

            @if(!empty(getGeneralSecuritySettings('captcha_for_forgot_pass')))
                <div class="mt-28 ">
                    @include('design_1.web.includes.captcha_input')
                </div>
            @endif

            <button type="button" class="js-submit-form-btn btn btn-primary btn-lg btn-block mt-24">{{ trans('auth.reset_password') }}</button>

            <div class="d-flex-center flex-column text-center mt-24">
                <span class="text-gray-500">{{ trans('update.other_options') }}</span>

                <div class="d-flex align-items-center mt-12">
                    <a href="/login" class="font-weight-bold text-dark  mr-16 pr-16  border-right-gray-300">{{ trans('auth.login') }}</a>

                    <a href="/register" class="font-weight-bold text-dark">{{ trans('auth.signup') }}</a>
                </div>
            </div>
        </div>
    </form>

@endsection
