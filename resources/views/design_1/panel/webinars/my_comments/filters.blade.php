<form action="/panel/courses/my-comments" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-6 col-md-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.from') }}</label>
                <input type="text" name="from" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('from') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.to') }}</label>
                <input type="text" name="to" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('to') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('webinars.course') }}</label>

                <select name="course_id" class="form-control searchable-select bg-white" data-allow-clear="false" data-placeholder="{{ trans('update.search_courses') }}"
                        data-api-path="/panel/courses/search"
                        data-item-column-name="title"
                        data-option=""
                        data-webinar-id=""
                >

                </select>
            </div>
        </div>

        <div class="col-6 col-md-3 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('update.filter') }}</button>
        </div>
    </div>
</form>
