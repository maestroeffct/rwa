<tr>
    {{-- Student --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="{{ $assignmentHistory->student->getAvatar(40) }}" alt="" class="img-cover rounded-circle">
            </div>

            <div class="ml-12">
                <div class="">{{ $assignmentHistory->student->full_name }}</div>

                @if(!empty($assignmentHistory->student->email))
                    <div class="mt-4 font-12 text-gray-500">{{ $assignmentHistory->student->email }}</div>
                @endif
            </div>
        </div>
    </td>

    {{-- Purchase Date --}}
    <td class="text-center">
        @if(!empty($assignmentHistory->purchase_date))
            <div class="text-center">
                <span class="d-block">{{ dateTimeFormat($assignmentHistory->purchase_date, 'j M Y H:i') }}</span>
                <span class="d-block mt-2 font-12 text-gray-500">{{ dateTimeFormat($assignmentHistory->purchase_date, 'j M Y H:i', 1) }}</span>
            </div>
        @else
            -
        @endif
    </td>

    {{-- First Submission --}}
    <td class="text-center">
        @if(!empty($assignmentHistory->firstSubmission->created_at))
            <div class="text-center">
                <span class="d-block">{{ dateTimeFormat($assignmentHistory->firstSubmission->created_at, 'j M Y H:i') }}</span>
                <span class="d-block mt-2 font-12 text-gray-500">{{ dateTimeFormat($assignmentHistory->firstSubmission->created_at, 'j M Y H:i', 1) }}</span>
            </div>
        @else
            -
        @endif
    </td>

    {{-- Last Submission --}}
    <td class="text-center">
        @if(!empty($assignmentHistory->lastSubmission->created_at))
            <div class="text-center">
                <span class="d-block">{{ dateTimeFormat($assignmentHistory->lastSubmission->created_at, 'j M Y H:i') }}</span>
                <span class="d-block mt-2 font-12 text-gray-500">{{ dateTimeFormat($assignmentHistory->lastSubmission->created_at, 'j M Y H:i', 1) }}</span>
            </div>
        @else
            -
        @endif
    </td>

    {{-- Attempts --}}
    <td class="text-center">
        <span class="">{{ ($assignmentHistory->attempts > 0) ? $assignmentHistory->attempts : '-' }}/{{ $assignmentHistory->usedAttemptsCount }}</span>
    </td>

    {{-- Grade --}}
    <td class="text-center">
        <span>{{ $assignmentHistory->grade ?? '-' }}</span>
    </td>

    {{-- Status --}}
    <td class="text-center">
        @if($assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$notSubmitted)
            <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-danger-30 font-12 text-danger">{{ trans('update.not_submitted') }}</span>
        @else
            @switch($assignmentHistory->status)
                @case(\App\Models\WebinarAssignmentHistory::$passed)
                    <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-success-30 font-12 text-success">{{ trans('quiz.passed') }}</span>
                    @break
                @case(\App\Models\WebinarAssignmentHistory::$pending)
                    <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-warning-30 font-12 text-warning">{{ trans('update.pending_review') }}</span>
                    @break
                @case(\App\Models\WebinarAssignmentHistory::$notPassed)
                    <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-danger-30 font-12 text-danger">{{ trans('quiz.failed') }}</span>
                    @break
            @endswitch
        @endif
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
                        <a href="{{ "{$assignmentHistory->assignment->webinar->getLearningPageUrl()}?type=assignment&item={$assignmentHistory->assignment->id}&student={$assignmentHistory->student_id}" }}" target="_blank"
                           class="">{{ trans('update.view_assignment') }}</a>
                    </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
