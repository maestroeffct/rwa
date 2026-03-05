@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    <form method="Post" action="/verification" class="">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="username" value="{{ $username }}">
        <input type="hidden" name="usernameValue" value="{{ $usernameValue }}">

        <div class="pl-16 pt-16">
            <div class="font-16 font-weight-bold">{{ trans('update.thank_you_for_joining') }} 😊</div>
            <h1 class="font-24 mt-4 mb-32">{{ trans('update.account_verification') }}</h1>

            <div class="bg-gray-100 p-12 rounded-16 border-gray-300 mt-24 text-gray-500">
                @if($username == "email")
                    {{ trans('update.account_verification_code_hint_for_email') }}
                @else
                    {{ trans('update.account_verification_code_hint_for_mobile') }}
                @endif
            </div>


            <div class="mt-16 font-12 text-gray-500">{{ trans('update.verification_code') }}</div>

            <div class="direction-ltr d-grid grid-columns-5 gap-16 mt-12">
                @foreach([1,2,3,4,5] as $num)
                    <input type="tel" name="code[{{ $num }}]" class="auth-verification-code-field" autocomplete="off">
                @endforeach
            </div>

            @error('code')
                <div class="invalid-feedback d-block mt-8">{{ $message }}</div>
            @enderror

            <button type="button" class="js-submit-verification-form-btn btn btn-primary btn-lg btn-block mt-24">{{ trans('update.verify') }}</button>
        </div>
    </form>

    @php
        $duration = getGeneralOptionsSettings("duration_of_resend_verification_code");
    @endphp

    <div class="d-flex-center flex-column text-center mt-24">
        @if(!empty($duration))
            <div class="js-resend-duration-card d-flex align-items-center gap-4 text-gray-500">
                <div class="">{{ trans('update.please_wait_2') }}</div>
                <div class="js-resend-timer resend-verification-code-timer d-flex align-items-center" data-minutes-left="{{ $duration }}"></div>
                <div class="">{{ trans('update.to_resend_the_code') }}</div>
            </div>
        @endif

        <div class="js-dont-received-card text-gray-500 {{ (!empty($duration)) ? 'd-none' : '' }}">{{ trans('update.haven’t_received_the_code') }}</div>

        <button type="button" class="js-resend-code-btn btn-transparent font-weight-bold mt-8 {{ !empty($duration) ? 'text-gray-500' : "text-dark" }}">{{ trans('auth.resend_code') }}</button>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        var waitLang = "{{ trans('update.wait!') }}";
        var pleaseWaitUntilTimeOverLang = "{{ trans('update.please_wait_until_time_over') }}";
    </script>

    <script src="/assets/default/vendors/jquery.simple.timer/jquery.simple.timer.js"></script>
@endpush
