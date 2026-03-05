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

                <h1 class="font-24 mt-16">{{ trans('auth.forget_password') }}</h1>
                <p class="font-12 text-gray-500 mt-8">{{ trans('update.we_will_send_a_link_to_reset_your_password') }}</p>

            </div>

            <form method="POST" action="{{ getAdminPanelUrl("/forget-password") }}" class="mt-28" novalidate="">
                {{ csrf_field() }}

                <div class="form-group">
                    <label class="form-group-label bg-white">{{ trans('public.email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control  @error('email')  is-invalid @enderror">

                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                @if(!empty(getGeneralSecuritySettings('captcha_for_admin_forgot_pass')))
                    <div class="mt-24">
                        @include('design_1.web.includes.captcha_input')
                    </div>
                @endif

                <button type="submit" class="btn btn-primary btn-xlg btn-block mt-16">{{ trans('auth.reset_password') }}</button>
            </form>

            <div class="d-flex-center flex-column mt-20">
                <a href="{{ getAdminPanelUrl("/login") }}" class="text-dark">{{ trans('auth.login') }}</a>
            </div>

        </div>
    </div>
@endsection
