<form id="eventFaqsForm" action="{{ getAdminPanelUrl("/events/{$event->id}/faqs/") }}{{ !empty($faq) ? ($faq->id.'/update') : 'store' }}" method="post">

    @if(!empty(getGeneralSettings('content_translate')))
        <div class="form-group">
            <label class="input-label">{{ trans('auth.language') }}</label>
            <select name="locale" class="form-control {{ !empty($faq) ? 'js-event-content-locale' : '' }}" data-title="{{ trans('update.edit_faq') }}">
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
        <input type="text" name="title" class="js-ajax-title form-control" placeholder="{{ trans('forms.maximum_255_characters') }}" value="{{ (!empty($faq) and !empty($faq->translate($locale))) ? $faq->translate($locale)->title : '' }}"/>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="input-label">{{ trans('public.answer') }}</label>
        <textarea name="answer" class="js-ajax-answer form-control" rows="6">{{ (!empty($faq) and !empty($faq->translate($locale))) ? $faq->translate($locale)->answer : '' }}</textarea>
        <div class="invalid-feedback"></div>
    </div>

</form>

