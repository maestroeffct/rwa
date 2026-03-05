<tr>
    <th class="text-left">
        <div class="user-inline-avatar d-flex align-items-center">
            <div class="size-48 bg-gray-200 rounded-circle">
                <img src="{{ $comment->user->getAvatar() }}" class="js-avatar-img img-cover rounded-circle" alt="">
            </div>
            <span class="user-name ml-4 font-weight-500">{{ $comment->user->full_name }}</span>
        </div>
    </th>

    <td class=" text-left" width="35%">
        <a href="{{ $comment->product->getUrl() }}" target="_blank" class="font-weight-500 text-dark">{{ $comment->product->title }}</a>
    </td>

    <td class="text-center">
        <button type="button" data-comment-id="{{ $comment->id }}" class="js-view-comment btn btn-sm bg-gray-200">{{ trans('public.view') }}</button>
    </td>

    <td class="text-center">
        @if(empty($comment->reply_id))
            <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-warning-30 font-12 text-warning">{{ trans('public.open') }}</span>
        @else
            <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-success-30 font-12 text-success">{{ trans('panel.replied') }}</span>
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

                    @if(!empty($order->product) and $order->product->isVirtual() and !empty($order->product->files) and count($order->product->files))
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/store/products/{{ $order->product->id }}/getFilesModal" class="js-show-product-files ">{{ trans('home.download') }}</a>
                        </li>
                    @endif

                    <li class="actions-dropdown__dropdown-menu-item">
                        <button type="button" data-comment-id="{{ $comment->id }}" class="js-reply-comment ">{{ trans('panel.reply') }}</button>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <button type="button" data-item-id="{{ $comment->product_id }}" data-comment-id="{{ $comment->id }}" class="report-comment">{{ trans('panel.report') }}</button>
                    </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
