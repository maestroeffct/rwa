@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("instructor_finder") }}">
@endpush

@section('content')
    <div class="container mt-104 pb-140">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <div class="d-flex-center flex-column text-center">
                    <h1 class="font-32 font-weight-bold">{{ trans('update.instructor_finder') }}</h1>
                    <p class="mt-8 font-16 text-gray-500">{{ trans('update.find_instructors_with_different_parameters_in_the_easiest_way') }}</p>
                </div>

                <form id="instructorFinderWizardForm" action="/instructor-finder/wizard?{{ http_build_query(request()->all()) }}" method="get">
                    @if(!empty(request()->all()) and count(request()->all()))
                        @foreach(request()->all() as $param => $value)
                            @if($param !== 'step')
                                <input type="hidden" name="{{ $param }}" value="{{ $value }}">
                            @endif
                        @endforeach
                    @endif

                    <input type="hidden" name="step" value="{{ $step + 1 }}">

                    <div class="instructor-finder-wizard position-relative bg-white rounded-32 p-16 mt-56">
                        <div class="instructor-finder-wizard__mask bg-gray-200"></div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="p-16">
                                    @include('design_1.web.instructor_finder.wizard.step_'.$step)

                                    <div class="mt-16 d-flex align-items-center gap-12">
                                        @if(($step != 1))
                                            <button type="button" class="js-previous-btn btn bg-gray-300 btn-lg text-gray-500">{{ trans('update.prev') }}</button>
                                        @endif

                                        <button type="button" class="js-next-btn btn btn-primary btn-lg ml-10">{{ ($step == 4) ? trans('home.find') : trans('webinars.next') }}</button>
                                    </div>

                                </div>
                            </div>

                            <div class="col-12 col-md-6 mt-24 mt-md-0">
                                @php
                                    $mainImage = null;
                                    $overlayImage = null;

                                    if (!empty($instructorFinderSettings) and !empty($instructorFinderSettings["main_image_step_{$step}"])) {
                                        $mainImage = $instructorFinderSettings["main_image_step_{$step}"];
                                    }

                                    if (!empty($instructorFinderSettings) and !empty($instructorFinderSettings["overlay_image_step_{$step}"])) {
                                        $overlayImage = $instructorFinderSettings["overlay_image_step_{$step}"];
                                    }

                                @endphp

                                <div class="instructor-finder-wizard__images-card bg-gray-100 rounded-16">
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
    <script>
        var leafletApiPath = '{{ getLeafletApiPath() }}';
        var selectRegionDefaultVal = '';
        var selectStateLang = '{{ trans('update.choose_a_state') }}';
        var selectCityLang = '{{ trans('update.choose_a_city') }}';
        var selectDistrictLang = '{{ trans('update.all_districts') }}';
    </script>

    <script src="/assets/vendors/leaflet/leaflet.min.js"></script>
    <script src="{{ getDesign1ScriptPath("leaflet_map") }}"></script>
    <script src="{{ getDesign1ScriptPath("get_regions") }}"></script>
    <script src="{{ getDesign1ScriptPath("range_slider_helpers") }}"></script>

    <script src="{{ getDesign1ScriptPath("instructor_finder_wizard") }}"></script>
@endpush
