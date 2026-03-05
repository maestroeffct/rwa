<div class=" mt-20">
    <div class="row">
        <div class="col-12 col-md-6">
            <form action="{{ getAdminPanelUrl("/settings/mobile-app") }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="name" value="{{ \App\Models\Setting::$mobileAppGeneralSettingsName }}">

                <div class="form-group">
                    <label>{{ trans('update.api_key') }}</label>
                    <input type="text" name="value[api_key]" class="form-control " value="{{ (!empty($itemValue) and !empty($itemValue['api_key'])) ? $itemValue['api_key'] : old('api_key') }}"/>
                    <div class="text-muted mt-8 font-12">{{ trans('update.mobile_app_setting_api_key_input_hint') }}</div>
                </div>



                <button type="submit" class="btn btn-success">{{ trans('admin/main.save_change') }}</button>
            </form>
        </div>
    </div>
</div>
