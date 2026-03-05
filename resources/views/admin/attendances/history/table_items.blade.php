<tr class="text-center">

    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-8 bg-gray-100">
                <img src="{{ $session->webinar->getIcon() }}" class="img-cover rounded-8" alt="{{ $session->title }}">
            </div>

            <div class="ml-12">
                <a href="{{ getAdminPanelUrl("/attendances/{$session->id}/details") }}" target="_blank" class="text-dark">{{ $session->title }}</a>
                <span class="d-block mt-4 font-12 text-gray-500">{{ $session->webinar->title }}</span>
            </div>
        </div>
    </td>


    {{-- Instructor --}}
    <td class="text-left">
        @if(!empty($session->creator))
            <div class="d-flex align-items-center">
                <figure class="avatar mr-2">
                    <img src="{{ $session->creator->getAvatar() }}" alt="{{ $session->creator->full_name }}">
                </figure>
                <div class="media-body ml-1">
                    <div class="mt-0 mb-1">{{ $session->creator->full_name }}</div>

                    @if($session->creator->mobile)
                        <div class="text-gray-500 text-small">{{ $session->creator->mobile }}</div>
                    @elseif($session->creator->email)
                        <div class="text-gray-500 text-small">{{ $session->creator->email }}</div>
                    @endif
                </div>
            </div>
        @endif
    </td>

    {{-- Start Date --}}
    <td class="">
        <span class="">{{ dateTimeFormat($session->date, 'j M Y H:i') }}</span>
    </td>

    {{-- Session api --}}
    <td class="">
        <span class="">{{ trans("update.session_api_{$session->session_api}") }}</span>
    </td>

    {{-- Students count	 --}}
    <td class="">
        <span class="">{{ $session->total_students }}</span>
    </td>

    {{-- Attendees count --}}
    <td class="">
        <span class="">{{ $session->present_count }}</span>
    </td>

    {{-- Absentees count --}}
    <td class="">
        <span class="">{{ $session->late_count }}</span>
    </td>

    {{-- Students with delays --}}
    <td class="">
        <span class="">{{ $session->absent_count }}</span>
    </td>

    {{-- Actions --}}
    <td class="text-center mb-2" width="120">
        <div class="btn-group dropdown table-actions position-relative">
            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
            </button>

            <div class="dropdown-menu dropdown-menu-right">
                @can('admin_attendances_history_details')
                    <a href="{{ getAdminPanelUrl("/attendances/{$session->id}/details") }}" target="_blank" class="dropdown-item d-flex align-items-center py-3 px-0 gap-4">
                        <x-iconsax-lin-eye class="icons text-gray-500" width="18px" height="18px"/>
                        <span class="text-gray-500 font-14">{{ trans('update.view_details') }}</span>
                    </a>
                @endcan
            </div>
        </div>
    </td>
</tr>
