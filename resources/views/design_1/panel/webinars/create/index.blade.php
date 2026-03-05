@extends('design_1.web.layouts.app', ['appFooter' => false])

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("create-course") }}">
@endpush

@section('content')
    <form method="post" action="/panel/courses/{{ !empty($webinar) ? $webinar->id .'/update' : 'store' }}" id="webinarForm" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="current_step" value="{{ !empty($currentStep) ? $currentStep : 1 }}">
        <input type="hidden" name="draft" value="no" id="forDraft"/>
        <input type="hidden" name="get_next" value="no" id="getNext"/>
        <input type="hidden" name="get_step" value="0" id="getStep"/>


        <div class="container mt-80 pb-100">
            {{-- Progress --}}
            @include('design_1.panel.webinars.create.includes.progress')

            {{-- Steps Inputs --}}
            @include("design_1.panel.webinars.create.steps.step_{$currentStep}")
        </div>


        {{-- Bottom Actions --}}
        @include('design_1.panel.webinars.create.includes.bottom_actions')

    </form>
@endsection

@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var zoomJwtTokenInvalid = '{{ trans('webinars.zoom_jwt_token_invalid') }}';
        var hasZoomApiToken = '{{ (!empty(getFeaturesSettings('zoom_client_id')) and !empty(getFeaturesSettings('zoom_client_secret'))) ? 'true' : 'false' }}';
        var editChapterLang = '{{ trans('public.edit_chapter') }}';
    </script>

    <script src="/assets/design_1/js/panel/create_webinar.min.js"></script>
    <script src="/assets/design_1/js/panel/webinar_content_locale.min.js"></script>
@endpush
