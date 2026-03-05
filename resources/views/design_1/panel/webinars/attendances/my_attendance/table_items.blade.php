<tr>
    {{-- Course and Session --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-8 bg-gray-100">
                <img src="{{ $session->webinar->getIcon() }}" class="img-cover rounded-8" alt="{{ $session->title }}">
            </div>

            <div class="ml-12">
                <span class="d-block text-dark">{{ $session->title }}</span>
                <span class="d-block mt-4 font-12 text-gray-500">{{ $session->webinar->title }}</span>
            </div>
        </div>
    </td>

    {{-- Instructor --}}
    <td class="text-center">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="{{ $session->creator->getAvatar(48) }}" alt="" class="img-cover rounded-circle">
            </div>
            <div class="ml-8">{{ $session->creator->full_name }}</div>
        </div>
    </td>

    {{-- Start Date --}}
    <td class="text-center">
        <span class="">{{ dateTimeFormat($session->date, 'j M Y H:i') }}</span>
    </td>

    {{-- Joined Date --}}
    <td class="text-center">
        @if(!empty($session->myAttendance))
            <div class="d-flex flex-column text-center">
                <span class="">{{ dateTimeFormat($session->myAttendance->joined_at, 'j M Y H:i') }}</span>

                @if(!empty($session->myAttendance->edited_at))
                    <span class="font-12 text-gray-500 mt-4">{{ trans('update.manual_edit') }}</span>
                @endif
            </div>
        @else
            -
        @endif
    </td>

    @php
        $status = "absent";

        if(!empty($session->myAttendance)) {
            $status = $session->myAttendance->status;
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

</tr>
