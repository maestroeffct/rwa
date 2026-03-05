<tr>
    <td class="text-left" width="35%">
        <a class="text-dark" href="{{ $comment->product->getUrl() }}" target="_blank">{{ $comment->product->title }}</a>
    </td>

    <td class="text-center">
        <button type="button" data-comment-id="{{ $comment->id }}" class="js-view-comment btn btn-sm bg-gray-200">{{ trans('public.view') }}</button>
    </td>

    <td class="text-center">
        @if($comment->status == 'active')
            <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-success-30 font-12 text-success">{{ trans('public.published') }}</span>
        @else
            <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-warning-30 font-12 text-warning">{{ trans('public.pending') }}</span>
        @endif
    </td>

    <td class="text-center">{{ dateTimeFormat($comment->created_at,'j M Y | H:i') }}</td>

    <td class="text-right">
        <input type="hidden" id="commentDescription{{ $comment->id }}" value="{{ nl2br($comment->comment) }}">

        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <button type="button" data-comment-id="{{ $comment->id }}" class="js-edit-comment">{{ trans('public.edit') }}</button>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/courses/comments/{{ $comment->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                    </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
