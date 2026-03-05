@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("forms") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("become_instructor") }}">
@endpush

@section('content')
    <div class="container mt-104 pb-140">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <div class="d-flex-center flex-column text-center">
                    <h1 class="font-32 font-weight-bold">{{ trans('update.become_instructor_organization') }}</h1>
                    <p class="mt-8 font-16 text-gray-500">{{ trans('update.become_instructor_organization_page_top_hint') }}</p>
                </div>

                <form id="becomeInstructorForm" action="/become-instructor/store" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="become-instructor-wizard position-relative bg-white rounded-32 p-16 mt-56">
                        <div class="become-instructor-wizard__mask bg-gray-200"></div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="become-instructor-wizard__form-card p-16" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif >
                                    @include('design_1.web.become_instructor.wizard.form')

                                    <button type="submit" class="btn btn-primary btn-block btn-xlg mt-16">{{ (!empty(getRegistrationPackagesGeneralSettings('show_packages_during_registration')) and getRegistrationPackagesGeneralSettings('show_packages_during_registration')) ? trans('webinars.next') : trans('site.send_request') }}</button>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mt-24 mt-md-0">
                                @php
                                    $becomeInstructorSettingsData = [];
                                    $theme = getActiveTheme();

                                    if (!empty($theme)) {
                                        $themeContents = [];
                                        if (!empty($theme->contents)) {
                                            $themeContents = json_decode($theme->contents, true);
                                        }

                                        $becomeInstructorSettingsData = !empty($themeContents['images']) ? $themeContents['images'] : [];
                                    }

                                    $mainImage = !empty($becomeInstructorSettingsData['become_instructor']) ? $becomeInstructorSettingsData['become_instructor'] : '';
                                    $overlayImage = !empty($becomeInstructorSettingsData['become_instructor_overlay_image']) ? $becomeInstructorSettingsData['become_instructor_overlay_image'] : '';

                                    // Fallback to original settings if they exist
                                    if (empty($mainImage) && !empty($becomeInstructorSettings) && !empty($becomeInstructorSettings["main_image"])) {
                                        $mainImage = $becomeInstructorSettings["main_image"];
                                    }

                                    if (empty($overlayImage) && !empty($becomeInstructorSettings) && !empty($becomeInstructorSettings["overlay_image"])) {
                                        $overlayImage = $becomeInstructorSettings["overlay_image"];
                                    }
                                @endphp

                                <div class="become-instructor-wizard__images-card bg-gray-100 rounded-16">
                                    @if(!empty($mainImage))
                                        <img src="{{ $mainImage }}" alt="{{ trans('update.main_image') }}" class="img-cover rounded-16">
                                    @endif

                                    @if(!empty($overlayImage))
                                        <div class="wizard-overlay-image d-flex-center">
                                            <img src="{{ $overlayImage }}" alt="{{ trans('update.overlay_image') }}" class="img-fluid">
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>

    <script src="{{ getDesign1ScriptPath("forms") }}"></script>
    <script src="{{ getDesign1ScriptPath("become_instructor_wizard") }}"></script>
@endpush
