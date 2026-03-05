@extends('design_1.web.forms.layout')

@section('formContent')
    <div class="card-with-mask position-relative">
        <div class="mask-8-white rounded-32 z-index-1"></div>
        <div class="position-relative mt-28 bg-white rounded-32 p-56 pb-100 z-index-2">
            <div class="d-flex-center flex-column text-center">
                <div class="">
                    <img src="{{ $form->tank_you_message_image }}" alt="{{ $form->tank_you_message_title }}" class="img-fluid" height="300px">
                </div>

                <h3 class="font-16 mt-16">{{ $form->tank_you_message_title }}</h3>
            </div>

            <div class="mt-12 font-14 text-gray-500">{{ $form->tank_you_message_description }}</div>

            <div class="d-flex-center mt-20">
                <a href="/" class="btn btn-primary">{{ trans('update.back_to_home') }}</a>
            </div>
        </div>
    </div>
@endsection
