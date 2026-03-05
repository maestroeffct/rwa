<div data-action="{{ !empty($quiz) ? ('/panel/quizzes/'. $quiz->id .'/update') : ('/panel/quizzes/store') }}" class="js-content-form quiz-form">

    {{-- Locale --}}
    <div class="mt-24">
        @include('design_1.panel.includes.locale.locale_select',[
                'itemRow' => !empty($quiz) ? $quiz : null,
                'withoutReloadLocale' => true,
                'extraClass' => 'js-quiz-locale'
            ])
    </div>

    @if(empty($selectedWebinar))
        <div class="form-group ">
            <label class="form-group-label">{{ trans('panel.webinar') }}</label>
            <select name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][webinar_id]" class="js-ajax-webinar_id form-control select2">
                <option {{ !empty($quiz) ? 'disabled' : 'selected disabled' }} value="">{{ trans('panel.choose_webinar') }}</option>

                @foreach($webinars as $webinar)
                    <option value="{{ $webinar->id }}" {{  (!empty($quiz) and $quiz->webinar_id == $webinar->id) ? 'selected' : '' }}>{{ $webinar->title }}</option>
                @endforeach
            </select>

            <div class="invalid-feedback"></div>
        </div>
    @else
        <input type="hidden" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][webinar_id]" value="{{ $selectedWebinar->id }}">
    @endif

    <div class="form-group ">
        <label class="form-group-label">{{ trans('public.chapter') }}</label>
        <select name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][chapter_id]" class="js-ajax-chapter_id form-control {{ (!empty($inWebinarPage) and empty($quiz)) ? 'js-make-select2-item' : 'select2' }}">
            <option value="">{{ trans('update.choose_a_chapter') }}</option>
            @if(!empty($chapters))
                @foreach($chapters as $ch)
                    <option value="{{ $ch->id }}" {{ (!empty($quiz) and $quiz->chapter_id == $ch->id) ? 'selected' : '' }}>{{ $ch->title }}</option>
                @endforeach
            @endif
        </select>

        <div class="invalid-feedback"></div>
    </div>


    <div class="form-group">
        <label class="form-group-label">{{ trans('public.title') }}</label>
        <input type="text" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ (!empty($quiz) and !empty($quiz->translate($locale))) ? $quiz->translate($locale)->title : '' }}"/>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.description') }}</label>
        <textarea type="text" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][description]" rows="3" class="js-ajax-description form-control">{{ (!empty($quiz) and !empty($quiz->translate($locale))) ? $quiz->translate($locale)->description : '' }}</textarea>
        <div class="invalid-feedback"></div>
    </div>

    <h4 class="font-16 font-weight-bold my-24">{{ trans('update.quiz_options') }}</h4>

    <div class="form-group">
        <label class="form-group-label">{{ trans('update.icon') }}</label>

        <div class="custom-file bg-white">
            <input type="file" name="icon" class="js-ajax-upload-file-input js-ajax-icon custom-file-input" data-upload-name="icon" id="iconInput" accept="image/*">
            <span class="custom-file-text">{{ (!empty($quiz) and !empty($quiz->icon)) ? getFileNameByPath($quiz->icon) : '' }}</span>
            <label class="custom-file-label" for="iconInput">{{ trans('update.browse') }}</label>
        </div>

        @error('icon')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

        @if(!empty($quiz) and !empty($quiz->icon))
            <a href="{{ url($quiz->icon) }}" target="_blank" class="text-danger mt-4">{{ trans('update.preview') }}</a>
        @endif
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.time') }}</label>
        <span class="has-translation bg-gray-100 font-14 text-gray-500 w-auto px-4">{{ trans('public.minutes') }}</span>
        <input type="number" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][time]" value="{{ !empty($quiz) ? $quiz->time : old('time') }}" class="js-ajax-time form-control" placeholder="{{ trans('forms.empty_means_unlimited') }}" min="0"/>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('quiz.number_of_attemps') }}</label>
        <input type="number" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][attempt]" value="{{ !empty($quiz) ? $quiz->attempt : old('attempt') }}" class="js-ajax-attempt form-control" placeholder="{{ trans('forms.empty_means_unlimited') }}" min="0"/>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('quiz.pass_mark') }}</label>
        <input type="number" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][pass_mark]" value="{{ !empty($quiz) ? $quiz->pass_mark : old('pass_mark') }}" class="js-ajax-pass_mark form-control" min="0"/>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('update.expiry_days') }}</label>
        <span class="has-translation bg-gray-100 font-14 text-gray-500 w-auto px-4">{{ trans('public.days') }}</span>
        <input type="number" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][expiry_days]" value="{{ !empty($quiz) ? $quiz->expiry_days : old('expiry_days') }}" class="js-ajax-expiry_days form-control" min="0"/>
        <div class="invalid-feedback"></div>

        <p class="font-12 text-gray-500 mt-8">{{ trans('update.quiz_expiry_days_hint') }}</p>
    </div>

    @if(!empty($quiz))
        <div class="form-group d-flex align-items-center mt-16">
            <div class="custom-switch mr-8">
                <input id="displayLimitedQuestionsSwitch{{ $quiz->id }}" type="checkbox" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][display_limited_questions]" class="js-ajax-display_limited_questions custom-control-input" {{ ($quiz->display_limited_questions) ? 'checked' : ''}}>
                <label class="custom-control-label cursor-pointer" for="displayLimitedQuestionsSwitch{{ $quiz->id }}"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="displayLimitedQuestionsSwitch{{ $quiz->id }}">{{ trans('update.display_limited_questions') }}</label>
            </div>
        </div>

        <div class="form-group js-display-limited-questions-count-field {{ ($quiz->display_limited_questions) ? '' : 'd-none' }}">
            <label class="form-group-label">{{ trans('update.number_of_questions') }} ({{ trans('update.total_questions') }}: {{ $quiz->quizQuestions->count() }})</label>
            <input type="number" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][display_number_of_questions]" value="{{ $quiz->display_number_of_questions }}" class="js-ajax-display_number_of_questions form-control" min="1"/>
            <div class="invalid-feedback"></div>
        </div>
    @endif

    <div class="form-group d-flex align-items-center mt-16">
        <div class="custom-switch mr-8">
            <input id="displayQuestionsRandomlySwitch{{ !empty($quiz) ? $quiz->id : 'record' }}" type="checkbox" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][display_questions_randomly]" class="custom-control-input" {{ (!empty($quiz) && $quiz->display_questions_randomly) ? 'checked' : ''}}>
            <label class="custom-control-label cursor-pointer" for="displayQuestionsRandomlySwitch{{ !empty($quiz) ? $quiz->id : 'record' }}"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="displayQuestionsRandomlySwitch{{ !empty($quiz) ? $quiz->id : 'record' }}">{{ trans('update.display_questions_randomly') }}</label>
        </div>
    </div>

    <div class="form-group d-flex align-items-center mt-16">
        <div class="custom-switch mr-8">
            <input id="certificateSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}" type="checkbox" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][certificate]" class="custom-control-input" {{ (!empty($quiz) && $quiz->certificate) ? 'checked' : ''}}>
            <label class="custom-control-label cursor-pointer" for="certificateSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="certificateSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}">{{ trans('quiz.certificate_included') }}</label>
        </div>
    </div>

    <div class="form-group d-flex align-items-center mt-16">
        <div class="custom-switch mr-8">
            <input id="statusSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}" type="checkbox" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][status]" class="custom-control-input" {{ (!empty($quiz) && $quiz->status == 'active') ? 'checked' : ''}}>
            <label class="custom-control-label cursor-pointer" for="statusSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="statusSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}">{{ trans('quiz.active_quiz') }}</label>
        </div>
    </div>

    <input type="hidden" name="ajax[{{ !empty($quiz) ? $quiz->id : 'new' }}][is_webinar_page]" value="@if(!empty($inWebinarPage) and $inWebinarPage) 1 @else 0 @endif">

    @if(!empty($inWebinarPage))
        <div class="mt-20 d-flex align-items-center justify-content-end">
            <button type="button" class="js-save-course-content btn btn-lg btn-primary">{{ trans('public.save') }}</button>

            @if(empty($quiz))
                <button type="button" class="btn btn-lg btn-danger ml-12 cancel-accordion">{{ trans('public.close') }}</button>
            @endif
        </div>
    @elseif(!empty($isQuizPage))



    @endif

</div>
