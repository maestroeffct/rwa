@extends('design_1.web.forms.layout')

@section('formContent')

    @include('design_1.web.forms.pages.alerts')

    <div class="card-with-mask position-relative mt-28">
        <div class="mask-8-white rounded-32 z-index-1"></div>

        <div class="position-relative bg-white rounded-32 p-16 z-index-2">
            <div class="d-flex-center flex-column text-center">
                <div class="">
                    <img src="{{ $form->image }}" alt="{{ $form->heading_title }}" class="rounded-16 img-fluid">
                </div>

                <h3 class="font-16 mt-16">{{ $form->heading_title }}</h3>
            </div>

            <div class="mt-12 font-14 text-gray-500">{!! $form->description !!}</div>

            {{-- Inputs --}}
            <form action="/forms/{{ $form->url }}/store" method="post" class="mt-32" enctype="multipart/form-data">
                {{ csrf_field() }}

                @include('design_1.web.forms.components.handle_field', ['fields' => $form->fields])

                <div class="d-flex align-items-center justify-content-end mt-32">
                    <button type="button" class="js-clear-form btn btn-danger mr-10">{{ trans('update.clear_form') }}</button>

                    <button type="submit" class="btn btn-primary">{{ trans('update.submit_form') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
