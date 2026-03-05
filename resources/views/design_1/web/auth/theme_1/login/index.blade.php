@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    <form method="Post" action="/login" class="">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="pl-16 pt-16">
            <div class="font-16 font-weight-bold">{{ trans('update.welcome_back') }} 👋</div>
            <h1 class="font-24 mt-4 mb-32">{{ trans('update.login_to_your_account') }}</h1>

            {{-- Source --}}
            @include('design_1.web.auth.theme_1.includes.login_methods')

            <div class="position-relative form-group mt-28 mb-0">
                <label class="form-group-label" for="password">{{ trans('auth.password') }}:</label>
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

            @if(!empty(getGeneralSecuritySettings('captcha_for_login')))
                <div class="mt-28 ">
                    @include('design_1.web.includes.captcha_input')
                </div>
            @endif

            <div class="text-right mt-12">
                <a href="/forget-password" target="_blank" class="font-14 text-dark">{{ trans('auth.forget_your_password') }}</a>
            </div>

            <button type="button" class="js-submit-form-btn btn btn-primary btn-lg btn-block mt-12">{{ trans('auth.login') }}</button>
        </div>
    </form>

    @if(session()->has('login_failed_active_session'))
        <div class="pl-16">
            <div class="d-flex align-items-center p-16 rounded-12 border-danger bg-danger-20 mt-16">
                <x-iconsax-bul-info-circle class="icons text-danger" width="32px" height="32px"/>
                <div class="ml-8">
                    <div class="font-14 font-weight-bold text-danger">{{ session()->get('login_failed_active_session')['title'] }}</div>
                    <div class="mt-4 font-12 text-danger">{{ session()->get('login_failed_active_session')['msg'] }}</div>
                </div>
            </div>
        </div>
    @endif

    <div class="d-flex-center flex-column text-center mt-24">
        <div class="font-12 text-gray-500">{{ trans('update.or_continue_with') }}</div>

        <div class="d-flex-center gap-20">
            @if(!empty(getFeaturesSettings('show_google_login_button')))
                <a href="/google" target="_blank" class="d-flex-center size-48 bg-gray-100 border-gray-200 rounded-circle mt-16">
                    <img src="/assets/default/img/auth/google.svg" class="img-fluid" alt="google svg" width="24px" height="24px"/>
                </a>
            @endif

            @if(!empty(getFeaturesSettings('show_facebook_login_button')))
                <a href="{{url('/facebook/redirect')}}" target="_blank" class="d-flex-center size-48 bg-gray-100 border-gray-200 rounded-circle mt-16">
                    <img src="/assets/default/img/auth/facebook.svg" class="img-fluid" alt="facebook svg" width="24px" height="24px"/>
                </a>
            @endif
        </div>

        <div class="font-14 text-gray-500 mt-32">{{ trans('auth.dont_have_account') }}</div>

        <a href="/register" class="font-14 font-weight-bold mt-8 text-dark">{{ trans('auth.signup') }}</a>
    </div>
@endsection
