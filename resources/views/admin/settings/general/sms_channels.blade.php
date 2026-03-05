@php
    if (!empty($itemValue) and !is_array($itemValue)) {
        $itemValue = json_decode($itemValue, true);
    }
@endphp

<div class="tab-pane mt-3 fade" id="sms_channels" role="tabpanel" aria-labelledby="sms_channels-tab">
    <div class="row">
        <div class="col-12 col-md-6">
            <form action="{{ getAdminPanelUrl() }}/settings/sms_channels" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="page" value="general">
                <input type="hidden" name="sms_channels" value="sms_channels">

                <div class="mb-5">
                    <h5>{{ trans('update.sms_channel') }}</h5>

                    <div class="form-group">
                        <label class="input-label">{{ trans('update.sms_sending_channel') }}</label>
                        <select name="value[sms_sending_channel]" class="form-control">
                            <option value="">{{ trans('update.select_a_sms_channel') }}</option>

                            @foreach(\App\Mixins\Notifications\SendSMS::allChannels as $smsChannel)
                                <option value="{{ $smsChannel }}" {{ (!empty($itemValue) and !empty($itemValue["sms_sending_channel"]) and $itemValue["sms_sending_channel"] == $smsChannel) ? 'selected' : '' }}>{{ trans("update.sms_channel_{$smsChannel}") }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-5">
                    <h5>{{ trans('update.check_mobile_number') }}</h5>

                    <div class="form-group custom-switches-stacked">
                        <label class="custom-switch pl-0">
                            <input type="hidden" name="value[check_mobile_number]" value="0">
                            <input type="checkbox" name="value[check_mobile_number]" id="checkMobileNumberSwitch" value="1"
                                   {{ (!empty($itemValue) and !empty($itemValue['check_mobile_number']) and $itemValue['check_mobile_number']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                            <span class="custom-switch-indicator"></span>
                            <label class="custom-switch-description mb-0 cursor-pointer"
                                   for="checkMobileNumberSwitch">{{ trans('update.check_mobile_number') }}</label>
                        </label>
                        <div class="text-gray-500 text-small mt-1">{{ trans('update.check_mobile_number_switch_hint') }}</div>
                    </div>

                    <div class="js-check-mobile-number-digits-filed {{ (!empty($itemValue) and !empty($itemValue['check_mobile_number']) and $itemValue['check_mobile_number']) ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label class="input-label">{{ trans('update.number_of_digits_in_mobile_number') }}</label>
                            <input type="number" name="value[number_of_digits_in_mobile_number]" id="number_of_digits_in_mobile_number"
                                   value="{{ (!empty($itemValue) and !empty($itemValue['number_of_digits_in_mobile_number'])) ? $itemValue['number_of_digits_in_mobile_number'] : 1 }}"
                                   class="form-control"/>
                            <p class="font-12 text-gray-500 mt-1 mb-0">{{ trans('update.number_of_digits_in_mobile_number_input_hint') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h5>{{ trans('update.twilio_api_settings') }}</h5>

                    @foreach(['twilio_sid', 'twilio_auth_token', 'twilio_number']  as $twilioConf)
                        <div class="form-group">
                            <label>{{ trans("update.{$twilioConf}") }}</label>
                            <input type="text" name="value[{{ $twilioConf }}]" value="{{ (!empty($itemValue) and !empty($itemValue["{$twilioConf}"])) ? $itemValue["{$twilioConf}"] : old("{$twilioConf}") }}" class="form-control "/>
                        </div>
                    @endforeach

                </div>

                <div class="mb-5">
                    <h5>{{ trans('update.kavenegar_settings') }}</h5>

                    @foreach(['kavenegar_url', 'kavenegar_api_key', 'kavenegar_number']  as $kvngConf)
                        <div class="form-group">
                            <label>{{ trans("update.{$kvngConf}") }}</label>
                            <input type="text" name="value[{{ $kvngConf }}]" value="{{ (!empty($itemValue) and !empty($itemValue["{$kvngConf}"])) ? $itemValue["{$kvngConf}"] : old("{$kvngConf}") }}" class="form-control "/>
                        </div>
                    @endforeach
                </div>

                <div class="mb-5">
                    <h5>{{ trans('update.msegat_settings') }}</h5>

                    @foreach(['msegat_username', 'msegat_user_sender', 'msegat_api_key']  as $msegatConf)
                        <div class="form-group">
                            <label>{{ trans("update.{$msegatConf}") }}</label>
                            <input type="text" name="value[{{ $msegatConf }}]" value="{{ (!empty($itemValue) and !empty($itemValue["{$msegatConf}"])) ? $itemValue["{$msegatConf}"] : old("{$msegatConf}") }}" class="form-control "/>
                        </div>
                    @endforeach
                </div>

                <div class="mb-5">
                    <h5>{{ trans('update.vonage_settings') }}</h5>

                    @foreach(['vonage_number', 'vonage_key', 'vonage_secret', 'vonage_application_id', 'vonage_private_key']  as $vonageConf)
                        <div class="form-group">
                            <label>{{ trans("update.{$vonageConf}") }}</label>
                            <input type="text" name="value[{{ $vonageConf }}]" value="{{ (!empty($itemValue) and !empty($itemValue["{$vonageConf}"])) ? $itemValue["{$vonageConf}"] : old("{$vonageConf}") }}" class="form-control "/>
                        </div>
                    @endforeach
                </div>

                <div class="mb-5">
                    <h5>{{ trans('update.msg91_settings') }}</h5>

                    @foreach(['msg91_key', "msg91_flow_id"]  as $msg91Conf)
                        <div class="form-group">
                            <label>{{ trans("update.{$msg91Conf}") }}</label>
                            <input type="text" name="value[{{ $msg91Conf }}]" value="{{ (!empty($itemValue) and !empty($itemValue["{$msg91Conf}"])) ? $itemValue["{$msg91Conf}"] : old("{$msg91Conf}") }}" class="form-control "/>
                        </div>
                    @endforeach
                </div>

                <div class="mb-5">
                    <h5>{{ trans('update.2factor_settings') }}</h5>

                    @foreach(['2factor_api_key']  as $factorConf)
                        <div class="form-group">
                            <label>{{ trans("update.{$factorConf}") }}</label>
                            <input type="text" name="value[{{ $factorConf }}]" value="{{ (!empty($itemValue) and !empty($itemValue["{$factorConf}"])) ? $itemValue["{$factorConf}"] : old("{$factorConf}") }}" class="form-control "/>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
            </form>
        </div>
    </div>
</div>
