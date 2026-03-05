@php
    if (!empty($itemValue) and !is_array($itemValue)) {
        $itemValue = json_decode($itemValue, true);
    }
@endphp

@push('styles_top')

@endpush

<div class="tab-pane mt-3 fade" id="security" role="tabpanel" aria-labelledby="security-tab">

    <form action="{{ getAdminPanelUrl() }}/settings/security" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="page" value="general">
        <input type="hidden" name="security" value="security">

        <div class="row">
            <div class="col-12 col-md-6">

                <div class="form-group custom-switches-stacked">
                    <label class="custom-switch pl-0">
                        <input type="hidden" name="value[login_device_limit]" value="0">
                        <input type="checkbox" name="value[login_device_limit]" id="loginDeviceLimit" value="1"
                               {{ (!empty($itemValue) and !empty($itemValue['login_device_limit']) and $itemValue['login_device_limit']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                        <span class="custom-switch-indicator"></span>
                        <label class="custom-switch-description mb-0 cursor-pointer"
                               for="loginDeviceLimit">{{ trans('update.device_limit') }}</label>
                    </label>
                    <div class="text-gray-500 text-small mt-1">{{ trans('update.device_limit_hint') }}</div>
                </div>

                <div class="js-device-limit-number {{ (!empty($itemValue) and !empty($itemValue['login_device_limit']) and $itemValue['login_device_limit']) ? '' : 'd-none' }}">
                    <div class="form-group">
                        <label class="input-label">{{ trans('update.number_of_allowed_devices') }}</label>
                        <input type="number" name="value[number_of_allowed_devices]" id="number_of_allowed_devices"
                               value="{{ (!empty($itemValue) and !empty($itemValue['number_of_allowed_devices'])) ? $itemValue['number_of_allowed_devices'] : 1 }}"
                               class="form-control"/>
                        <p class="font-12 text-gray-500 mt-1 mb-0">{{ trans('update.number_of_allowed_devices_hint') }}</p>
                    </div>

                    @include('admin.includes.delete_button',[
                                                            'url' => getAdminPanelUrl("/settings/reset-users-login-count"),
                                                           'btnClass' => 'btn-radius16 text-danger border font-14 btn-lg btn-icon icon-left mt-2',
                                                           'btnText' => trans('update.reset_users_login_count'),
                                                           'btnIcon' => 'rotate-left',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
                                                  
                                                        ])


                </div>

                <h5 class="mt-5">{{ trans('update.captcha_settings') }}</h5>
                @php
                    $captchaSwitchs = ['captcha_for_admin_login', 'captcha_for_admin_forgot_pass', 'captcha_for_login', 'captcha_for_register', 'captcha_for_forgot_pass']
                @endphp

                @foreach($captchaSwitchs as $captchaSwitch)
                    <div class="form-group custom-switches-stacked">
                        <label class="custom-switch pl-0 mb-0">
                            <input type="hidden" name="value[{{ $captchaSwitch }}]" value="0">
                            <input type="checkbox" name="value[{{ $captchaSwitch }}]"
                                   id="captchaSwitch{{ $captchaSwitch }}" value="1"
                                   {{ (!empty($itemValue) and !empty($itemValue[$captchaSwitch]) and $itemValue[$captchaSwitch]) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                            <span class="custom-switch-indicator"></span>
                            <label class="custom-switch-description mb-0 cursor-pointer"
                                   for="captchaSwitch{{ $captchaSwitch }}">{{ trans('update.'.$captchaSwitch) }}</label>
                        </label>
                        <div class="text-gray-500 text-small">{{ trans('update.'.$captchaSwitch.'_hint') }}</div>
                    </div>
                @endforeach


                <h5 class="mt-5">{{ trans('update.admin_panel_url') }}</h5>

                <div class="form-group mt-2">
                    <label class="input-label">{{ trans('admin/main.url') }}</label>
                    <input type="text" name="value[admin_panel_url]" id="admin_panel_url"
                           value="{{ (!empty($itemValue) and !empty($itemValue['admin_panel_url'])) ? $itemValue['admin_panel_url'] : 'admin' }}"
                           class="form-control" required/>
                    <p class="font-12 text-gray-500 mt-1 mb-0">{{ trans('update.admin_panel_url_hint') }}</p>
                </div>

                <h5 class="mt-5">{{ trans('admin/main.watermark') }}</h5>

                <div class="form-group custom-switches-stacked mt-3">
                    <label class="custom-switch pl-0">
                        <input type="hidden" name="value[learning_page_watermark]" value="0">
                        <input type="checkbox" name="value[learning_page_watermark]" id="learningPageWatermarkToggle" value="1"
                               {{ (!empty($itemValue) and !empty($itemValue['learning_page_watermark']) and $itemValue['learning_page_watermark']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                        <span class="custom-switch-indicator"></span>
                        <label class="custom-switch-description mb-0 cursor-pointer" for="learningPageWatermarkToggle">{{ trans('admin/main.enable_watermark') }}</label>
                    </label>
                    <div class="text-gray-500 text-small mt-1">{{ trans('admin/main.enable_watermark_hint') }}</div>
                </div>

                @php
                    $wmEnabled = (!empty($itemValue) and !empty($itemValue['learning_page_watermark']) and $itemValue['learning_page_watermark']);
                    $wmMode = (!empty($itemValue) and !empty($itemValue['learning_page_watermark_mode'])) ? $itemValue['learning_page_watermark_mode'] : 'fade';
                    $wmOpacity = (!empty($itemValue) and isset($itemValue['learning_page_watermark_opacity'])) ? $itemValue['learning_page_watermark_opacity'] : '';
                    $wmSize = (!empty($itemValue) and !empty($itemValue['learning_page_watermark_size'])) ? $itemValue['learning_page_watermark_size'] : '1';
                    $wmData = (!empty($itemValue) and !empty($itemValue['learning_page_watermark_data'])) ? $itemValue['learning_page_watermark_data'] : 'student';
                @endphp

                <div class="js-learning-watermark-settings">
                    <div class="form-group">
                        <label class="input-label"> {{ trans('admin/main.watermark_data') }}</label>
                        <select name="value[learning_page_watermark_data]" id="learning_page_watermark_data" class="form-control">
                            <option value="student" {{ $wmData == 'student' ? 'selected' : '' }}>{{ trans('admin/main.student_name') }}</option>
                            <option value="student_name_id" {{ $wmData == 'student_name_id' ? 'selected' : '' }}>{{ trans('admin/main.student_name_id') }}</option>
                            <option value="student_phone" {{ $wmData == 'student_phone' ? 'selected' : '' }}>{{ trans('admin/main.student_phone_number') }}</option>
                            <option value="student_email" {{ $wmData == 'student_email' ? 'selected' : '' }}>{{ trans('admin/main.student_email') }}</option>
                            <option value="instructor" {{ $wmData == 'instructor' ? 'selected' : '' }}>{{ trans('admin/main.instructor_name') }}</option>
                            <option value="platform" {{ $wmData == 'platform' ? 'selected' : '' }}>{{ trans('admin/main.platform_title') }}</option>
                            <option value="platform_logo" {{ $wmData == 'platform_logo' ? 'selected' : '' }}>{{ trans('admin/main.platform_logo') }}</option>
                        </select>
                        <p class="font-12 text-gray-500 mt-1 mb-0">{{ trans('admin/main.watermark_data_hint') }}</p>
                    </div>

                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.watermark_mode') }}</label>
                        <select name="value[learning_page_watermark_mode]" id="learning_page_watermark_mode" class="form-control">
                            <option value="fade" {{ $wmMode == 'fade' ? 'selected' : '' }}>{{ trans('admin/main.fade_mode') }}</option>
                            <option value="move" {{ $wmMode == 'move' ? 'selected' : '' }}>{{ trans('admin/main.move_mode') }}</option>
                        </select>
                        <p class="font-12 text-gray-500 mt-1 mb-0">{{ trans('admin/main.watermark_mode_hint') }}</p>
                    </div>

                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.watermark_size') }}</label>
                        <select name="value[learning_page_watermark_size]" id="learning_page_watermark_size" class="form-control">
                            <option value="1" {{ $wmSize == '1' ? 'selected' : '' }}>{{ trans('admin/main.size_1') }}</option>
                            <option value="2" {{ $wmSize == '2' ? 'selected' : '' }}>{{ trans('admin/main.size_2') }}</option>
                            <option value="3" {{ $wmSize == '3' ? 'selected' : '' }}>{{ trans('admin/main.size_3') }}</option>
                        </select>
                        <p class="font-12 text-gray-500 mt-1 mb-0">{{ trans('admin/main.watermark_size_hint') }}</p>
                    </div>

                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.watermark_opacity') }}</label>
                        <input type="number" step="0.01" min="0" max="1" name="value[learning_page_watermark_opacity]" id="learning_page_watermark_opacity" value="{{ $wmOpacity }}" class="form-control" placeholder="e.g. 0.30"/>
                        <p class="font-12 text-gray-500 mt-1 mb-0">{{ trans('admin/main.watermark_opacity_hint') }}</p>
                    </div>
                </div>


            </div>
        </div>

        <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
    </form>

</div>

@push('scripts_bottom')

@endpush
