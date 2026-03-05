<div class="@if(!empty($quiz)) descriptiveQuestionModal{{ $quiz->id }} @endif">
    <div class="custom-modal-body p-16">

        <div class="quiz-questions-form" data-action="/panel/quizzes-questions/{{ empty($question_edit) ? 'store' : $question_edit->id.'/update' }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="ajax[quiz_id]" value="{{ !empty($quiz) ? $quiz->id :'' }}">
            <input type="hidden" name="ajax[type]" value="{{ \App\Models\QuizzesQuestion::$descriptive }}">

            <div class="row mt-24">

                <div class="col-12">
                    @include('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($question_edit) ? $question_edit : null,
                        'withoutReloadLocale' => true,
                        'extraClass' => 'js-quiz-question-locale'
                    ])
                </div>

                <div class="col-12 col-md-8">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('quiz.question_title') }}</label>
                        <input type="text" name="ajax[title]" class="js-ajax-title form-control" value="{{ !empty($question_edit) ? $question_edit->title : '' }}"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('quiz.grade') }}</label>
                        <input type="text" name="ajax[grade]" class="js-ajax-grade form-control" value="{{ !empty($question_edit) ? $question_edit->grade : '' }}"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('public.image') }} ({{ trans('public.optional') }})</label>

                        <div class="custom-file bg-white">
                            <input type="file" name="ajax[image]" class="js-ajax-upload-file-input js-ajax-image custom-file-input" data-upload-name="ajax[image]" id="imageInput_{{ !empty($question_edit) ? $question_edit->id : 'record' }}" accept="image/*">
                            <span class="custom-file-text"></span>
                            <label class="custom-file-label" for="imageInput_{{ !empty($question_edit) ? $question_edit->id : 'record' }}">{{ trans('update.browse') }}</label>
                        </div>

                        <div class="invalid-feedback d-block"></div>

                        @if(!empty($question_edit) and !empty($question_edit->image))
                            <a href="{{ $question_edit->image }}" target="_blank" class="font-12 text-primary mt-8">{{ trans('update.preview') }}</a>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.video') }} ({{ trans('public.optional') }})</label>

                        <div class="custom-file bg-white">
                            <input type="file" name="ajax[video]" class="js-ajax-upload-file-input js-ajax-video custom-file-input" data-upload-name="ajax[video]" id="videoInput_{{ !empty($question_edit) ? $question_edit->id : 'record' }}" accept="video/*">
                            <span class="custom-file-text"></span>
                            <label class="custom-file-label" for="videoInput_{{ !empty($question_edit) ? $question_edit->id : 'record' }}">{{ trans('update.browse') }}</label>
                        </div>

                        <div class="invalid-feedback d-block"></div>

                        @if(!empty($question_edit) and !empty($question_edit->video))
                            <a href="{{ $question_edit->video }}" target="_blank" class="font-12 text-primary mt-8">{{ trans('update.preview') }}</a>
                        @endif
                    </div>
                </div>

            </div>

            <p class="font-12 text-gray-500 col-12">{{ trans('update.quiz_question_image_validation_by_video') }}</p>

            <div class="row mt-20">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('quiz.correct_answer') }}</label>
                        <textarea name="ajax[correct]" class="js-ajax-correct form-control" rows="10">{{ !empty($question_edit) ? $question_edit->correct : '' }}</textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
