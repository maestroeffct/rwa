@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.contact_us') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('admin/main.contact_us') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ getAdminPanelUrl() }}/additional_page/contact_us" method="post">
                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-12 col-md-6">

                                        <h2 class="section-title after-line">{{ trans('public.basic_information') }}</h2>

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('admin/main.contact_us_phones') }}</label>
                                            <input type="text" name="value[phones]" value="{{ (!empty($value) and !empty($value['phones'])) ? $value['phones'] : '' }}" class="form-control" placeholder="{{ trans('admin/main.contact_us_phones_placeholder') }}"/>
                                            <div class="mt-1 text-gray-500 font-12">{{ trans('update.separate_with_commas') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('admin/main.contact_us_emails') }}</label>
                                            <input type="text" name="value[emails]" value="{{ (!empty($value) and !empty($value['emails'])) ? $value['emails'] : '' }}" class="form-control" placeholder="{{ trans('admin/main.contact_us_emails_placeholder') }}"/>
                                            <div class="mt-1 text-gray-500 font-12">{{ trans('update.separate_with_commas') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('admin/main.contact_us_address') }}</label>
                                            <textarea name="value[address]" rows="5" class="form-control" placeholder="{{ trans('admin/main.contact_us_address') }}">{{ (!empty($value) and !empty($value['address'])) ? $value['address'] : '' }}</textarea>
                                        </div>



                                        <h2 class="section-title after-line">{{ trans('admin/main.map_position') }}</h2>


                                        <div class="form-group">
                                            @php
                                                $latitude = (!empty($value) and !empty($value['latitude'])) ? $value['latitude'] : getDefaultMapsLocation()['lat'];
                                                $longitude = (!empty($value) and !empty($value['longitude'])) ? $value['longitude'] : getDefaultMapsLocation()['lon'];
                                                $zoom = (!empty($value) and !empty($value['map_zoom'])) ? $value['map_zoom'] : 12;
                                            @endphp

                                            <input type="hidden" id="LocationLatitude" name="value[latitude]" value="{{ $latitude }}">
                                            <input type="hidden" id="LocationLongitude" name="value[longitude]" value="{{ $longitude }}">

                                            <div id="mapContainer" class="">
                                                <label class="input-label">{{ trans('update.select_location') }}</label>
                                                <span class="d-block">{{ trans('update.select_location_hint') }}</span>

                                                <div class="region-map mt-10" id="mapBox"
                                                     data-zoom="{{ $zoom }}"
                                                >
                                                    <img src="/assets/default/img/location.png" class="marker">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="input-label">{{ trans('admin/main.map_zoom') }}</label>
                                            <input type="text" name="value[map_zoom]" value="{{ $zoom }}" class="form-control" placeholder="{{ trans('admin/main.map_zoom') }}"/>
                                        </div>


                                        {{-- additional Information --}}
                                        <h2 class="section-title after-line">{{ trans('public.additional_information') }}</h2>

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('admin/main.title') }}</label>
                                            <input type="text" name="value[additional_information_title]" value="{{ (!empty($value) and !empty($value['additional_information_title'])) ? $value['additional_information_title'] : '' }}" class="form-control" placeholder=""/>
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('update.subtitle') }}</label>
                                            <textarea name="value[additional_information_subtitle]" rows="4" class="form-control">{{ (!empty($value) and !empty($value['additional_information_subtitle'])) ? $value['additional_information_subtitle'] : '' }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('admin/main.image') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text admin-file-manager" data-input="additional_information_image" data-preview="holder">
                                                        <i class="fa fa-chevron-up"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="value[additional_information_image]" id="additional_information_image" value="{{ (!empty($value) and !empty($value['additional_information_image'])) ? $value['additional_information_image'] : '' }}" class="form-control"/>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/leaflet/leaflet.min.js"></script>

    <script>
        var removeLang = '{{ trans('admin/main.remove') }}';
        var leafletApiPath = '{{ getLeafletApiPath() }}';
    </script>

    <script src="/assets/admin/js/parts/contact_us.min.js"></script>
@endpush
