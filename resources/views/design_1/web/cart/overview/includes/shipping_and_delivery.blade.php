<div class="px-16">
    <div class="border-dashed border-gray-300 rounded-16 p-12 mt-16">

        @if(!empty($deliveryEstimateTime))
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                    <x-iconsax-bul-truck-time class="icons text-primary" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <h5 class="font-14">{{ trans('update.shipping_and_delivery') }}</h5>
                    <p class="mt-4 font-12 text-gray-500">{{ trans('update.cart_order_estimated_delivery_time_hint', ['days' => $deliveryEstimateTime]) }}</p>
                </div>
            </div>
        @endif

        @if(!empty(getStoreSettings('show_address_selection_in_cart')))
            <div id="regionCard" class="js-instructor-location mt-28">

                <div class="d-flex align-items-center flex-wrap gap-16">
                    <div class="flex-1">
                        <div class="form-group ">
                            <label class="form-group-label">{{ trans('update.country') }}</label>

                            <select name="country_id" class="js-ajax-country_id js-country-selection form-control select2" data-regions-parent="js-instructor-location" data-map-zoom="5">
                                <option value="">{{ trans('update.choose_a_country') }}</option>

                                @if(!empty($countries))
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ (!empty($user) and $user->country_id == $country->id) ? 'selected' : '' }}>{{ $country->title }}</option>
                                    @endforeach
                                @endif
                            </select>

                            <div class="invalid-feedback d-block">@error('country_id') {{ $message }} @enderror</div>
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
                                {{ (!empty($user) and $user->country_id) ? '' : 'disabled' }}
                            >
                                <option value="">{{ trans('update.choose_a_state') }}</option>

                                @if(!empty($provinces))
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" {{ (!empty($user) and $user->province_id == $province->id) ? 'selected' : '' }}>{{ $province->title }}</option>
                                    @endforeach
                                @endif
                            </select>

                            <div class="invalid-feedback d-block">@error('province_id') {{ $message }} @enderror</div>
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
                                {{ (!empty($user) and $user->province_id) ? '' : 'disabled' }}
                            >
                                <option value="">{{ trans('update.choose_a_city') }}</option>

                                @if(!empty($cities))
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ (!empty($user) and $user->city_id == $city->id) ? 'selected' : '' }}>{{ $city->title }}</option>
                                    @endforeach
                                @endif
                            </select>

                            <div class="invalid-feedback d-block">@error('city_id') {{ $message }} @enderror</div>
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="form-group ">
                            <label class="form-group-label">{{ trans('update.district') }}</label>

                            <select name="district_id"
                                    class="js-ajax-district_id js-district-selection form-control select2"
                                    data-regions-parent="js-instructor-location"
                                    data-map-zoom="15"
                                {{ (!empty($user) and $user->city_id) ? '' : 'disabled' }}
                            >
                                <option value="">{{ trans('update.choose_a_district') }}</option>

                                @if(!empty($districts))
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ (!empty($user) and $user->district_id == $district->id) ? 'selected' : '' }}>{{ $district->title }}</option>
                                    @endforeach
                                @endif
                            </select>

                            <div class="invalid-feedback d-block">@error('district_id') {{ $message }} @enderror</div>
                        </div>
                    </div>

                </div>
            </div>
        @endif

        <div class="form-group {{ (!empty(getStoreSettings('show_address_selection_in_cart'))) ? '' : 'mt-28' }}">
            <label class="form-group-label">{{ trans('update.address') }}</label>
            <textarea name="address" rows="6" class="js-ajax-address form-control">{{ !empty($user) ? $user->address : '' }}</textarea>
            <div class="invalid-feedback d-block">@error('address') {{ $message }} @enderror</div>
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('update.message_to_seller') }}</label>
            <textarea name="message_to_seller" rows="6" class="js-ajax-message_to_seller form-control"></textarea>
            <div class="invalid-feedback d-block">@error('message_to_seller') {{ $message }} @enderror</div>
        </div>


    </div>
</div>
