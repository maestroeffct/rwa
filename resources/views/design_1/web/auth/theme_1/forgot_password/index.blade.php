@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    <form method="Post" action="/forget-password" class="">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="pl-16 pt-16">
            <div class="font-16 font-weight-bold">{{ trans('auth.forget_your_password') }} 🤔</div>
            <h1 class="font-24 mt-4 mb-32">{{ trans('update.recover_your_password') }}</h1>

            <div class="bg-gray-100 p-12 rounded-16 border-gray-300 mt-24 text-gray-500 mb-28">
                {{ trans('update.recover_your_password_alert_hint') }}
            </div>

            @include('design_1.web.auth.theme_1.includes.login_methods')


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

@push('scripts_bottom')
    <script>
        var waitLang = "{{ trans('update.wait!') }}";
        var pleaseWaitUntilTimeOverLang = "{{ trans('update.please_wait_until_time_over') }}";
    </script>

    <script src="/assets/default/vendors/jquery.simple.timer/jquery.simple.timer.js"></script>
@endpush
