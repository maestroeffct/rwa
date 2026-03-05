<div class="js-instructor-location instructor-finder__filters-card position-relative bg-white p-16 rounded-24 mt-28">
    <h5 class="instructor-finder__filters-title font-14 font-weight-bold">{{ trans('update.location') }}</h5>

    <div class="form-group  mt-24">
        <label class="form-group-label">{{ trans('update.country') }}</label>

        <select name="country_id" class="js-ajax-country_id js-country-selection form-control select2" data-regions-parent="js-instructor-location" data-map-zoom="5">
            <option value="">{{ trans('update.choose_a_country') }}</option>

            @if(!empty($countries))
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ (request()->get('country_id') == $country->id) ? 'selected' : '' }}>{{ $country->title }}</option>
                @endforeach
            @endif
        </select>

        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group ">
        <label class="form-group-label">{{ trans('update.state') }}</label>

        <select
            name="province_id"
            class="js-ajax-province_id js-state-selection form-control select2"
            data-regions-parent="js-instructor-location"
            data-map-zoom="8"
            {{ empty($provinces) ? 'disabled' : '' }}
        >
            <option value="">{{ trans('update.choose_a_state') }}</option>

            @if(!empty($provinces))
                @foreach($provinces as $province)
                    <option value="{{ $province->id }}" {{ (request()->get('province_id') == $province->id) ? 'selected' : '' }}>{{ $province->title }}</option>
                @endforeach
            @endif

        </select>

        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group ">
        <label class="form-group-label">{{ trans('update.city') }}</label>

        <select name="city_id"
                class="js-ajax-city_id js-city-selection form-control select2"
                data-regions-parent="js-instructor-location"
                data-map-zoom="12"
                {{ empty($cities) ? 'disabled' : '' }}
        >
            <option value="">{{ trans('update.choose_a_city') }}</option>

            @if(!empty($cities))
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ (request()->get('city_id') == $city->id) ? 'selected' : '' }}>{{ $city->title }}</option>
                @endforeach
            @endif

        </select>

        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group ">
        <label class="form-group-label">{{ trans('update.district') }}</label>

        <select name="district_id"
                class="js-ajax-district_id js-district-selection form-control select2"
                data-regions-parent="js-instructor-location"
                data-map-zoom="15"
                {{ empty($districts) ? 'disabled' : '' }}
        >
            <option value="">{{ trans('update.all_districts') }}</option>

            @if(!empty($districts))
                @foreach($districts as $district)
                    <option value="{{ $district->id }}" {{ (request()->get('district_id') == $district->id) ? 'selected' : '' }}>{{ $district->title }}</option>
                @endforeach
            @endif
        </select>

        <div class="invalid-feedback"></div>
    </div>

</div>
