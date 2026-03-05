<div class="d-flex-center flex-column text-center mt-16">
    <div class="d-flex-center size-64 rounded-16 border-gray-4 bg-info-gradient">
        <x-iconsax-bul-message-question class="icons text-white" width="32px" height="32px"/>
    </div>
    <h4 class="mt-12 font-14 text-dark">{{ trans('update.have_an_idea') }}</h4>
    <div class="mt-8 font-12 text-gray-500">{{ trans('update.share_it_with_others_and_get_involved') }}</div>
</div>

<div class="js-forum-answer-form mt-28" data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers/{{ $answer->id }}/update">

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.description') }}</label>
        <textarea name="description" class="js-ajax-description form-control" rows="8">{{ $answer->description }}</textarea>
        <div class="invalid-feedback"></div>
    </div>

</div>
