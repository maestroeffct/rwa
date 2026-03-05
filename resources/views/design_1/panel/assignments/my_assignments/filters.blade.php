<form action="" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-500" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.from') }}</label>
                <input type="text" name="from" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('from') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-500" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.to') }}</label>
                <input type="text" name="to" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('to') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('product.course') }}</label>
                <select name="webinar_id" class="form-control select2">
                    <option value="">{{ trans('webinars.all_courses') }}</option>

                    @foreach($webinars as $webinar)
                        <option value="{{ $webinar->id }}" @if(request()->get('webinar_id') == $webinar->id) selected @endif>{{ $webinar->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('panel.course_type') }}</label>
                <select name="course_type" class="form-control select2 bg-white" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('all') }}</option>

                    @foreach([\App\Models\Webinar::$webinar, \App\Models\Webinar::$course, \App\Models\Webinar::$textLesson] as $courseType)
                        <option value="{{ $courseType }}">{{ trans("update.{$courseType}") }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.search') }}</label>
                <input type="text" name="search" class="form-control" placeholder="{{ trans('update.search_title,_description,...') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.instructors') }}</label>
                <select name="instructor_id" class="form-control select2">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" @if(request()->get('instructor_id') == $instructor->id) selected @endif>{{ $instructor->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.status') }}</label>
                <select class="form-control select2" id="status" name="status" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach(\App\Models\WebinarAssignmentHistory::$assignmentHistoryStatus as $status)
                        <option value="{{ $status }}" {{ (request()->get('status') == $status) ? 'selected' : '' }}>{{ trans('update.assignment_history_status_'.$status) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('filter') }}</button>
        </div>
    </div>
</form>
