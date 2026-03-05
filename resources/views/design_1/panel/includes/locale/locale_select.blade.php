@if(getGeneralSettings('content_translate'))
    <div class="form-group {{ !empty($className) ? $className : '' }}">
        <label class="form-group-label">{{ trans('language') }}</label>

        <select name="locale"
                class="form-control select2 {{ !empty($withoutReloadLocale) ? '' : 'js-reload-when-selected' }} {{ !empty($extraClass) ? $extraClass : '' }}" {!! !empty($extraData) ? $extraData : '' !!}
                data-minimum-results-for-search="Infinity"
        >
            @foreach(getUserLanguagesLists() as $lang => $language)
                <option
                    value="{{ mb_strtolower($lang) }}"
                    {{ (mb_strtolower(request()->get('locale', (!empty($itemRow) and !empty($itemRow->locale)) ? $itemRow->locale : app()->getLocale())) == mb_strtolower($lang)) ? 'selected' : '' }}>
                    {{ $language }} {{ (!empty($itemRow) and empty($itemRow->translate(mb_strtolower($lang)))) ? '('.trans('update.not_defined').')' : '' }}
                </option>
            @endforeach
        </select>
    </div>
@else
    <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
@endif

