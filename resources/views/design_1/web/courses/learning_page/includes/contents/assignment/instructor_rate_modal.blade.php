<div class="d-flex-center flex-column text-center">
    <div class="">
        <img src="/assets/design_1/img/courses/learning_page/assignment/rate.svg" alt="" class="img-fluid" width="215px" height="160px">
    </div>

    <h4 class="mt-12 font-14 text-dark">{{ trans('update.rate_assignment') }}</h4>

    <div class="mt-8 font-12 text-gray-500">{!! nl2br(trans('update.learning_page_assigment_submit_grade_modal_desc')) !!}</div>
</div>

<div class="js-assigment-submit-grade-form mt-24" data-action="/course/assignment/{{ $assignment->id }}/history/{{ $assignmentHistory->id }}/setGrade">

    <input type="hidden" name="student" value="{{ $studentId }}">

    <div class="form-group">
        <label class="form-group-label">{{ trans('update.assignments_grade') }}</label>
        <input type="number" name="grade" class="js-ajax-grade form-control">

        <div class="invalid-feedback"></div>
    </div>
</div>

