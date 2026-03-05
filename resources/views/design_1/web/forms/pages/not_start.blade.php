@extends('design_1.web.forms.layout')

@section('formContent')
    <div class="card-with-mask position-relative">
        <div class="mask-8-white rounded-32 z-index-1"></div>
        <div class="position-relative d-flex-center flex-column text-center mt-28 bg-white rounded-32 p-32 z-index-2">
            <div class="">
                <img src="/assets/design_1/img/forms/expired.svg" alt="{{ trans('update.access_denied') }}" class="img-fluid" height="300px">
            </div>

            <h3 class="font-16 mt-16">{{ trans('update.form_has_not_yet_started') }}</h3>

            @if(empty($form->end_date))
                <div class="mt-4 font-14 text-gray-500">{{ trans("update.you_can_fill_out_this_form_from_date",['date' => dateTimeFormat($form->start_date, 'j M Y')]) }}</div>
            @else
                <div class="mt-4 font-14 text-gray-500">{{ trans("update.you_can_fill_out_this_form_from_date_to_end_date",['date' => dateTimeFormat($form->start_date, 'j M Y'), 'end_date' => dateTimeFormat($form->end_date, 'j M Y')]) }}</div>
            @endif

            <a href="/" class="btn btn-primary mt-16">{{ trans('update.back_to_home') }}</a>
        </div>
    </div>
@endsection
