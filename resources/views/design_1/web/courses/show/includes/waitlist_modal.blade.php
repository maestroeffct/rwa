<div class="d-flex-center flex-column text-center mt-16">
    <div class="">
        <img src="/assets/design_1/img/courses/waitlist.svg" alt="{{ trans('update.join_waitlist') }}" class="img-fluid" height="160px">
    </div>

    <h6 class="font-12 font-weight-bold mt-12">{{ trans('update.course_waitlist') }}</h6>
    <p class="mt-4 font-12 text-gray-500">{{ trans('update.course_waitlist_modal_hint') }}</p>
</div>

<form action="/course/{{ $course->slug }}/waitlists/join" method="post" class="js-course-waitlist-form mt-24">

    @if(empty($user))
        <div class="form-group">
            <label class="form-group-label">{{ trans('public.name') }}</label>
            <input name="name" type="text" class="js-ajax-name form-control">
            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('public.email') }}</label>
            <input name="email" type="text" class="js-ajax-email form-control">
            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('public.phone') }}</label>
            <input name="phone" type="text" class="js-ajax-phone form-control">
            <div class="invalid-feedback"></div>
        </div>

        @include('design_1.web.includes.captcha_input')

    @endif

</form>
