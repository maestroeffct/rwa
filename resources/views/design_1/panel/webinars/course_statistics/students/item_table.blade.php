<tr>
    {{-- Student --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="{{ $student->getAvatar(48) }}" alt="" class="img-cover rounded-circle">
            </div>
            <div class="ml-8">
                <span class="d-block text-dark">{{ $student->full_name }}</span>
                <span class="d-block mt-2 font-12 text-gray-500">{{ $student->email }}</span>
            </div>
        </div>
    </td>

    {{-- Progress --}}
    <td class="text-left">
        <div class="">{{ $student->course_progress }}%</div>

        <div class="progress-card d-flex bg-gray-100 mt-8">
            <div class="progress-bar bg-primary" style="width: {{ $student->course_progress }}%"></div>
        </div>
    </td>

    {{-- Learning Activity --}}
    <td class="text-center">
        <div class="text-center">
            <div class="">{{ convertMinutesToHourAndMinute($student->learning_activity) }}</div>
            <div class="mt-4 font-12 text-gray-500">{{ trans('home.hours') }}</div>
        </div>
    </td>

    {{-- Passed Quizzes --}}
    <td class="text-center">
        <span class="">{{ $student->passed_quizzes }}</span>
    </td>

    {{-- Passed Assignments --}}
    <td class="text-center">
        <span class="">{{ $student->passed_assignments }}/{{ $student->total_assignments }}</span>
    </td>

    {{-- Certificates --}}
    <td class="text-center">
        <span class="">{{ $student->certificatesCount }}</span>
    </td>

    {{-- Enrollment Date --}}
    <td class="text-center">
        @if(!empty($student->purchased_at))
            <div class="text-center">
                <div class="">{{ dateTimeFormat($student->purchased_at, 'j M Y H:i') }}</div>
                <div class="mt-4 font-12 text-gray-500">{{ dateTimeFormatForHumans($student->purchased_at, true, null, 1) }}</div>
            </div>
        @else
            -
        @endif
    </td>
</tr>
