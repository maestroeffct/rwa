@php
    $item = $personalNote->getItem();
@endphp

<tr>
    <td class="text-left">
        <div class="d-flex flex-column">
            <a class="text-dark" href="{{ $personalNote->course->getUrl() }}" target="_blank">{{ $personalNote->course->title }}</a>
            <span class="d-block font-12 text-gray-500 mt-4">{{ trans('public.by') }} {{ $personalNote->course->teacher->full_name }}</span>
        </div>
    </td>

    <td class="text-left">
        <div class="d-flex flex-column">
            @if(!empty($item))
                <span class="">{{ $item->title }}</span>

                @if(!empty($item->chapter))
                    <span class="font-12 text-gray-500 mt-4">{{ trans('public.chapter') }}: {{ $item->chapter->title }}</span>
                @else
                    -
                @endif
            @else
                -
            @endif
        </div>
    </td>

    <td class="text-center">
        <div class="">
            <input type="hidden" value="{{ $personalNote->note }}">
            <button type="button" class="js-show-note btn btn-sm btn-gray">{{ trans('public.view') }}</button>
        </div>
    </td>

    @if(!empty(getFeaturesSettings('course_notes_attachment')))
        <td class="text-center">
            @if(!empty($personalNote->attachment))
                <a href="/course/personal-notes/{{ $personalNote->id }}/download-attachment" class="btn btn-sm btn-gray">{{ trans('home.download') }}</a>
            @else
                -
            @endif
        </td>
    @endif

    <td class="text-center">{{ dateTimeFormat($personalNote->created_at, 'j M Y | H:i') }}</td>

    <td class="text-right">
        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="{{ "{$personalNote->course->getLearningPageUrl()}?type={$personalNote->getItemType()}&item={$personalNote->targetable_id}" }}" target="_blank" class="">{{ trans('public.view') }}</a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/courses/personal-notes/{{ $personalNote->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                    </li>

                </ul>
            </div>
        </div>
    </td>
</tr>
