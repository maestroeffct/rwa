<div class="add-answer-card position-relative rounded-16 border-gray-200 p-16 pt-24 mt-20 {{ (empty($answer) or (!empty($loop) and $loop->iteration == 1)) ? 'main-answer-box' : '' }}">

    <div class="form-group">
        <label class="form-group-label">{{ trans('quiz.answer_title') }}</label>
        <input type="text" name="ajax[answers][{{ !empty($answer) ? $answer->id : 'ans_tmp' }}][title]" class=" form-control {{ !empty($answer) ? 'js-ajax-answer-title-'.$answer->id : '' }}" value="{{ !empty($answer) ? $answer->title : '' }}"/>
    </div>


    <div class="form-group">
        <label class="form-group-label">{{ trans('quiz.answer_image') }}</label>

        <div class="custom-file bg-white">
            <input type="file" name="ajax[answers][{{ !empty($answer) ? $answer->id : 'ans_tmp' }}][file]" class="js-ajax-upload-file-input js-ajax-image custom-file-input" data-upload-name="ajax[answers][{{ !empty($answer) ? $answer->id : 'ans_tmp' }}][file]" id="imageInput_{{ !empty($answer) ? $answer->id : '_ans_tmp' }}" accept="image/*">
            <span class="custom-file-text">{{ (!empty($answer) and !empty($answer->image)) ? getFileNameByPath($answer->image) : '' }}</span>
            <label class="custom-file-label" for="imageInput_{{ !empty($answer) ? $answer->id : '_ans_tmp' }}">{{ trans('browse') }}</label>
        </div>

        @if(!empty($answer) and !empty($answer->image))
            <a href="{{ $answer->image }}" target="_blank" class="font-12 text-primary mt-8">{{ trans('update.preview') }}</a>
        @endif

        <div class="invalid-feedback d-block"></div>
    </div>

    <div class="d-flex align-items-center justify-content-between">
        <div class="form-group mb-0 d-flex align-items-center js-switch-parent">
            <div class="custom-switch mr-8">
                <input id="correctAnswerSwitch_{{ !empty($answer) ? $answer->id : '' }}" type="checkbox" name="ajax[answers][{{ !empty($answer) ? $answer->id : 'ans_tmp' }}][correct]" class="js-switch custom-control-input" {{ (!empty($answer) and $answer->correct) ? 'checked' : ''}}>
                <label class="custom-control-label cursor-pointer js-switch" for="correctAnswerSwitch_{{ !empty($answer) ? $answer->id : '' }}"></label>
            </div>

            <div class="">
                <label class="cursor-pointer js-switch" for="correctAnswerSwitch_{{ !empty($answer) ? $answer->id : '' }}">{{ trans('quiz.correct_answer') }}</label>
            </div>
        </div>


        <div class="d-flex-center cursor-pointer answer-remove {{ (!empty($answer) and !empty($loop) and $loop->iteration > 1) ? '' : 'd-none' }}">
            <x-iconsax-lin-trash class="icons text-danger" width="24px" height="24px"/>
        </div>

    </div>

</div>
