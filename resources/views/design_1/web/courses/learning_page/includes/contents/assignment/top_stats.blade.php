<div class="d-grid grid-columns-4 gap-16 mt-16">

    {{-- Deadline --}}
    <div class="learning-page-top-stat-box-120 d-flex align-items-start justify-content-between p-16 rounded-16 bg-white border-gray-200">
        <div class="d-flex justify-content-between flex-column mt-8 h-100">
            <span class="text-gray-500">{{ trans('update.deadline') }}</span>

            <div class="d-flex align-items-end font-24 font-weight-bold">
                @if($assignmentDeadline)
                    @if(is_bool($assignmentDeadline))
                        <span class="">{{ trans('update.unlimited') }}</span>
                    @else
                        <span class="">{{ ceil($assignmentDeadline) }}</span>
                        <span class="font-12 text-gray-500 font-weight-400 ml-4">{{ trans('public.days') }}</span>
                    @endif
                @else
                    <span class="text-danger">{{ trans('panel.expired') }}</span>
                @endif
            </div>
        </div>

        <div class="d-flex-center size-48 rounded-12 bg-primary-40">
            <x-iconsax-lin-calendar-2 class="icons text-primary" width="24px" height="24px"/>
        </div>
    </div>

    {{-- Submission Times --}}
    <div class="learning-page-top-stat-box-120 d-flex align-items-start justify-content-between p-16 rounded-16 bg-white border-gray-200">
        <div class="d-flex justify-content-between flex-column mt-8 h-100">
            <span class="text-gray-500">{{ trans('update.submission_times') }}</span>

            <div class="d-flex align-items-end font-24 font-weight-bold">
                @if(!empty($assignment->attempts))
                    {{ $submissionTimes }}/{{ $assignment->attempts  }}
                @else
                    {{ trans('update.unlimited') }}
                @endif
            </div>
        </div>

        <div class="d-flex-center size-48 rounded-12 bg-success-40">
            <x-iconsax-lin-refresh-2 class="icons text-success" width="24px" height="24px"/>
        </div>
    </div>

    {{-- Your Grade --}}
    <div class="learning-page-top-stat-box-120 d-flex align-items-start justify-content-between p-16 rounded-16 bg-white border-gray-200">
        <div class="d-flex justify-content-between flex-column mt-8 h-100">
            <span class="text-gray-500">{{ trans('quiz.your_grade') }}</span>

            <div class="d-flex align-items-end font-24 font-weight-bold">
                {{ $assignmentHistory->grade ?? 0 }}/{{ $assignment->grade }}
            </div>
        </div>

        <div class="d-flex-center size-48 rounded-12 bg-warning-40">
            <x-iconsax-lin-note-2 class="icons text-warning" width="24px" height="24px"/>
        </div>
    </div>

    {{-- Pass Grade --}}
    <div class="learning-page-top-stat-box-120 d-flex align-items-start justify-content-between p-16 rounded-16 bg-white border-gray-200">
        <div class="d-flex justify-content-between flex-column mt-8 h-100">
            <span class="text-gray-500">{{ trans('update.pass_grade') }}</span>

            <div class="d-flex align-items-end font-24 font-weight-bold">
                {{ $assignment->pass_grade }}
            </div>
        </div>

        <div class="d-flex-center size-48 rounded-12 bg-danger-40">
            <x-iconsax-lin-tick-circle class="icons text-danger" width="24px" height="24px"/>
        </div>
    </div>

</div>
