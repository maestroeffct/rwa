@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
    <div class="bg-white p-16 rounded-24 mb-56">
        <h1 class="font-16 font-weight-bold">{{ trans('update.profile_settings')  }}</h1>
        <p class="text-gray-500 mt-4">{{ trans('update.define_and_manage_your_profile_settings') }}</p>

        <div class="custom-tabs mt-16">
            @if(empty($new_user))
                <div class="custom-tabs-items-scrollable-mobile d-flex align-items-center gap-24 gap-lg-40 border-bottom-gray-200 border-top-gray-200">
                    @include('design_1.panel.settings.includes.progress')
                </div>
            @endif

            <div class="custom-tabs-body mt-16">

                <form method="post" id="userSettingForm" class="mt-30" action="{{ (!empty($new_user)) ? '/panel/manage/'. $user_type .'/new' : '/panel/setting' }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="step" value="{{ !empty($currentStep) ? $currentStep : 1 }}">

                    @if(!empty($organization_id))
                        <input type="hidden" name="organization_id" value="{{ $organization_id }}">
                        <input type="hidden" id="userId" name="user_id" value="{{ $user->id }}">
                    @endif

                    {{-- Step Fields --}}
                    @include("design_1.panel.settings.tabs.{$currentStep}")

                    <div class="panel-bottom-bar d-flex align-items-center justify-content-end bg-white px-32 py-16">
                        <button type="button" id="saveData" class="btn btn-primary">{{ trans('update.save_settings') }}</button>
                    </div>

                </form>

            </div>

        </div>


    </div>
@endsection

@push('scripts_bottom')
    <script>
        var newEducationLang = '{{ trans('site.new_education') }}';
        var submitRequestLang = '{{ trans('update.submit_request') }}';
        var editEducationLang = '{{ trans('site.edit_education') }}';
        var newExperienceLang = '{{ trans('site.new_experience') }}';
        var editExperienceLang = '{{ trans('site.edit_experience') }}';
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var saveErrorLang = '{{ trans('site.store_error_try_again') }}';
        var notAccessToLang = '{{ trans('public.not_access_to_this_content') }}';
        var newAttachmentLang = '{{ trans('update.new_attachment') }}';
        var editAttachmentLang = '{{ trans('update.edit_attachment') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>

    <script src="/assets/design_1/js/panel/user_setting.min.js"></script>
@endpush
