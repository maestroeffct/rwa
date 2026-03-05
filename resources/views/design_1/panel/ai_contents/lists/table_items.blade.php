<tr>
    <td class="text-left">
        {{ trans($content->service_type) }}
    </td>

    <td class="text-center">
        @if(!empty($content->template))
            {{ $content->template->title }}
        @else
            {{ trans('update.custom') }}
        @endif
    </td>

    <td class="text-center">
        <span class="">{{ !empty($content->keyword) ? truncate($content->keyword, 100) : '-' }}</span>
    </td>

    <td class="text-center">
        <span class="">{{ $content->language ? truncate($content->language, 100) : '-' }}</span>
    </td>

    <td class="text-center">{{ dateTimeFormat($content->created_at, 'j F Y H:i') }}</td>

    <td class="d-flex justify-content-end">
        <input type="hidden" class="js-prompt" value="{{ $content->prompt }}">
        <input type="hidden" class="js-result" value="{{ $content->result }}">

        <div class="js-view-content d-flex-center size-40 rounded-circle cursor-pointer">
            <x-iconsax-lin-eye class="icons text-gray-500" width="20px" height="20px"/>
        </div>
    </td>

</tr>
