<tr>
    <td class="text-left">
        <a href="{{ $post->getUrl() }}" target="_blank" class="text-dark">{{ $post->title }}</a>
    </td>
    <td class="text-center align-middle">{{ $post->category->title }}</td>
    <td class="text-center align-middle">{{ $post->comments_count }}</td>
    <td class="text-center align-middle">{{ $post->visit_count }}</td>

    <td class="text-center align-middle">
        @if($post->status == 'publish')
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-success-20 text-success">{{ trans('public.published') }}</span>
        @else
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-warning-20 text-warning">{{ trans('public.pending') }}</span>
        @endif
    </td>

    <td class="text-center align-middle">{{ dateTimeFormat($post->created_at, 'j M Y H:i') }}</td>

    <td class="text-right">
        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/blog/{{ $post->id }}/edit" class="">{{ trans('public.edit') }}</a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        @can('panel_blog_delete_article')
                            @include('design_1.panel.includes.content_delete_btn', [
                                'deleteContentUrl' => "/panel/blog/{$post->id}/delete",
                                'deleteContentClassName' => 'd-flex align-items-center w-100 px-16 py-8 btn-transparent text-danger',
                                'deleteContentItem' => $post,
                                'deleteContentItemType' => "post",
                            ])
                        @endcan
                    </li>

                </ul>
            </div>
        </div>
    </td>

</tr>
