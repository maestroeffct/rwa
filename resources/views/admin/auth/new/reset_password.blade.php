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

                <h1 class="font-24 mt-16">{{ trans('auth.reset_password') }}</h1>
                <p class="font-12 text-gray-500 mt-8">{{ trans('update.please_enter_your_new_password') }}</p>

            </div>

            <form method="POST" action="{{ getAdminPanelUrl("/reset-password") }}" class="mt-28" novalidate="">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label class="form-group-label bg-white">{{ trans('public.email') }}</label>
                    <input type="email" value="{{ $email }}" disabled class="form-control bg-white">
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

                <div class="form-group position-relative">
                    <label class="form-group-label bg-white">{{ trans('auth.retype_password') }}</label>
                    <input name="password_confirmation" type="password" class="form-control bg-white  @error('password_confirmation')  is-invalid @enderror">

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

                <button type="submit" class="btn btn-primary btn-xlg btn-block mt-16">{{ trans('auth.reset_password') }}</button>
            </form>

        </div>
    </div>
@endsection
