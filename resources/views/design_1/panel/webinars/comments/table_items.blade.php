<tr>
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="{{ $comment->user->getAvatar() }}" class="img-cover rounded-circle" alt="">
            </div>

            <span class="ml-8">{{ $comment->user->full_name }}</span>
        </div>
    </td>

    <td class="text-left">
        <a class="text-dark" href="{{ $comment->webinar->getUrl() }}" target="_blank">{{ $comment->webinar->title }}</a>
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

    <td class="text-right">
        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="" class="js-reply-comment" data-comment-id="{{ $comment->id }}">{{ trans('panel.reply') }}</a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="" class="js-report-comment" data-item-id="{{ $comment->webinar_id }}" data-comment-id="{{ $comment->id }}">{{ trans('panel.report') }}</a>
                    </li>

                </ul>
            </div>
        </div>
    </td>
</tr>
