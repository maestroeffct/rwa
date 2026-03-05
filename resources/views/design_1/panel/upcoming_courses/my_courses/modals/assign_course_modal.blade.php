<div id="upcomingAssignCourseModal" class="" data-action="/panel/upcoming_courses/{{ $upcomingCourse->id }}/assign-course">

    <div class="d-flex-center flex-column text-center mt-12">
        <img src="/assets/design_1/img/panel/upcoming/assign_course.svg" alt="assign_course" class="img-fluid" width="215px" height="160px">

        <h3 class="font-14 font-weight-bold mt-12">{{ trans('update.are_you_sure_to_assign_a_course?') }}</h3>
        <p class="mt-8 font-12 text-gray-500">{{ trans('update.are_you_sure_to_assign_a_course_hint') }}</p>
    </div>

    <div class="form-group  mt-24">
        <label class="form-group-label">{{ trans('product.course') }}</label>

        <select name="course" class="js-ajax-course form-control js-select2">
            <option value="">{{ trans('update.select_a_course') }}</option>
            @foreach($webinars as $webinar)
                <option value="{{ $webinar->id }}">{{ $webinar->title }}</option>
            @endforeach
        </select>

        <div class="invalid-feedback d-block"></div>
    </div>
</div>
