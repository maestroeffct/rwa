@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("contactus") }}">
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
@endpush


@section('content')
    <div class="container mt-96 pb-104">
        <div class="bg-white p-16 rounded-32">
            <div class="row">
                <div class="col-12 col-md-3">
                    @include('design_1.web.contactus.our_info')
                </div>

                <div class="col-12 col-md-5 mt-20 mt-md-0">
                    @include('design_1.web.contactus.form')
                </div>

                <div class="col-12 col-md-4 mt-20 mt-md-0">
                    @include('design_1.web.contactus.map')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        var leafletApiPath = '{{ getLeafletApiPath() }}';
    </script>

    <script src="/assets/vendors/leaflet/leaflet.min.js"></script>
    <script src="{{ getDesign1ScriptPath("leaflet_map") }}"></script>
@endpush
