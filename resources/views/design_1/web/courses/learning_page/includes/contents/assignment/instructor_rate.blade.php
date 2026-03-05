@if($user->id == $assignment->creator_id and !empty($assignmentStudentId))
    <div class="d-flex align-items-center justify-content-between mt-16 rounded-16 p-12 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                <x-iconsax-bul-star class="icons text-primary" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h5 class="font-14 text-dark">{{ trans('update.rate_assignment') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.rate_assignment_instructor_hint_msg') }}</p>
            </div>
        </div>

        <button type="button"
            class="js-show-submit-rate btn btn-primary btn-lg"
            data-path="/course/assignment/{{ $assignment->id }}/history/{{ $assignmentHistory->id }}/grade-modal?student={{ $assignmentStudentId }}"
        >
            {{ trans('update.submit_rate') }}
        </button>
    </div>
@endif
