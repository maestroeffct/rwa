<tr>
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 bg-gray-200 rounded-circle">
                <img src="{{ $topic->forum->icon }}" class="img-cover rounded-circle" alt="">
            </div>

            <a href="{{ $topic->getPostsUrl() }}" target="_blank" class="ml-8 text-dark">
                <span class="d-block font-14">{{ $topic->title }}</span>
            </a>
        </div>
    </td>

    <td class="text-center">{{ $topic->forum->title }}</td>

    <td class="text-center">{{ $topic->posts_count }}</td>

    <td class="text-center">
        @if($topic->close)
            {{ trans('panel.closed') }}
        @else
            {{ trans('public.published') }}
        @endif
    </td>

    <td class="text-center">{{ dateTimeFormat($topic->created_at, 'j M Y H:i') }}</td>
</tr>
