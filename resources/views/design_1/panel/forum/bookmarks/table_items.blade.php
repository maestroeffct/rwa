<tr>
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 bg-gray-200 rounded-circle">
                <img src="{{ $topic->creator->getAvatar(48) }}" class="img-cover rounded-circle" alt="">
            </div>

            <a href="{{ $topic->getPostsUrl() }}" target="_blank" class="text-dark">
                <div class="ml-8 text-dark">
                    <span class="d-block font-14">{{ $topic->title }}</span>
                    <span class="d-block font-12 text-gray-500 mt-4">{{ trans('public.by') }} {{ $topic->creator->full_name }}</span>
                </div>
            </a>
        </div>
    </td>

    <td class="text-center">{{ $topic->forum->title }}</td>

    <td class="text-center">{{ $topic->posts_count }}</td>

    <td class="text-center">{{ dateTimeFormat($topic->created_at, 'j M Y H:i') }}</td>

    <td class="text-center">
        <a
            href="/panel/forums/topics/{{ $topic->id }}/removeBookmarks"
            data-msg="{{ trans('update.this_topic_will_be_removed_from_your_bookmark') }}"
            data-confirm="{{ trans('update.confirm') }}"
            class="delete-action d-flex-center size-28 rounded-8 bg-danger-20">
            <x-iconsax-lin-trash class="icons text-danger" width="18px" height="18px"/>
        </a>
    </td>
</tr>
