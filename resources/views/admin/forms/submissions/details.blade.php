@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.forms') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ getAdminPanelUrl("/forms/{$form->id}/submissions/{$submission->id}/update") }}" method="post" class="mt-30">
                                {{ csrf_field() }}


                                {{-- Items --}}
                                @include('admin.forms.submissions.form_field_items', [
                                    'formFields' => $form->fields,
                                    'submissionItems' => $submission->items,
                                ])


                                <div class="d-flex align-items-center justify-content-end mt-30">
                                    <button type="button" class="js-clear-form btn btn-outline-primary mr-2">{{ trans('update.clear_form') }}</button>

                                    <button type="submit" class="btn btn-primary">{{ trans('update.submit_form') }}</button>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/admin/js/parts/form_submissions_details.min.js"></script>
@endpush
