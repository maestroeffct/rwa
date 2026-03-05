<tr class="text-center">

    {{-- Student --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="{{ $student->getAvatar(48) }}" class="img-cover rounded-circle" alt="{{ $student->full_name }}">
            </div>

            <div class="ml-12">
                <span class="d-block text-dark">{{ $student->full_name }}</span>

                @if(!empty($student->email))
                    <span class="d-block mt-4 font-12 text-gray-500">{{ $student->email }}</span>
                @endif

                @if(!empty($student->mobile))
                    <span class="d-block mt-4 font-12 text-gray-500">+{{ $student->mobile }}</span>
                @endif
            </div>
        </div>
    </td>

    {{-- Joined Date --}}
    <td class="text-center">
        @if(!empty($student->joined_at))
            <div class="d-flex flex-column text-center">
                <span class="">{{ dateTimeFormat($student->joined_at, 'j M Y H:i') }}</span>

                @if(!empty($student->attendance) and !empty($student->attendance->edited_at))
                    <span class="font-12 text-gray-500 mt-4">{{ trans('update.manual_edit') }}</span>
                @endif
            </div>
        @else
            -
        @endif
    </td>

    @php
        $status = "absent";

        if(!empty($student->attendance)) {
            $status = $student->attendance->status;
        }
    @endphp

    {{-- Attendance Status --}}
    <td class="text-center">
        @if($status == "present")
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-success-30 text-success">{{ trans('update.present') }}</span>
        @elseif($status == "late")
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-warning-30 text-warning">{{ trans('update.late') }}</span>
        @else
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-danger-30 text-danger">{{ trans('update.absent') }}</span>
        @endif
    </td>

    {{-- Actions --}}
    <td class="text-center mb-2" width="120">
        <div class="btn-group dropdown table-actions position-relative">
            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
            </button>

            <div class="dropdown-menu dropdown-menu-right">

                @if($status != "present")
                    <a href="{{ getAdminPanelUrl("/attendances/{$session->id}/details/{$student->id}/status/present") }}"
                       class="delete-action dropdown-item d-flex align-items-center py-3 px-0 gap-4"
                       data-msg="{{ trans('update.attendance_mark_as_present_msg') }}"
                       data-modal-title="{{ trans('update.attendance_status') }}"
                       data-confirm="{{ trans('update.yes_convert') }}"
                    >
                        {{ trans('update.mark_as_present') }}
                    </a>
                @endif

                @if($status != "late")
                    <a href="{{ getAdminPanelUrl("/attendances/{$session->id}/details/{$student->id}/status/late") }}"
                       class="delete-action dropdown-item d-flex align-items-center py-3 px-0 gap-4"
                       data-msg="{{ trans('update.attendance_mark_as_late_msg') }}"
                       data-modal-title="{{ trans('update.attendance_status') }}"
                       data-confirm="{{ trans('update.yes_convert') }}"
                    >
                        {{ trans('update.mark_as_late') }}
                    </a>
                @endif

                @if($status != "absent")
                    <a href="{{ getAdminPanelUrl("/attendances/{$session->id}/details/{$student->id}/status/absent") }}"
                       class="delete-action dropdown-item d-flex align-items-center py-3 px-0 gap-4"
                       data-msg="{{ trans('update.attendance_mark_as_absent_msg') }}"
                       data-modal-title="{{ trans('update.attendance_status') }}"
                       data-confirm="{{ trans('update.yes_convert') }}"
                    >
                        {{ trans('update.mark_as_absent') }}
                    </a>
                @endif

            </div>
        </div>
    </td>
</tr>
