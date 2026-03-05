<tr>
    <td class="text-left">
        <div class="user-inline-avatar d-flex align-items-center">
            <div class="avatar size-48 bg-gray-200 rounded-circle">
                <img src="{{ $comment->user->getAvatar() }}" class="js-avatar-img img-cover rounded-circle" alt="">
            </div>
            <span class="user-name ml-8 text-dark">{{ $comment->user->full_name }}</span>
        </div>
    </td>

    <td class="text-left" width="35%">
        <a href="{{ $comment->blog->getUrl() }}" target="_blank" class="text-dark">{{ $comment->blog->title }}</a>
    </td>

    <td class="text-center">
        <input type="hidden" id="commentDescription{{ $comment->id }}" value="{{ nl2br($comment->comment) }}">
        <button type="button" data-comment-id="{{ $comment->id }}" class="js-view-comment btn btn-sm btn-gray">{{ trans('public.view') }}</button>
    </td>

    <td class="text-center">
        @if($comment->status == 'active')
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-success-20 text-success">{{ trans('public.active') }}</span>
        @else
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-warning-20 text-warning">{{ trans('public.pending') }}</span>
        @endif
    </td>

    <td class="text-center">{{ dateTimeFormat($comment->created_at, 'j M Y | H:i') }}</td>
</tr>
