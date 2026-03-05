

<form action="{{ getAdminPanelUrl('/forums/settings') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="name" value="{{ \App\Models\Setting::$forumsGeneralSettingsName }}">

    <div class="row">
        <div class="col-12 col-md-6">

            <div class="form-group mt-3 custom-switches-stacked">
                <label class="custom-switch pl-0">
                    <input type="hidden" name="value[forums_status]" value="0">
                    <input type="checkbox" name="value[forums_status]" id="forumStatusSwitch" value="1" {{ (!empty($settingValues) and !empty($settingValues['forums_status']) and $settingValues['forums_status']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                    <span class="custom-switch-indicator"></span>
                    <label class="custom-switch-description mb-0 cursor-pointer" for="forumStatusSwitch">{{ trans('admin/main.active') }}</label>
                </label>

                <p class="font-12 text-gray-500 mb-0">{{ trans('update.forum_settings_status_hint') }}</p>
            </div>

        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
</form>
