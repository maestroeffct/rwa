<tr>
    {{-- Course and Session --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-8 bg-gray-100">
                <img src="{{ $session->webinar->getIcon() }}" class="img-cover rounded-8" alt="{{ $session->title }}">
            </div>

            <div class="ml-12">
                <a href="/panel/courses/attendances/{{ $session->id }}/details" target="_blank" class="text-dark">{{ $session->title }}</a>
                <span class="d-block mt-4 font-12 text-gray-500">{{ $session->webinar->title }}</span>
            </div>
        </div>
    </td>

    {{-- Start Date --}}
    <td class="text-center">
        <span class="">{{ dateTimeFormat($session->date, 'j M Y H:i') }}</span>
    </td>

    {{-- Session Provider --}}
    <td class="text-center">
        <span class="">{{ trans("update.session_api_{$session->session_api}") }}</span>
    </td>

    {{-- Total Students --}}
    <td class="text-center">
        <span class="">{{ $session->total_students }}</span>
    </td>

    {{-- Present --}}
    <td class="text-center">
        <span class="">{{ $session->present_count }}</span>
    </td>

    {{-- Late --}}
    <td class="text-center">
        <span class="">{{ $session->late_count }}</span>
    </td>

    {{-- Absent --}}
    <td class="text-center">
        <span class="">{{ $session->absent_count }}</span>
    </td>

    {{-- Actions --}}
    <td class="text-right">
        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/courses/attendances/{{ $session->id }}/details" target="_blank" class="" >{{ trans('update.view_details') }}</a>
                    </li>

                </ul>
            </div>
        </div>
    </td>
</tr>
