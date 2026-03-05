@php
    $currentLocal = request()->get('locale', \App\Models\Setting::$defaultSettingsLocale);

    $itemValue = (!empty($settings) and !empty($settings['referral_how_work']) and !empty($settings['referral_how_work']->translate(mb_strtolower($currentLocal)))) ? $settings['referral_how_work']->translate(mb_strtolower($currentLocal))->value : '';

    if (!empty($itemValue) and !is_array($itemValue)) {
        $itemValue = json_decode($itemValue, true);
    }
@endphp

<div class="tab-pane mt-3 fade active show " id="referral_how_work" role="tabpanel" aria-labelledby="referral_how_work-tab">
    <div class="row">
        <div class="col-12 col-md-6">
            <form action="{{ getAdminPanelUrl() }}/settings/main" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="page" value="financial">
                <input type="hidden" name="name" value="referral_how_work">

                @if(!empty(getGeneralSettings('content_translate')))
                    <div class="form-group">
                        <label class="input-label">{{ trans('auth.language') }}</label>
                        <select name="locale" class="form-control js-edit-content-locale">
                            @foreach($userLanguages as $lang => $language)
                                <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', (!empty($itemValue) and !empty($itemValue['locale'])) ? $itemValue['locale'] : app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
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
                            <button type="button" class="input-group-text admin-file-manager" data-input="howWorkImage" data-preview="holder">
                                <i class="fa fa-chevron-up"></i>
                            </button>
                        </div>
                        <input type="text" name="value[image]" id="howWorkImage" value="{{ (!empty($itemValue) and !empty($itemValue['image'])) ? $itemValue['image'] : '' }}" class="js-ajax-logo form-control"/>
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ trans('admin/main.description') }}</label>
                    <textarea name="value[description]" class="form-control" rows="6" placeholder="">{{ (!empty($itemValue) and !empty($itemValue['description'])) ? $itemValue['description'] : old('description') }}</textarea>
                    <div class="text-gray-500 text-small mt-1">{{ trans('update.referral_how_work_setting_description_field_hint') }}</div>
                </div>

                <button type="submit" class="btn btn-success">{{ trans('admin/main.save_change') }}</button>
            </form>
        </div>
    </div>
</div>
