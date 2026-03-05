<form id="eventSpeakersForm" action="{{ getAdminPanelUrl("/events/{$event->id}/speakers/") }}{{ !empty($speaker) ? ($speaker->id.'/update') : 'store' }}" method="post">

    <div class="row">
        <div class="col-6">
            @if(!empty(getGeneralSettings('content_translate')))
                <div class="form-group">
                    <label class="input-label">{{ trans('auth.language') }}</label>
                    <select name="locale" class="form-control {{ !empty($speaker) ? 'js-event-content-locale' : '' }}" data-title="{{ trans('update.edit_speaker') }}">
                        @foreach($userLanguages as $lang => $language)
                            <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
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
        </div>

        <div class="col-6">
            <div class="form-group">
                <label class="input-label">{{ trans('public.name') }}</label>
                <input type="text" name="name" value="{{ (!empty($speaker) and !empty($speaker->translate($locale))) ? $speaker->translate($locale)->name : old('name') }}" class="js-ajax-name form-control " placeholder=""/>
                <div class="invalid-feedback d-block"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="input-label">{{ trans('panel.job_title') }}</label>
                <input type="text" name="job" value="{{ (!empty($speaker) and !empty($speaker->translate($locale))) ? $speaker->translate($locale)->job : old('job') }}" class="js-ajax-job form-control " placeholder=""/>
                <div class="invalid-feedback d-block"></div>
            </div>
        </div>

        <div class="col-6">

            <div class="form-group">
                <label class="input-label">{{ trans('public.description') }}</label>
                <textarea name="description" rows="2" class="js-ajax-description form-control">{!! (!empty($speaker) and !empty($speaker->translate($locale))) ? $speaker->translate($locale)->description : old('description')  !!}</textarea>
                <div class="invalid-feedback d-block"></div>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="input-label">{{ trans('update.image') }} ({{ trans('public.optional') }})</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="input-group-text admin-file-manager" data-input="speakerImage" data-preview="holder">
                            <i class="fa fa-upload"></i>
                        </button>
                    </div>
                    <input type="text" name="image" id="speakerImage" value="{{ !empty($speaker) ? $speaker->image : old('image') }}" class="js-ajax-image form-control @error('image')  is-invalid @enderror"/>
                    <div class="input-group-append">
                        <button type="button" class="input-group-text admin-file-view" data-input="speakerImage">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <label class="input-label">{{ trans('public.link') }}</label>
                <input type="text" name="link" value="{{ !empty($speaker) ? $speaker->link : old('link') }}" class="js-ajax-point form-control"/>
                <div class="invalid-feedback d-block"></div>
            </div>
        </div>
    </div>


    <div class="form-group mt-30 d-flex align-items-center ">
        <label class="cursor-pointer mr-16" for="enableTicketSwitch">{{ trans('update.enable') }}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="enable" class="custom-control-input" id="enableTicketSwitch" {{ (!empty($speaker) and $speaker->enable) ? 'checked' : ''  }}>
            <label class="custom-control-label" for="enableTicketSwitch"></label>
        </div>
    </div>

</form>

