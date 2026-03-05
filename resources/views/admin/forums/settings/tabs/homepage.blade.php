<form action="{{ getAdminPanelUrl('/forums/settings') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="name" value="{{ \App\Models\Setting::$forumsHomepageSettingsName }}">

    <div class="row">
        <div class="col-12 col-md-6">

            @if(!empty(getGeneralSettings('content_translate')))
                <div class="form-group">
                    <label class="input-label">{{ trans('auth.language') }}</label>
                    <select name="locale" class="form-control js-edit-content-locale">
                        @foreach($userLanguages as $lang => $language)
                            <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', (!empty($settingValues) and !empty($settingValues['locale'])) ? $settingValues['locale'] : app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                        @endforeach
                    </select>
                    @error('locale')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            @else
                <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
            @endif

            <div class="form-group">
                <label class="input-label">{{ trans('admin/main.image') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="input-group-text admin-file-manager" data-input="image" data-preview="holder">
                            <i class="fa fa-chevron-up"></i>
                        </button>
                    </div>
                    <input type="text" name="value[image]" id="image" value="{{ (!empty($settingValues) and !empty($settingValues['image'])) ? $settingValues['image'] : old('image') }}" class="form-control"/>
                </div>
            </div>

            <div class="form-group">
                <label>{{ trans('admin/main.title') }}</label>
                <input type="text" name="value[title]" value="{{ (!empty($settingValues) and !empty($settingValues['title'])) ? $settingValues['title'] : old('title') }}" class="form-control "/>
            </div>

            <div class="form-group">
                <label>{{ trans('public.description') }}</label>
                <textarea type="text" name="value[description]" rows="5" class="form-control ">{{ (!empty($settingValues) and !empty($settingValues['description'])) ? $settingValues['description'] : old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>{{ trans('update.badge') }}</label>
                <div class="row">
                    <div class="col-6">
                        <label>{{ trans('admin/main.title') }}</label>
                        <input type="text" name="value[badge][title]" value="{{ (!empty($settingValues) and !empty($settingValues['badge']) and !empty($settingValues['badge']['title'])) ? $settingValues['badge']['title'] : '' }}" class="form-control "/>
                    </div>
                    <div class="col-6">
                        <label>{{ trans('update.color') }}</label>

                        <div class="input-group colorpickerinput">
                            <input type="text" name="value[badge][color]"
                                   autocomplete="off"
                                   class="form-control"
                                   value="{{ (!empty($settingValues) and !empty($settingValues['badge']) and !empty($settingValues['badge']['color'])) ? $settingValues['badge']['color'] : '' }}"
                                   placeholder="{{ trans('admin/main.trend_color_placeholder') }}"
                            />

                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="fas fa-fill-drip"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3 custom-switches-stacked">
                <label class="custom-switch pl-0">
                    <input type="hidden" name="value[show_search]" value="0">
                    <input type="checkbox" name="value[show_search]" id="showSearchSwitch" value="1" {{ (!empty($settingValues) and !empty($settingValues['show_search'])) ? 'checked' : '' }} class="custom-switch-input"/>
                    <span class="custom-switch-indicator"></span>
                    <label class="custom-switch-description mb-0 cursor-pointer" for="showSearchSwitch">{{ trans('update.show_search') }}</label>
                </label>

                <p class="font-12 text-gray-500 mb-0">{{ trans('update.forum_settings_show_search_hint') }}</p>
            </div>

        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
</form>
