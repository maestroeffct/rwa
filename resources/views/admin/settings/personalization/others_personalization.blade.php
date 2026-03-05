@php
    if (!empty($itemValue) and !is_array($itemValue)) {
        $itemValue = json_decode($itemValue, true);
    }

@endphp

@push('styles_top')

@endpush

<div class="mt-3" id="others_personalization">

    <form action="{{ getAdminPanelUrl() }}/settings/others_personalization" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="page" value="personalization">
        <input type="hidden" name="others_personalization" value="others_personalization">



        <div class="row">
            <div class="col-12 col-md-6">
                <h5 class="text-dark font-20">{{ trans('update.guarantee') }}</h5>

                <div class="form-group custom-switches-stacked mb-0 mt-2">
                    <label class="custom-switch pl-0">
                        <input type="hidden" name="value[show_guarantee_text]" value="0">
                        <input type="checkbox" name="value[show_guarantee_text]" id="showGuaranteeTextSwitch" value="1" {{ (!empty($itemValue) and !empty($itemValue['show_guarantee_text']) and $itemValue['show_guarantee_text']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                        <span class="custom-switch-indicator"></span>
                        <label class="custom-switch-description mb-0 cursor-pointer" for="showGuaranteeTextSwitch">{{ trans('admin/main.show') }}</label>
                    </label>
                </div>


                <h5 class="text-dark font-20 mt-5">{{ trans('update.secure_payment_text') }}</h5>

                <div class="form-group custom-switches-stacked mb-0 mt-3">
                    <label class="custom-switch pl-0">
                        <input type="hidden" name="value[show_secure_payment_text]" value="0">
                        <input type="checkbox" name="value[show_secure_payment_text]" id="show_secure_payment_text_switch" value="1" {{ (!empty($itemValue) and !empty($itemValue['show_secure_payment_text']) and $itemValue['show_secure_payment_text']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                        <span class="custom-switch-indicator"></span>
                        <label class="custom-switch-description mb-0 cursor-pointer" for="show_secure_payment_text_switch">{{ trans('admin/main.show') }}</label>
                    </label>

                    <div class="mt-1 text-gray-500">{{ trans('update.secure_payment_text_hint') }}</div>
                </div>

                <div class="form-group mt-3">
                    <label>{{ trans('update.secure_payment_image') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text admin-file-manager" data-input="secure_payment_image" data-preview="holder">
                                <i class="fa fa-chevron-up"></i>
                            </button>
                        </div>
                        <input type="text" name="value[secure_payment_image]" id="secure_payment_image" value="{{ (!empty($itemValue) and !empty($itemValue['secure_payment_image'])) ? $itemValue['secure_payment_image'] : '' }}" class="form-control"/>
                    </div>
                    <div class="mt-2 text-gray-500">{{ trans('update.secure_payment_image_hint') }}</div>
                </div>


                <h5 class="text-dark font-20 mt-5">{{ trans('update.avatar_settings') }}</h5>

                <div class="form-group">
                    <label>{{ trans('update.user_avatar_style') }}</label>
                    <select name="value[user_avatar_style]" class="js-user-avatar-style-select form-control">
                        <option value="default_avatar" {{ (!empty($itemValue) and !empty($itemValue['user_avatar_style']) and $itemValue['user_avatar_style'] == 'default_avatar') ? 'selected' : '' }}>{{ trans('update.default_avatar') }}</option>
                        <option value="ui_avatar" {{ (!empty($itemValue) and !empty($itemValue['user_avatar_style']) and $itemValue['user_avatar_style'] == 'ui_avatar') ? 'selected' : '' }}>{{ trans('update.ui_avatar') }}</option>
                    </select>
                </div>

                <div class="form-group js-default-user-avatar-field {{ (empty($itemValue['user_avatar_style']) or (!empty($itemValue['user_avatar_style'])) and $itemValue['user_avatar_style'] == 'default_avatar') ? '' : 'd-none' }}">
                    <label class="input-label">{{ trans('admin/main.user_avatar_background') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text admin-file-manager" data-input="default_user_avatar" data-preview="holder">
                                <i class="fa fa-chevron-up"></i>
                            </button>
                        </div>
                        <input type="text" name="value[default_user_avatar]" id="default_user_avatar" value="{{ (!empty($itemValue) and !empty($itemValue['default_user_avatar'])) ? $itemValue['default_user_avatar'] : '' }}" class="form-control"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('update.default_user_profile_secondary_image') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text admin-file-manager" data-input="default_user_profile_secondary_image" data-preview="holder">
                                <i class="fa fa-chevron-up"></i>
                            </button>
                        </div>
                        <input type="text" name="value[default_user_profile_secondary_image]" id="default_user_profile_secondary_image" value="{{ (!empty($itemValue) and !empty($itemValue['default_user_profile_secondary_image'])) ? $itemValue['default_user_profile_secondary_image'] : '' }}" class="form-control"/>
                    </div>
                </div>


                <h5 class="text-dark font-20 mt-5">{{ trans('update.platform_phone_and_email_position') }}</h5>

                <div class="form-group">
                    <label>{{ trans('update.select_position') }}</label>
                    <select name="value[platform_phone_and_email_position]" class="form-control">
                        <option value="header" {{ (!empty($itemValue) and !empty($itemValue['platform_phone_and_email_position']) and $itemValue['platform_phone_and_email_position'] == 'header') ? 'selected' : '' }}>{{ trans('update.header') }}</option>
                        <option value="footer" {{ (!empty($itemValue) and !empty($itemValue['platform_phone_and_email_position']) and $itemValue['platform_phone_and_email_position'] == 'footer') ? 'selected' : '' }}>{{ trans('update.footer') }}</option>
                    </select>
                </div>

            </div>
        </div>

        <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
    </form>

</div>

@push('scripts_bottom')
    <script src="/assets/admin/js/parts/settings/others_personalization.min.js"></script>
@endpush
