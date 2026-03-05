<form action="/panel/meetings/{{ $meeting->id }}/update" method="post">
    {{ csrf_field() }}

    <div class="row">

        <div class="col-12 col-lg-4 mt-20 mt-lg-0">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h4 class="font-14 font-weight-bold">{{ trans('update.basic_settings') }}</h4>

                <div class="meeting-basic-settings-image d-flex justify-content-center align-items-center" style="width:100%;text-align:center;min-height:160px;">
                    <img src="/assets/design_1/img/panel/meeting/basic_settings.svg" alt="" class="img-fluid" style="display:inline-block;">
                </div>

                <div class="form-group d-flex align-items-center mt-16">
                    <div class="custom-switch mr-8">
                        <input id="enableSwitch" type="checkbox" name="enable" class="custom-control-input" {{ !$meeting->disabled ? 'checked' : '' }}>
                        <label class="custom-control-label cursor-pointer" for="enableSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="enableSwitch">{{ trans('update.enable_meetings') }}</label>
                    </div>
                </div>

                <div class="mt-16 text-gray-500">{{ trans('update.enable_meetings_hint') }}</div>

                @if(!empty($meetingPackagesFeatureStatus))
                    <div class="form-group d-flex align-items-center mt-16">
                        <div class="custom-switch mr-8">
                            <input id="enableMeetingsPackagesSwitch" type="checkbox" name="enable_meeting_packages" class="custom-control-input" {{ $meeting->enable_meeting_packages ? 'checked' : '' }}>
                            <label class="custom-control-label cursor-pointer" for="enableMeetingsPackagesSwitch"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="enableMeetingsPackagesSwitch">{{ trans('update.enable_meetings_packages') }}</label>
                        </div>
                    </div>
                @endif

            </div>
        </div>


        <div class="col-12 col-lg-4 mt-20 mt-lg-0">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h4 class="font-14 font-weight-bold">{{ trans('update.base_hourly_charge') }}</h4>

                <div class="form-group mt-24">
                    <label class="form-group-label">{{ trans('panel.amount') }}</label>
                    <span class="has-translation bg-gray-100">{{ $currency }}</span>
                    <input type="text" name="amount" class="form-control " value="{{ !empty($meeting) ? convertPriceToUserCurrency($meeting->amount) : old('amount') }}" placeholder="{{ trans('panel.number_only') }}" oninput="validatePrice(this)">
                    <div class="invalid-feedback d-block"></div>
                </div>

                <div class="form-group mt-24">
                    <label class="form-group-label">{{ trans('panel.discount') }}</label>
                    <span class="has-translation bg-gray-100">%</span>
                    <input type="number" name="discount" class="form-control " value="{{ !empty($meeting) ? $meeting->discount : old('discount') }}" placeholder="{{ trans('panel.number_only') }}">
                    <div class="invalid-feedback d-block"></div>
                </div>
            </div>

            <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                <h4 class="font-14 font-weight-bold">{{ trans('update.in_person_meetings') }}</h4>

                <div class="form-group d-flex align-items-center mt-16">
                    <div class="custom-switch mr-8">
                        <input id="inPersonMeetingSwitch" type="checkbox" name="in_person" class="custom-control-input" {{ (!empty($meeting) and $meeting->in_person) ? 'checked' :  '' }}>
                        <label class="custom-control-label cursor-pointer" for="inPersonMeetingSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="inPersonMeetingSwitch">{{ trans('update.available_for_in_person_meetings') }}</label>
                    </div>
                </div>

                <div id="inPersonMeetingAmount" class="form-group mt-28 {{ (!empty($meeting) and $meeting->in_person) ? '' :  'd-none' }}">
                    <label class="form-group-label">{{ trans('update.hourly_amount') }}</label>
                    <span class="has-translation bg-gray-100">{{ $currency }}</span>
                    <input type="text" name="in_person_amount" class="form-control " value="{{ !empty($meeting) ? convertPriceToUserCurrency($meeting->in_person_amount) : old('in_person_amount') }}" placeholder="{{ trans('panel.number_only') }}" oninput="validatePrice(this)">
                    <div class="invalid-feedback d-block"></div>
                </div>

            </div>
        </div>

        <div class="col-12 col-lg-4 mt-20 mt-lg-0">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h4 class="font-14 font-weight-bold">{{ trans('update.group_meeting') }}</h4>

                <div class="form-group d-flex align-items-center mt-16">
                    <div class="custom-switch mr-8">
                        <input id="groupMeetingSwitch" type="checkbox" name="group_meeting" class="custom-control-input" {{ (!empty($meeting) and $meeting->group_meeting) ? 'checked' :  '' }}>
                        <label class="custom-control-label cursor-pointer" for="groupMeetingSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="groupMeetingSwitch">{{ trans('update.available_for_group_meeting') }}</label>
                    </div>
                </div>

                <div class="mt-24 {{ (!empty($meeting) and $meeting->group_meeting) ? '' :  'd-none' }}" id="onlineGroupMeetingOptions">
                    <h4 class="font-14 text-gray-500 font-weight-bold">{{ trans('update.online_group_meeting_options') }}</h4>

                    <div class="form-group mt-24">
                        <label class="form-group-label">{{ trans('update.minimum_students') }}</label>
                        <input type="number" min="2" name="online_group_min_student" class="form-control " value="{{ !empty($meeting) ? $meeting->online_group_min_student : old('online_group_min_student') }}" placeholder="{{ trans('panel.number_only') }}">
                        <div class="invalid-feedback d-block"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.maximum_students') }}</label>
                        <input type="number" name="online_group_max_student" class="form-control " value="{{ !empty($meeting) ? $meeting->online_group_max_student : old('online_group_max_student') }}" placeholder="{{ trans('panel.number_only') }}">
                        <div class="invalid-feedback d-block"></div>
                    </div>

                    <div class="form-group mt-28">
                        <label class="form-group-label">{{ trans('update.hourly_amount') }}</label>
                        <span class="has-translation bg-gray-100">{{ $currency }}</span>
                        <input type="text" name="online_group_amount" class="form-control " value="{{ !empty($meeting) ? convertPriceToUserCurrency($meeting->online_group_amount) : old('online_group_amount') }}" placeholder="{{ trans('panel.number_only') }}" oninput="validatePrice(this)">
                        <div class="invalid-feedback d-block"></div>
                    </div>
                </div>

                <div class="mt-24 {{ (!empty($meeting) and $meeting->group_meeting and $meeting->in_person) ? '' :  'd-none' }}" id="inPersonGroupMeetingOptions">
                    <h4 class="font-14 text-gray-500 font-weight-bold">{{ trans('update.in_person_group_meeting_options') }}</h4>

                    <div class="form-group mt-24">
                        <label class="form-group-label">{{ trans('update.minimum_students') }}</label>
                        <input type="number" min="2" name="in_person_group_min_student" class="form-control " value="{{ !empty($meeting) ? $meeting->in_person_group_min_student : old('in_person_group_min_student') }}" placeholder="{{ trans('panel.number_only') }}">
                        <div class="invalid-feedback d-block"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.maximum_students') }}</label>
                        <input type="number" name="in_person_group_max_student" class="form-control " value="{{ !empty($meeting) ? $meeting->in_person_group_max_student : old('in_person_group_max_student') }}" placeholder="{{ trans('panel.number_only') }}">
                        <div class="invalid-feedback d-block"></div>
                    </div>

                    <div class="form-group mt-28">
                        <label class="form-group-label">{{ trans('update.hourly_amount') }}</label>
                        <span class="has-translation bg-gray-100">{{ $currency }}</span>
                        <input type="text" name="in_person_group_amount" class="form-control " value="{{ !empty($meeting) ? convertPriceToUserCurrency($meeting->in_person_group_amount) : old('in_person_group_amount') }}" placeholder="{{ trans('panel.number_only') }}" oninput="validatePrice(this)">
                        <div class="invalid-feedback d-block"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel-bottom-bar d-flex align-items-center justify-content-end bg-white px-32 py-16">
        <button type="button" id="meetingSettingFormSubmit" class="btn btn-primary">{{ trans('update.save_settings') }}</button>
    </div>
</form>
