<div class="d-flex-center flex-column text-center mt-16">
    <div class="course-report-icon d-flex-center size-64 rounded-24">
        <x-iconsax-bul-danger class="icons text-white" width="32px" height="32px"/>
    </div>

    <h6 class="font-12 font-weight-bold mt-12">{{ trans('update.report_abuse') }}</h6>
    <p class="mt-4 font-12 text-gray-500">{{ trans('update.report_course_modal_hint') }}</p>
</div>

<form action="/course/{{ $course->id }}/report" method="post" class="js-course-report-form mt-24">

    <div class="form-group ">
        <label class="form-group-label">{{ trans('product.reason') }}</label>
        <select id="reason" name="reason" class="js-ajax-reason form-control select2">
            <option value="" selected disabled>{{ trans('product.select_reason') }}</option>

            @foreach(getReportReasons() as $reason)
                <option value="{{ $reason }}">{{ $reason }}</option>
            @endforeach
        </select>

        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label" for="message_to_reviewer">{{ trans('public.message_to_reviewer') }}</label>
        <textarea name="message" id="message_to_reviewer" class="js-ajax-message form-control" rows="8"></textarea>
        <div class="invalid-feedback"></div>
    </div>
</form>
