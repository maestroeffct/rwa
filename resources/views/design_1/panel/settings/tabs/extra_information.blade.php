@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
@endpush

<div class="custom-tabs-content active">
    <div class="row">
        <div class="col-12 col-lg-4">

            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.personal_information') }}</h3>

                <div class="form-group">
                    <label class="form-group-label">{{ trans('update.birthday') }}</label>
                    <span class="has-translation"><x-iconsax-lin-calendar-2 class="icons text-gray-500" width="24px" height="24px"/></span>
                    <input type="text" name="birthday" class="form-control datepicker js-default-init-date-picker @error('date') is-invalid @enderror" value="{{ !empty($user->birthday) ? dateTimeFormat($user->birthday, 'Y-m-d H:i', false) : old('birthday') }}" data-format="YYYY/MM/DD" data-show-drops="true"/>

                    @error('birthday')
                    <div class="invalid-feedback"> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-0">
                    <label class="font-14 font-weight-bold bg-white">{{ trans('update.gender') }}:</label>

                    <div class="mt-16">
                        <div class="custom-control custom-radio">
                            <input type="radio" name="gender" value="man" {{ (!empty($user->gender) and $user->gender == 'man') ? 'checked="checked"' : ''}} id="man" class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="man">{{ trans('update.man') }}</label>
                        </div>

                        <div class="custom-control custom-radio mt-12">
                            <input type="radio" name="gender" value="woman" id="woman" {{ (!empty($user->gender) and $user->gender == 'woman') ? 'checked="checked"' : ''}} class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="woman">{{ trans('update.woman') }}</label>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Meetings Settings --}}
            <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.meeting_settings') }}</h3>

                {{--<div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="availableForMeetingsSwitch" type="checkbox" name="available_for_meetings" class="custom-control-input" {{ (!empty($user) and $user->available_for_meetings) ? 'checked' : '' }}>
                        <label class="custom-control-label cursor-pointer" for="availableForMeetingsSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="availableForMeetingsSwitch">{{ trans('update.available_for_meetings') }}</label>
                    </div>
                </div>--}}

                <div class="form-group">
                    <label class="font-14 font-weight-bold bg-white">{{ trans('update.meeting_type') }}:</label>

                    <div class="mt-16">
                        <div class="custom-control custom-radio">
                            <input type="radio" name="meeting_type" value="in_person" id="in_person" {{ (!empty($user->meeting_type) and $user->meeting_type == 'in_person') ? 'checked="checked"' : ''}} class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="in_person">{{ trans('update.in_person') }}</label>
                        </div>

                        <div class="custom-control custom-radio mt-12">
                            <input type="radio" name="meeting_type" value="online" id="online" {{ (!empty($user->meeting_type) and $user->meeting_type == 'online') ? 'checked="checked"' : ''}} class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="online">{{ trans('update.online') }}</label>
                        </div>

                        <div class="custom-control custom-radio mt-12">
                            <input type="radio" name="meeting_type" value="all" id="all" {{ (!empty($user->meeting_type) and $user->meeting_type == 'all') ? 'checked="checked"' : ''}} class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="all">{{ trans('public.all') }}</label>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="font-14 font-weight-bold bg-white">{{ trans('update.level_of_training') }}:</label>

                    <div class="mt-16">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="level_of_training[]" value="beginner" id="beginner" {{ (!empty($user->level_of_training) and is_array($user->level_of_training) and in_array('beginner',$user->level_of_training)) ? 'checked="checked"' : ''}} class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="beginner">{{ trans('update.beginner') }}</label>
                        </div>

                        <div class="custom-control custom-checkbox mt-12">
                            <input type="checkbox" name="level_of_training[]" value="middle" id="middle" {{ (!empty($user->level_of_training) and is_array($user->level_of_training) and in_array('middle',$user->level_of_training)) ? 'checked="checked"' : ''}} class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="middle">{{ trans('update.middle') }}</label>
                        </div>

                        <div class="custom-control custom-checkbox mt-12">
                            <input type="checkbox" name="level_of_training[]" value="expert" id="expert" {{ (!empty($user->level_of_training) and is_array($user->level_of_training) and in_array('expert',$user->level_of_training)) ? 'checked="checked"' : ''}} class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="expert">{{ trans('update.expert') }}</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Forms --}}
            @if(!empty($formFieldsHtml))
                <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                    <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.forms') }}</h3>

                    <div class="">
                        {!! $formFieldsHtml !!}
                    </div>
                </div>
            @endif

        </div>

        <div class="js-user-location col-12 col-lg-8 mt-20 mt-lg-0">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.region') }}</h3>

                        <div class="form-group mt-20 ">
                            <label class="form-group-label">{{ trans('update.country') }}</label>

                            <select name="country_id" class="js-ajax-country_id js-country-selection form-control select2" data-regions-parent="js-user-location" data-map-zoom="5">
                                <option value="">{{ trans('update.choose_a_country') }}</option>

                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ (!empty($user) and $user->country_id == $country->id) ? 'selected' : '' }} data-center="{{ implode(',', $country->geo_center) }}">{{ $country->title }}</option>
                                @endforeach
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group ">
                            <label class="form-group-label">{{ trans('update.state') }}</label>

                            <select
                                name="province_id"
                                {{ (empty($user) or empty($user->country_id)) ? 'disabled' : '' }}
                                class="js-ajax-province_id js-state-selection form-control select2"
                                data-regions-parent="js-user-location"
                                data-map-zoom="8"
                            >
                                <option value="">{{ trans('update.choose_a_state') }}</option>

                                @if(!empty($user) and !empty($user->country_id))
                                    @foreach(\App\Models\Region::getRegionsByTypeAndColumn(\App\Models\Region::$province, 'country_id', $user->country_id) as $province)
                                        <option value="{{ $province->id }}" {{ (!empty($user) and $user->province_id == $province->id) ? 'selected' : '' }} data-center="{{ implode(',', $province->geo_center) }}">{{ $province->title }}</option>
                                    @endforeach
                                @endif
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group ">
                            <label class="form-group-label">{{ trans('update.city') }}</label>

                            <select name="city_id"
                                    class="js-ajax-city_id js-city-selection form-control select2"
                                    {{ (empty($user) or empty($user->province_id)) ? 'disabled' : '' }}
                                    data-regions-parent="js-user-location"
                                    data-map-zoom="12"
                            >
                                <option value="">{{ trans('update.choose_a_city') }}</option>

                                @if(!empty($user) and !empty($user->province_id))
                                    @foreach(\App\Models\Region::getRegionsByTypeAndColumn(\App\Models\Region::$city, 'province_id', $user->province_id) as $city)
                                        <option value="{{ $city->id }}" {{ (!empty($user) and $user->city_id == $city->id) ? 'selected' : '' }} data-center="{{ implode(',', $city->geo_center) }}">{{ $city->title }}</option>
                                    @endforeach
                                @endif
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>


                        <div class="form-group ">
                            <label class="form-group-label">{{ trans('update.district') }}</label>

                            <select name="district_id"
                                    class="js-ajax-district_id js-district-selection form-control select2"
                                    {{ (empty($user) or empty($user->city_id)) ? 'disabled' : '' }}
                                    data-regions-parent="js-user-location"
                                    data-map-zoom="15"
                            >
                                <option value="">{{ trans('update.all_districts') }}</option>

                                @if(!empty($user) and !empty($user->city_id))
                                    @foreach(\App\Models\Region::getRegionsByTypeAndColumn(\App\Models\Region::$district, 'city_id', $user->city_id) as $district)
                                        <option value="{{ $district->id }}" {{ (!empty($user) and $user->district_id == $district->id) ? 'selected' : '' }} data-center="{{ implode(',', $district->geo_center) }}">{{ $district->title }}</option>
                                    @endforeach
                                @endif
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mb-30">
                            <label class="form-group-label">{{ trans('update.address') }}:</label>
                            <input type="text" name="address" value="{{ !empty($user->address) ? $user->address : '' }}" class="form-control">
                        </div>

                    </div>

                    @php
                        $latitude = getDefaultMapsLocation()['lat'];
                        $longitude = getDefaultMapsLocation()['lon'];

                        if(!empty($user->location)) {
                            $latitude = $user->location[0];
                            $longitude = $user->location[1];
                        }
                    @endphp

                    <div class="col-12 col-lg-8">
                        <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.location_on_map') }}</h3>

                        <input type="hidden" id="LocationLatitude" name="latitude" value="{{ $latitude }}">
                        <input type="hidden" id="LocationLongitude" name="longitude" value="{{ $longitude }}">

                        <div class="region-map with-default-initial-drag w-100 rounded-8 mt-16 bg-gray-100" id="mapBox"
                             data-latitude="{{ $latitude }}"
                             data-longitude="{{ $longitude }}"
                             data-zoom="12"
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


            @php
                $socials = getSocials();
                if (!empty($socials) and count($socials)) {
                    $socials = collect($socials)->sortBy('order')->toArray();
                }

                $userSocials = !empty($user->socials) ? json_decode($user->socials, true) : [];
            @endphp

            @if(count($socials))
                <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                    <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.social_networks') }}</h3>

                    @foreach($socials as $socialKey => $socialValue)
                        @if(!empty($socialValue['title']))
                            <div class="d-flex align-items-center gap-12 mt-24">
                                <div class="d-flex-center size-48 bg-gray-100 border-gray-300 rounded-12">
                                    @if(!empty($socialValue['image']))
                                        <img src="{{ $socialValue['image'] }}" alt="" class="img-fluid" width="24px" height="24px">
                                    @else
                                        <x-iconsax-lin-mobile class="icon text-gray-500" width="24px" height="24px"/>
                                    @endif
                                </div>

                                <div class="form-group mb-0 flex-1">
                                    <label class="form-group-label">{{ $socialValue['title'] }}</label>
                                    <input type="text" name="socials[{{ $socialKey }}]" value="{{ (!empty($userSocials) and !empty($userSocials[$socialKey])) ? $userSocials[$socialKey] : '' }}" class="form-control">
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <div class="d-flex align-items-center mt-16 pt-16 border-top-gray-100">
                        <div class="d-flex-center size-48 rounded-12 bg-gray-300">
                            <x-iconsax-bol-info-circle class="icon text-gray-500" width="24px" height="24px"/>
                        </div>
                        <div class="ml-8">
                            <h4 class="font-14">{{ trans('update.note') }}</h4>
                            <p class="font-12 text-gray-500">{{ trans('update.leave_social_media_links_blank_to_hide_them_on_the_front_side') }}</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

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
    <script src="/assets/design_1/js/parts/get_regions.min.js"></script>

@endpush
