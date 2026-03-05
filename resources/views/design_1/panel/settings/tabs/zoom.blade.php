<div class="custom-tabs-content active">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="bg-white rounded-16 p-16 border-gray-200">

                <div class="row">
                    <div class="col-12 col-md-6">
                        <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.zoom_account_credentials') }}</h3>

                        <div class="form-group">
                            <label class="form-group-label">{{ trans('update.zoom_api_key') }}</label>
                            <textarea type="text" name="zoom_api_key" rows="3" class="form-control">{{ (!empty($user) and empty($new_user) and $user->zoomApi) ? $user->zoomApi->api_key : old('api_key') }}</textarea>

                            <p class="font-12 text-gray-500 mt-8"><a href="https://developers.zoom.us/docs/internal-apps/create/#steps-to-create-a-server-to-server-oauth-app" class="text-gray-500">{{ trans('update.zoom_api_key_hint') }}</a></p>
                        </div>

                        <div class="form-group">
                            <label class="form-group-label">{{ trans('update.zoom_api_secret') }}</label>
                            <textarea type="text" name="zoom_api_secret" rows="4" class="form-control">{{ (!empty($user) and empty($new_user) and $user->zoomApi) ? $user->zoomApi->api_secret : old('api_secret') }}</textarea>

                            <p class="font-12 text-gray-500 mt-8"><a href="https://developers.zoom.us/docs/internal-apps/create/#steps-to-create-a-server-to-server-oauth-app" class="text-gray-500">{{ trans('update.zoom_api_secret_hint') }}</a></p>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-group-label">{{ trans('update.zoom_account_id') }}</label>
                            <textarea type="text" name="zoom_account_id" rows="4" class="form-control">{{ (!empty($user) and empty($new_user) and $user->zoomApi) ? $user->zoomApi->account_id : old('account_id') }}</textarea>

                            <p class="font-12 text-gray-500 mt-8"><a href="https://developers.zoom.us/docs/internal-apps/create/#steps-to-create-a-server-to-server-oauth-app" class="text-gray-500">{{ trans('update.zoom_account_id_hint') }}</a></p>
                        </div>

                    </div>

                    <div class="col-12 col-md-6 mt-20 mt-md-0">
                        <div class="d-flex-center flex-column text-center">
                            <img src="/assets/design_1/img/panel/settings/setting_zoom.svg" alt="" class="" width="230px" height="200px">

                            <h4 class="font-14 font-weight-bold mt-12">{{ trans('update.how_to_get_credentials_from_zoom') }}</h4>
                            <p class="mt-8 text-gray-500 font-14">{{ trans('update.how_to_get_credentials_from_zoom_hint') }}</p>

                            <a href="" class="d-flex align-items-center mt-12 font-12 text-primary">
                                <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                                <span class="ml-2">{{ trans('update.learn_more') }}</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
