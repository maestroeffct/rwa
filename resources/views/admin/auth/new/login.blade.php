@extends('admin.auth.new.layout')

@php
    $siteGeneralSettings = getGeneralSettings();
@endphp

@section('content')
    <div class="content-box position-relative w-100">
        <div class="content-box__mask"></div>

        <div class="position-relative z-index-2 bg-white py-32 px-16 rounded-24">
            <div class="d-flex-center flex-column text-center">
                <div class="content-box__logo">
                    <img src="{{ $siteGeneralSettings['logo'] ?? '' }}" alt="logo" class="">
                </div>

                <h1 class="font-24 mt-16">{{ trans('update.welcome_back_to_site!', ['site' => $siteGeneralSettings['site_name'] ?? '']) }}</h1>
                <p class="font-12 text-gray-500 mt-8">{{ trans('update.login_to_your_account_and_manage_everything') }}</p>

            </div>

            <form method="POST" action="{{ getAdminPanelUrl("/login") }}" class="mt-28" novalidate="">
                {{ csrf_field() }}

                <div class="form-group">
                    <label class="form-group-label bg-white">{{ trans('public.email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control bg-white  @error('email')  is-invalid @enderror">

                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group position-relative">
                    <label class="form-group-label bg-white">{{ trans('auth.password') }}</label>
                    <input name="password" type="password" class="form-control bg-white  @error('password')  is-invalid @enderror">

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

                @if(!empty(getGeneralSecuritySettings('captcha_for_admin_login')))
                    <div class="mt-28 ">
                        @include('design_1.web.includes.captcha_input')
                    </div>
                @endif


                <div class="custom-control custom-checkbox mt-20">
                    <input type="checkbox" name="remember" id="rememberSwitch" class="custom-control-input">
                    <label class="custom-control__label cursor-pointer" for="rememberSwitch">{{ trans('auth.remember_me') }}</label>
                </div>

                <button type="submit" class="btn btn-primary btn-xlg btn-block mt-16">{{ trans('auth.login') }}</button>
            </form>

            <div class="d-flex-center flex-column mt-20">
                <a href="{{ getAdminPanelUrl("/forget-password") }}" class="text-dark">{{ trans('auth.forget_your_password') }}</a>
            </div>

        </div>
    </div>
@endsection
