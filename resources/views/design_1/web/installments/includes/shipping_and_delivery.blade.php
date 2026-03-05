<section class="mt-44">
    <h3 class="font-14">{{ trans('update.shipping_and_delivery') }}</h3>

    <div class="p-16 pt-24 rounded-16 border-gray-200 mt-16">
        <div class="row js-installment-shipping-and-delivery">
            @if(!empty(getStoreSettings('show_address_selection_in_cart')))
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.country') }}</label>

                        <select name="country_id" class="js-country-selection form-control select2 @error('country_id')  is-invalid @enderror" data-regions-parent="js-installment-shipping-and-delivery">
                            <option value="">{{ trans('update.select_country') }}</option>

                            @if(!empty($countries))
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ (!empty($user) and $user->country_id == $country->id) ? 'selected' : '' }}>{{ $country->title }}</option>
                                @endforeach
                            @endif
                        </select>

                        @error('country_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.province') }}</label>

                        <select name="province_id" class="js-state-selection form-control select2 @error('province_id')  is-invalid @enderror" {{ (!empty($user) and $user->province_id) ? '' : 'disabled' }} data-regions-parent="js-installment-shipping-and-delivery">
                            <option value="">{{ trans('update.select_province') }}</option>

                            @if(!empty($provinces))
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ (!empty($user) and $user->province_id == $province->id) ? 'selected' : '' }}>{{ $province->title }}</option>
                                @endforeach
                            @endif
                        </select>

                        @error('province_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.city') }}</label>

                        <select name="city_id" class="js-city-selection form-control select2 @error('city_id')  is-invalid @enderror" {{ (!empty($user) and $user->city_id) ? '' : 'disabled' }} data-regions-parent="js-installment-shipping-and-delivery">
                            <option value="">{{ trans('update.select_city') }}</option>

                            @if(!empty($cities))
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ (!empty($user) and $user->city_id == $city->id) ? 'selected' : '' }}>{{ $city->title }}</option>
                                @endforeach
                            @endif
                        </select>

                        @error('city_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.district') }}</label>

                        <select name="district_id" class="js-district-selection form-control select2 @error('district_id')  is-invalid @enderror" {{ (!empty($user) and $user->district_id) ? '' : 'disabled' }} data-regions-parent="js-installment-shipping-and-delivery">
                            <option value="">{{ trans('update.select_district') }}</option>

                            @if(!empty($districts))
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ (!empty($user) and $user->district_id == $district->id) ? 'selected' : '' }}>{{ $district->title }}</option>
                                @endforeach
                            @endif
                        </select>

                        @error('district_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            @endif

            <div class="col-12 {{ !empty(getStoreSettings('show_address_selection_in_cart')) ? 'col-lg-6' : '' }}">
                <div class="form-group">
                    <label class="form-group-label">{{ trans('update.address') }}</label>

                    <textarea name="address" rows="5" class="form-control @error('address')  is-invalid @enderror">{{ !empty($user) ? $user->address : '' }}</textarea>

                    @error('address')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-group-label">{{ trans('update.message_to_seller') }}</label>

                    <textarea name="message_to_seller" rows="5" class="form-control @error('message_to_seller')  is-invalid @enderror"></textarea>

                    @error('message_to_seller')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</section>

@if(!empty($deliveryEstimateTime))
    <div class="d-flex align-items-center mt-16 rounded-16 border-gray-200 p-16">
        <div class="appointment-timezone-icon">
            <img src="/assets/default/img/icons/timezone.svg" alt="appointment timezone">
        </div>

        <div class="ml-16">
            <h4 class="font-14">{{ trans('update.cart_order_estimated_delivery_time') }}</h4>
            <p class="font-12 text-gray-500">{{ trans('update.cart_order_estimated_delivery_time_hint',['days' => $deliveryEstimateTime]) }}</p>
        </div>
    </div>
@endif


@push('scripts_bottom')
    <script>
        var leafletApiPath = '{{ getLeafletApiPath() }}';
        var selectRegionDefaultVal = '';
        var selectStateLang = '{{ trans('update.choose_a_state') }}';
        var selectCityLang = '{{ trans('update.choose_a_city') }}';
        var selectDistrictLang = '{{ trans('update.all_districts') }}';
    </script>

    <script src="{{ getDesign1ScriptPath("get_regions") }}"></script>
@endpush
