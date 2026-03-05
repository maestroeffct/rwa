@extends('design_1.web.forms.layout')

@section('formContent')

    @include('design_1.web.forms.pages.alerts')

    <div class="card-with-mask position-relative mt-28">
        <div class="mask-8-white rounded-32 z-index-1"></div>
        <div class="position-relative bg-white rounded-32 p-16 z-index-2">
            <div class="d-flex-center flex-column text-center">
                <div class="">
                    <img src="{{ $form->welcome_message_image }}" alt="{{ $form->welcome_message_title }}" class="img-fluid" height="300px">
                </div>

                <h3 class="font-16 mt-16">{{ $form->welcome_message_title }}</h3>
            </div>

            <div class="mt-12 font-14 text-gray-500">{{ $form->welcome_message_description }}</div>

            <div class="d-flex-center mt-20">
                <a href="/forms/{{ $form->url }}?fields=1" class="btn btn-primary ">{{ trans('update.fill_out_the_form') }}</a>
            </div>
        </div>
    </div>

@endsection
