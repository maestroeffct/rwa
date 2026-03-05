<div class=" mt-3">
    <div class="row">
        <div class="col-12 col-md-6">
            <form action="{{ getAdminPanelUrl() }}/settings/main" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="name" value="guaranty_text">
                <input type="hidden" name="page" value="personalization">

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
                    <label>{{ trans('admin/main.course_guaranty_text') }}</label>
                    <input type="text" name="value[course_guaranty_text]" value="{{ (!empty($itemValue) and !empty($itemValue['course_guaranty_text'])) ? $itemValue['course_guaranty_text'] : old('course_guaranty_text') }}" class="form-control " required/>
                </div>

                <div class="form-group">
                    <label>{{ trans('admin/main.bundle_guaranty_text') }}</label>
                    <input type="text" name="value[bundle_guaranty_text]" value="{{ (!empty($itemValue) and !empty($itemValue['bundle_guaranty_text'])) ? $itemValue['bundle_guaranty_text'] : old('bundle_guaranty_text') }}" class="form-control " required/>
                </div>


                <div class="form-group">
                    <label>{{ trans('update.event_guaranty_text') }}</label>
                    <input type="text" name="value[event_guaranty_text]" value="{{ (!empty($itemValue) and !empty($itemValue['event_guaranty_text'])) ? $itemValue['event_guaranty_text'] : old('event_guaranty_text') }}" class="form-control " required/>
                </div>


                <button type="submit" class="btn btn-success">{{ trans('admin/main.save_change') }}</button>
            </form>
        </div>
    </div>
</div>
