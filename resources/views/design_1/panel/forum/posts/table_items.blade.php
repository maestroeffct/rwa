<tr>
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 bg-gray-200 rounded-circle">
                <img src="{{ $post->topic->creator->getAvatar(48) }}" class="img-cover rounded-circle" alt="">
            </div>

            <a href="{{ $post->topic->getPostsUrl() }}" target="_blank" class="text-dark">
                <div class="ml-8 text-dark">
                    <span class="d-block font-14">{{ $post->topic->title }}</span>
                    <span class="d-block font-12 text-gray-500 mt-4">{{ trans('public.by') }} {{ $post->topic->creator->full_name }}</span>
                </div>
            </a>
        </div>
    </td>

    <td class="text-center">{{ $post->topic->forum->title }}</td>

    <td class="text-center">{{ $post->replies_count }}</td>

    <td class="text-center">{{ dateTimeFormat($post->created_at, 'j M Y H:i') }}</td>
</tr>
