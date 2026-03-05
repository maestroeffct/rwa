<tr>
    <td class="text-left">
        <span class="d-block font-16 text-dark">{{ $assignment->title }}</span>
        <span class="d-block font-12 text-gray-500">{{ $assignment->webinar->title }}</span>
    </td>

    <td class="text-center">
        <span class="">{{ !empty($assignment->deadline) ? dateTimeFormat($assignment->deadlineTime, 'j M Y') : '-' }}</span>
    </td>

    <td class="text-center">
        <span class="">{{ !empty($assignment->first_submission) ? dateTimeFormat($assignment->first_submission, 'j M Y | H:i') : '-' }}</span>
    </td>

    <td class="text-center">
        <span class="">{{ !empty($assignment->last_submission) ? dateTimeFormat($assignment->last_submission, 'j M Y | H:i') : '-' }}</span>
    </td>

    <td class="text-center">
        <span class="">{{ !empty($assignment->attempts) ? "{$assignment->usedAttemptsCount}/{$assignment->attempts}" : '-' }}</span>
    </td>

    <td class="text-center">
        <span>{{ (!empty($assignment->assignmentHistory) and !empty($assignment->assignmentHistory->grade)) ? $assignment->assignmentHistory->grade : '-' }}</span>
    </td>

    <td class="text-center">
        <span>{{ $assignment->pass_grade }}</span>
    </td>

    <td class="text-center">
        @if(empty($assignment->assignmentHistory) or ($assignment->assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$notSubmitted))
            <span class="text-danger ">{{ trans('update.assignment_history_status_not_submitted') }}</span>
        @else
            @switch($assignment->assignmentHistory->status)
                @case(\App\Models\WebinarAssignmentHistory::$passed)
                      <div class="d-inline-flex-center px-8 py-6 rounded-8 bg-success-30 font-12 text-success">{{ trans('quiz.passed') }}</div>
                    @break
                @case(\App\Models\WebinarAssignmentHistory::$pending)
                    <div class="d-inline-flex-center px-8 py-6 rounded-8 bg-warning-30 font-12 text-warning">{{ trans('public.pending') }}</div>
                    @break
                @case(\App\Models\WebinarAssignmentHistory::$notPassed)
                    <div class="d-inline-flex-center px-8 py-6 rounded-8 bg-danger-30 font-12 text-danger">{{ trans('quiz.failed') }}</div>
                    @break
            @endswitch
        @endif
    </td>


    <td class="text-right">

        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        @if($assignment->webinar->checkUserHasBought())
                            <a href="{{ "{$assignment->webinar->getLearningPageUrl()}?type=assignment&item={$assignment->id}" }}" target="_blank"
                               class="">{{ trans('update.view_assignment') }}</a>
                        @else
                            <a href="#!" class="not-access-toast ">{{ trans('update.view_assignment') }}</a>
                        @endif
                    </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
