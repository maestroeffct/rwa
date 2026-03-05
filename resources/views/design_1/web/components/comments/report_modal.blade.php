<div class="bg-gray-100 p-16 rounded-8 mt-16">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="{{ $comment->user->getAvatar() }}" class="js-avatar-img img-cover rounded-circle" alt="">
            </div>
            <div class="ml-8">
                <h6 class="font-14 font-weight-bold">{{ $comment->user->full_name }}</h6>
                <div class="mt-4 font-12 text-gray-500">{{ $comment->user->role->caption }}</div>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <span class="font-12 text-gray-500">{{ dateTimeFormat($comment->created_at, 'j M Y H:i') }}</span>
        </div>
    </div>

    <div class="mt-12 text-gray-500 font-14">
        {!! clean($comment->comment, 'comment') !!}
    </div>
</div>

<div class="js-comment-report-form mt-28" data-action="/comments/{{ $comment->id }}/report">
    <input type="hidden" name="item_id" value="{{ $itemId }}">
    <input type="hidden" name="item_name" value="{{ $itemType }}">

    <div class="form-group ">
        <label class="form-group-label">{{ trans('public.message_to_reviewer') }}</label>
        <textarea name="message" rows="6" class="js-ajax-message form-control"></textarea>
        <div class="invalid-feedback"></div>
    </div>
</div>
