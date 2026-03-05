@extends('design_1.web.forms.layout')

@section('formContent')
    <div class="card-with-mask position-relative">
        <div class="mask-8-white rounded-32 z-index-1"></div>
        <div class="position-relative d-flex-center flex-column text-center mt-28 bg-white rounded-32 p-32 z-index-2">
            <div class="">
                <img src="/assets/design_1/img/forms/already_submitted.svg" alt="{{ trans('update.already_submitted') }}" class="img-fluid" height="300px">
            </div>

            <h3 class="font-16 mt-16">{{ trans('update.already_submitted') }}</h3>

            <div class="mt-4 font-14 text-gray-500">{{ trans("update.you_have_submitted_this_form_already_and_you_can_not_fill_it_in_again...") }}</div>

            <a href="/" class="btn btn-primary mt-16">{{ trans('update.back_to_home') }}</a>
        </div>
    </div>
@endsection
