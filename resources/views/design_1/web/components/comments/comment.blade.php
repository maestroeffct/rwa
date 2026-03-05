<div class="{{ $className }}">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="{{ $comment->user->getAvatar() }}" class="js-avatar-img img-cover rounded-circle" alt="{{ $comment->user->full_name }}">
            </div>
            <div class="ml-8">
                <h6 class="font-14 font-weight-bold">{{ $comment->user->full_name }}</h6>
                <div class="mt-4 font-12 text-gray-500">{{ $comment->user->role->caption }}</div>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <span class="font-12 text-gray-500">{{ dateTimeFormat($comment->created_at, 'j M Y') }}</span>

            <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                <button type="button" class="d-flex-center size-20 ml-8 btn-transparent">
                    <x-iconsax-lin-more class="icons text-gray-500" width="20"/>
                </button>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-top-32">
                    <ul class="my-8">

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" class="js-reply-comment" data-comment="{{ $mainCommentId }}" data-item="{{ $commentForItemId }}" data-item-name="{{ $commentForItemName }}">{{ trans('panel.reply') }}</button>
                        </li>

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" class="js-report-comment" data-comment="{{ $comment->id }}" data-item="{{ $commentForItemId }}" data-item-name="{{ $commentForItemName }}">{{ trans('panel.report') }}</button>
                        </li>

                        @if(auth()->check() and auth()->user()->id == $comment->user_id)
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/comments/{{ $comment->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 text-gray-500 font-14">
        {!! clean($comment->comment, 'comment') !!}
    </div>

    @if(!empty($replies) and count($replies))
        @foreach($replies as $replyRow)
            @include('design_1.web.components.comments.comment', [
                'comment' => $replyRow,
                'replies' => null,
                'mainCommentId' => $mainCommentId,
                'commentForItemId' => $commentForItemId,
                'commentForItemName' => $commentForItemName,
                'className' => 'bg-gray-100 p-16 rounded-8 mt-16',
            ])
        @endforeach
    @endif

</div>
