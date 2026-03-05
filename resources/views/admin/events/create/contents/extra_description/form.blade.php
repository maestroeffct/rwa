<form id="eventFaqsForm" action="{{ getAdminPanelUrl("/events/{$event->id}/extra-description/") }}{{ !empty($extraDescription) ? ($extraDescription->id.'/update') : 'store' }}" method="post">
    <input type="hidden" name="type" value="{{ $type }}">

    @if($type == \App\Models\WebinarExtraDescription::$COMPANY_LOGOS)
        <div class="form-group">
            <label class="input-label">{{ trans('update.image') }}</label>
            <div class="js-ajax-value input-group">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text admin-file-manager" data-input="extraDescriptionImage" data-preview="holder">
                        <i class="fa fa-upload"></i>
                    </button>
                </div>
                <input type="text" name="value" id="extraDescriptionImage" value="{{ (!empty($extraDescription) and !empty($extraDescription->translate($locale))) ? $extraDescription->translate($locale)->value : '' }}" class="js-ajax-image form-control @error('image')  is-invalid @enderror"/>
                <div class="input-group-append">
                    <button type="button" class="input-group-text admin-file-view" data-input="extraDescriptionImage">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="invalid-feedback"></div>
        </div>
    @else

        @if(!empty(getGeneralSettings('content_translate')))
            <div class="form-group">
                <label class="input-label">{{ trans('auth.language') }}</label>
                <select name="locale" class="form-control {{ !empty($extraDescription) ? 'js-event-content-locale' : '' }}" data-title="{{ trans('update.edit_faq') }}">
                    @foreach($userLanguages as $lang => $language)
                        <option value="{{ $lang }}" @if($locale == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
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
            <label class="input-label">{{ trans('public.title') }}</label>
            <input type="text" name="value" class="js-ajax-value form-control" placeholder="{{ trans('forms.maximum_255_characters') }}" value="{{ (!empty($extraDescription) and !empty($extraDescription->translate($locale))) ? $extraDescription->translate($locale)->value : '' }}"/>
            <div class="invalid-feedback"></div>
        </div>

    @endif

</form>

