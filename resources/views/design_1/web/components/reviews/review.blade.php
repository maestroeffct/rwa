<div class="{{ !empty($className) ? $className : '' }}">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="{{ $commentCreator->getAvatar() }}" class="js-avatar-img img-cover rounded-circle" alt="{{ $commentCreator->full_name }}">
            </div>
            <div class="ml-8">
                <h6 class="font-14 font-weight-bold">{{ $commentCreator->full_name }}</h6>

                @if(!empty($showRate))
                    @include('design_1.web.components.rate', [
                         'rate' => $comment->rates,
                         'rateCount' => false,
                         'rateClassName' => 'mt-4'
                     ])
                @else
                    <div class="mt-4 font-12 text-gray-500">{{ $commentCreator->role->caption }}</div>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center">
            <span class="font-12 text-gray-500">{{ dateTimeFormat($comment->created_at, 'j M Y H:i') }}</span>

            <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                <button type="button" class="d-flex-center size-20 ml-8 btn-transparent">
                    <x-iconsax-lin-more class="icons text-gray-500" width="20"/>
                </button>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-top-32">
                    <ul class="my-8">

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" class="js-reply-review" data-review="{{ $replyItemId }}">{{ trans('panel.reply') }}</button>
                        </li>

                        @if(auth()->check() and auth()->user()->id == $commentCreator->id)
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="{{ $deleteUrlPrefix }}/{{ $comment->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 text-gray-500 font-14">
        {!! clean($description, 'description') !!}
    </div>

    @if(!empty($replies) and count($replies))
        @foreach($replies as $replyRow)
            @include('design_1.web.components.reviews.review', [
                'comment' => $replyRow,
                'commentCreator' => $replyRow->user,
                'replies' => null,
                'description' => $replyRow->comment,
                'replyItemId' => $replyItemId,
                'showRate' => false,
                'deleteUrlPrefix' => "/comments",
                'className' => 'bg-gray-100 p-16 rounded-8 mt-16',
            ])
        @endforeach
    @endif
</div>
