<div class="">

    <div class="d-inline-flex-center py-4 px-8 bg-gray-100 rounded-32 text-gray-500">{{ trans('update.step') }} 2/4</div>
    <h3 class="mt-8 font-24 font-weight-bold">{{ trans('update.meeting_type') }} 📹</h3>

    <div class="d-flex align-items-center w-100 mt-4">
        <div class="instructor-finder-wizard__progress rounded-4 bg-gray-100 flex-1 mr-8">
            <div class="progressbar rounded-4 bg-success" style="width: 50%"></div>
        </div>

        <div class="d-flex-center size-32 bg-gray-100 rounded-circle">
            <x-iconsax-lin-teacher class="icons text-gray-500" width="16px" height="16px"/>
        </div>
    </div>

    <div class="mt-32 text-gray-500">{{ trans('update.which_meeting_type_do_you_prefer?') }}</div>


    <div class="form-group mt-28">
        <label class="font-12 font-weight-bold">{{ trans('update.meeting_type') }}</label>

        @php
            $meetingTypes = [
                'all',
                'in_person',
                'online',
            ];
        @endphp

        <div class="d-flex align-items-center gap-5 p-4 border-gray-300 rounded-12 mt-8">
            @foreach($meetingTypes as $meetingType)
                <div class="custom-input-button custom-input-button-none-border-and-active-bg  position-relative flex-1">
                    <input type="radio" class="" name="meeting_type" id="meeting_type_{{ $meetingType }}" value="{{ $meetingType }}" {{ $loop->first ? 'checked' : '' }}>
                    <label for="meeting_type_{{ $meetingType }}" class="position-relative d-flex-center flex-column p-12 rounded-8 text-center text-gray-500">
                        {{ trans("update.{$meetingType}") }}
                    </label>
                </div>
            @endforeach
        </div>

        @error('meeting_type')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div id="regionCard" class="js-instructor-location">
        <h5 class="font-12 font-weight-bold my-24">{{ trans('update.instructor_location') }}</h5>

        <div class="d-flex align-items-center flex-wrap gap-16">
            <div class="flex-1">
                <div class="form-group ">
                    <label class="form-group-label">{{ trans('update.country') }}</label>

                    <select name="country_id" class="js-ajax-country_id js-country-selection form-control select2" data-regions-parent="js-instructor-location" data-map-zoom="5">
                        <option value="">{{ trans('update.choose_a_country') }}</option>

                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" data-center="{{ implode(',', $country->geo_center) }}">{{ $country->title }}</option>
                        @endforeach
                    </select>

                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="flex-1">
                <div class="form-group ">
                    <label class="form-group-label">{{ trans('update.state') }}</label>

                    <select
                        name="province_id"
                        class="js-ajax-province_id js-state-selection form-control select2"
                        data-regions-parent="js-instructor-location"
                        data-map-zoom="8"
                        disabled
                    >
                        <option value="">{{ trans('update.choose_a_state') }}</option>

                    </select>

                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center flex-wrap gap-16">
            <div class="flex-1">
                <div class="form-group ">
                    <label class="form-group-label">{{ trans('update.city') }}</label>

                    <select name="city_id"
                            class="js-ajax-city_id js-city-selection form-control select2"
                            data-regions-parent="js-instructor-location"
                            data-map-zoom="12"
                            disabled
                    >
                        <option value="">{{ trans('update.choose_a_city') }}</option>

                    </select>

                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="flex-1">
                <div class="form-group ">
                    <label class="form-group-label">{{ trans('update.district') }}</label>

                    <select name="district_id"
                            class="js-ajax-district_id js-district-selection form-control select2"
                            data-regions-parent="js-instructor-location"
                            data-map-zoom="15"
                            disabled
                    >
                        <option value="">{{ trans('update.all_districts') }}</option>

                    </select>

                    <div class="invalid-feedback"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group mt-20">
        <label class="font-12 font-weight-bold">{{ trans('update.population') }}</label>

        @php
            $populations = [
                'all',
                'single',
                'group',
            ];
        @endphp

        <div class="d-flex align-items-center gap-5 p-4 border-gray-300 rounded-12 mt-8">
            @foreach($populations as $population)
                <div class="custom-input-button custom-input-button-none-border-and-active-bg  position-relative flex-1">
                    <input type="radio" class="" name="population" id="population_{{ $population }}" value="{{ $population }}" {{ $loop->first ? 'checked' : '' }}>
                    <label for="population_{{ $population }}" class="position-relative d-flex-center flex-column p-12 rounded-8 text-center text-gray-500">
                        {{ trans("update.{$population}") }}
                    </label>
                </div>
            @endforeach
        </div>

    </div>

</div>
