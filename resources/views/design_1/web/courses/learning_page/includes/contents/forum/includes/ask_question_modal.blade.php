<div class="d-flex-center flex-column text-center mt-16">
    <div class="d-flex-center size-64 rounded-16 border-gray-4 bg-info-gradient">
        <x-iconsax-bul-message-question class="icons text-white" width="32px" height="32px"/>
    </div>
    <h4 class="mt-12 font-14 text-dark">{{ trans('update.have_a_question?') }}</h4>
    <div class="mt-8 font-12 text-gray-500">{{ trans('update.ask_your_questions_in_course_forum_and_communicate_with_other_users') }}</div>
</div>

<div class="js-forum-question-form mt-28" data-action="{{ $course->getForumPageUrl() }}/{{ !empty($forum) ? ($forum->id. "/update") : 'store' }}">

    <div class="form-group">
        <label class="form-group-label">{{ trans('quiz.question_title') }}</label>
        <input type="text" name="title" class="js-ajax-title form-control" value="{{ !empty($forum) ? $forum->title : '' }}">
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.description') }}</label>
        <textarea name="description" class="js-ajax-description form-control" rows="8">{{ !empty($forum) ? $forum->description : '' }}</textarea>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('update.attachment') }}</label>
        <div class="js-ajax-attachment custom-file bg-white">
            <input type="file" name="attachment" class="js-ajax-upload-file-input custom-file-input" data-upload-name="attachment" id="attachmentInput" accept="">
            <span class="custom-file-text">{{ (!empty($forum) and !empty($forum->attach)) ? getFileNameByPath($forum->attach) : '' }}</span>
            <label class="custom-file-label bg-transparent" for="attachmentInput">
                <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
            </label>
        </div>

        <div class="invalid-feedback"></div>
    </div>

</div>
