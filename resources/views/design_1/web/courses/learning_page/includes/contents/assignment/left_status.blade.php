<div class="d-flex-center flex-column text-center w-100 h-100">
    <div class="">
        @if($assignmentHistory->status == "passed")
            <img src="/assets/design_1/img/courses/learning_page/assignment/passed.svg" alt="" class="img-fluid" width="285px" height="212px">
        @elseif($assignmentHistory->status == "not_passed")
            <img src="/assets/design_1/img/courses/learning_page/assignment/failed.svg" alt="" class="img-fluid" width="285px" height="212px">
        @else
            <img src="/assets/design_1/img/courses/learning_page/assignment/pending.svg" alt="" class="img-fluid" width="285px" height="212px">
        @endif
    </div>

    @if($assignmentHistory->status == "passed")
        <h5 class="mt-12 font-16 text-dark">{{ trans('update.assignment_passed_title') }}</h5>
        <div class="mt-8 text-gray-500">{{ trans('update.assignment_passed_desc') }}</div>
    @elseif($assignmentHistory->status == "not_passed")
        <h5 class="mt-12 font-16 text-dark">{{ trans('update.assignment_not_passed_title') }}</h5>
        <div class="mt-8 text-gray-500">{{ trans('update.assignment_not_passed_desc') }}</div>
    @elseif(!$assignmentDeadline)
        <h5 class="mt-12 font-16 text-dark">{{ trans('update.assignment_deadline_error_title') }}</h5>
        <div class="mt-8 text-gray-500">{{ trans('update.assignment_deadline_error_desc') }}</div>
    @else
        <h5 class="mt-12 font-16 text-dark">{{ trans('update.assignment_submission_error_title') }}</h5>
        <div class="mt-8 text-gray-500">{{ trans('update.assignment_submission_error_desc') }}</div>
    @endif
</div>
