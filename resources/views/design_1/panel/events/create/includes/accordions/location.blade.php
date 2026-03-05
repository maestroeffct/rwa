@php
    $specificLocation = !empty($event->specificLocation) ? $event->specificLocation : null;
@endphp

<div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed mt-32">
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-48 bg-primary-20 rounded-12">
            <x-iconsax-bul-location class="icons text-primary" width="24px" height="24px"/>
        </div>

        <div class="ml-8">
            <h5 class="font-14 font-weight-bold">{{ trans('update.location') }}</h5>
            <p class="mt-4 font-12 text-gray-500">{{ trans('update.set_the_location_where_the_event_will_take_place') }}</p>
        </div>
    </div>
</div>

<div class="js-event-location p-16 mt-16 rounded-16 border-gray-200">
    <div class="row">
        <div class="col-12 col-lg-6">
            <h4 class="font-14">{{ trans('update.region') }}</h4>

            <div class="form-group mt-20 ">
                <label class="form-group-label">{{ trans('update.country') }}</label>

                <select name="specificLocation[country_id]" class="js-ajax-country_id js-country-selection form-control select2" data-regions-parent="js-event-location" data-map-zoom="5">
                    <option value="">{{ trans('update.choose_a_country') }}</option>

                    @foreach(\App\Models\Region::getRegionsByTypeAndColumn(\App\Models\Region::$country) as $country)
                        <option value="{{ $country->id }}" {{ (!empty($specificLocation) and $specificLocation->country_id == $country->id) ? 'selected' : '' }} data-center="{{ implode(',', $country->geo_center) }}">{{ $country->title }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.state') }}</label>

                <select
                    name="specificLocation[province_id]"
                    {{ (empty($specificLocation) or empty($specificLocation->country_id)) ? 'disabled' : '' }}
                    class="js-ajax-province_id js-state-selection form-control select2"
                    data-regions-parent="js-event-location"
                    data-map-zoom="8"
                >
                    <option value="">{{ trans('update.choose_a_state') }}</option>

                    @if(!empty($specificLocation) and !empty($specificLocation->country_id))
                        @foreach(\App\Models\Region::getRegionsByTypeAndColumn(\App\Models\Region::$province, 'country_id', $specificLocation->country_id) as $province)
                            <option value="{{ $province->id }}" {{ (!empty($specificLocation) and $specificLocation->province_id == $province->id) ? 'selected' : '' }} data-center="{{ implode(',', $province->geo_center) }}">{{ $province->title }}</option>
                        @endforeach
                    @endif
                </select>

                <div class="invalid-feedback"></div>
            </div>


            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.city') }}</label>

                <select name="specificLocation[city_id]"
                        class="js-ajax-city_id js-city-selection form-control select2"
                        {{ (empty($specificLocation) or empty($specificLocation->province_id)) ? 'disabled' : '' }}
                        data-regions-parent="js-event-location"
                        data-map-zoom="12"
                >
                    <option value="">{{ trans('update.choose_a_city') }}</option>

                    @if(!empty($specificLocation) and !empty($specificLocation->province_id))
                        @foreach(\App\Models\Region::getRegionsByTypeAndColumn(\App\Models\Region::$city, 'province_id', $specificLocation->province_id) as $city)
                            <option value="{{ $city->id }}" {{ (!empty($specificLocation) and $specificLocation->city_id == $city->id) ? 'selected' : '' }} data-center="{{ implode(',', $city->geo_center) }}">{{ $city->title }}</option>
                        @endforeach
                    @endif
                </select>

                <div class="invalid-feedback"></div>
            </div>


            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.district') }}</label>

                <select name="specificLocation[district_id]"
                        class="js-ajax-district_id js-district-selection form-control select2"
                        {{ (empty($specificLocation) or empty($specificLocation->city_id)) ? 'disabled' : '' }}
                        data-regions-parent="js-event-location"
                        data-map-zoom="15"
                >
                    <option value="">{{ trans('update.all_districts') }}</option>

                    @if(!empty($specificLocation) and !empty($specificLocation->city_id))
                        @foreach(\App\Models\Region::getRegionsByTypeAndColumn(\App\Models\Region::$district, 'city_id', $specificLocation->city_id) as $district)
                            <option value="{{ $district->id }}" {{ (!empty($specificLocation) and $specificLocation->district_id == $district->id) ? 'selected' : '' }} data-center="{{ implode(',', $district->geo_center) }}">{{ $district->title }}</option>
                        @endforeach
                    @endif
                </select>

                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group mb-30">
                <label class="form-group-label">{{ trans('update.address') }}:</label>
                <textarea type="text" name="specificLocation[address]" rows="5" class="form-control">{{ !empty($specificLocation->address) ? $specificLocation->address : '' }}</textarea>
            </div>
        </div>

        @php
            $latitude = getDefaultMapsLocation()['lat'];
            $longitude = getDefaultMapsLocation()['lon'];

            if(!empty($specificLocation->geo_center)) {
                $latitude = $specificLocation->geo_center[0];
                $longitude = $specificLocation->geo_center[1];
            }
        @endphp

        <div class="col-12 col-lg-6 mt-20 mt-lg-0">
            <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.location_on_map') }}</h3>

            <input type="hidden" id="LocationLatitude" name="specificLocation[latitude]" value="{{ $latitude }}">
            <input type="hidden" id="LocationLongitude" name="specificLocation[longitude]" value="{{ $longitude }}">

            <div class="region-map with-default-initial-drag w-100 rounded-8 mt-16 bg-gray-100" id="mapBox"
                 data-latitude="{{ $latitude }}"
                 data-longitude="{{ $longitude }}"
                 data-zoom="{{ !empty($specificLocation->geo_center) ? '17' : '5' }}"
                 data-dragging="true"
                 data-zoomControl="true"
                 data-scrollWheelZoom="true"
            >
                <div class="map-dont-click-alert d-none align-items-center justify-content-center">
                    <div class="d-flex-center flex-column">
                        <img src="/assets/design_1/img/spread-icon.png" height="96px">
                        <span class="mt-12 font-20 text-white">{{ trans('update.instead_of_clicking_please_move_the_map') }}</span>
                    </div>
                </div>

                <img src="/assets/default/img/location.png" class="marker" width="40" height="40">
            </div>
        </div>
    </div>
</div>
