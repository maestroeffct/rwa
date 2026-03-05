<div class="@if(!empty($quiz)) multipleQuestionModal{{ $quiz->id }} @endif">
    <div class="custom-modal-body">
        <div class="quiz-questions-form" data-action="/panel/quizzes-questions/{{ empty($question_edit) ? 'store' : $question_edit->id.'/update' }}">

            <input type="hidden" name="ajax[quiz_id]" value="{{ !empty($quiz) ? $quiz->id :'' }}">
            <input type="hidden" name="ajax[type]" value="{{ \App\Models\QuizzesQuestion::$multiple }}">

            <div class="row mt-24">
                <div class="col-12">
                    @include('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($question_edit) ? $question_edit : null,
                        'withoutReloadLocale' => true,
                        'extraClass' => 'js-quiz-question-locale'
                    ])
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('quiz.question_title') }}</label>
                        <input type="text" name="ajax[title]" class="js-ajax-title form-control" value="{{ !empty($question_edit) ? $question_edit->title : '' }}"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('quiz.grade') }}</label>
                        <input type="text" name="ajax[grade]" class="js-ajax-grade form-control" value="{{ !empty($question_edit) ? $question_edit->grade : '' }}"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('quiz.negative_grade') }}</label>
                        <input type="text" name="ajax[negative_grade]" class="js-ajax-negative_grade form-control" value="{{ !empty($question_edit) ? $question_edit->negative_grade : '' }}"/>
                        <span class="invalid-feedback"></span>
                        <p class="font-12 text-gray-500 mt-4">{{ trans('quiz.leave_empty_for_no_negative') }}</p>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('public.image') }} ({{ trans('public.optional') }})</label>

                        <div class="custom-file bg-white">
                            <input type="file" name="ajax[image]" class="js-ajax-upload-file-input js-ajax-image custom-file-input" data-upload-name="ajax[image]" id="imageInput_{{ !empty($question_edit) ? $question_edit->id : 'record' }}" accept="image/*">
                            <span class="custom-file-text">{{ (!empty($question_edit) and !empty($question_edit->image)) ? getFileNameByPath($question_edit->image) : '' }}</span>
                            <label class="custom-file-label" for="imageInput_{{ !empty($question_edit) ? $question_edit->id : 'record' }}">{{ trans('update.browse') }}</label>
                        </div>

                        <div class="invalid-feedback d-block"></div>

                        @if(!empty($question_edit) and !empty($question_edit->image))
                            <a href="{{ $question_edit->image }}" target="_blank" class="font-12 text-primary mt-8">{{ trans('update.preview') }}</a>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.video') }} ({{ trans('public.optional') }})</label>

                        <div class="custom-file bg-white">
                            <input type="file" name="ajax[video]" class="js-ajax-upload-file-input js-ajax-video custom-file-input" data-upload-name="ajax[video]" id="videoInput_{{ !empty($question_edit) ? $question_edit->id : 'record' }}" accept="video/*">
                            <span class="custom-file-text">{{ (!empty($question_edit) and !empty($question_edit->video)) ? getFileNameByPath($question_edit->video) : '' }}</span>
                            <label class="custom-file-label" for="videoInput_{{ !empty($question_edit) ? $question_edit->id : 'record' }}">{{ trans('update.browse') }}</label>
                        </div>

                        <div class="invalid-feedback d-block"></div>

                        @if(!empty($question_edit) and !empty($question_edit->video))
                            <a href="{{ $question_edit->video }}" target="_blank" class="font-12 text-primary mt-8">{{ trans('update.preview') }}</a>
                        @endif
                    </div>
                </div>


                <p class="font-12 text-gray-500 col-12">{{ trans('update.quiz_question_image_validation_by_video') }}</p>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-20 p-12 rounded-16 border-dashed border-gray-300">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                        <x-iconsax-bul-tick-square class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h4 class="font-14 font-weight-bold">{{ trans('public.answers') }}</h4>
                        <p class="font-12 text-gray-500 mt-4">{{ trans('update.define_answers_for_this_question') }}</p>
                    </div>
                </div>

                <div class="add-answer-btn d-flex align-items-center cursor-pointer">
                    <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
                    <span class="font-14 text-primary">{{ trans('quiz.add_an_answer') }}</span>
                </div>
            </div>

            <div class="form-group mb-0">
                <input type="hidden" class="js-ajax-current_answer">
                <div class="invalid-feedback d-block"></div>
            </div>

            <div class="add-answer-container pb-20">

                @if (!empty($question_edit->quizzesQuestionsAnswers) and !$question_edit->quizzesQuestionsAnswers->isEmpty())
                    @foreach ($question_edit->quizzesQuestionsAnswers as $answer)
                        @include('design_1.panel.quizzes.create.modals.multiple_answer_form',['answer' => $answer])
                    @endforeach
                @else
                    @include('design_1.panel.quizzes.create.modals.multiple_answer_form')
                @endif
            </div>

        </div>
    </div>
</div>