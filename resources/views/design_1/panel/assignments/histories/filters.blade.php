<form action="" method="get" class="mt-20 px-16">

    <div class="row">
        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.search') }}</label>
                <input type="text" name="search" class="form-control" value="{{ request()->get('search') }}" placeholder="{{ trans('search') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('update.last_submission_date_range') }}</label>
                <input type="text" name="last_submission_date" class="form-control date-range-picker" data-format="YYYY/MM/DD" value="">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('panel.course_type') }}</label>
                <select name="course_type" class="form-control  bg-white">
                    <option value="">{{ trans('all') }}</option>

                    @foreach([\App\Models\Webinar::$webinar, \App\Models\Webinar::$course, \App\Models\Webinar::$textLesson] as $courseType)
                        <option value="{{ $courseType }}">{{ trans("update.{$courseType}") }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('product.course') }}</label>
                <select name="course_type" class="form-control select2">
                    <option value="">{{ trans('all') }}</option>

                </select>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.category') }}</label>
                <select name="category_id" class="form-control select2">
                    <option value="">{{ trans('all') }}</option>

                    @foreach(['active', 'inactive', 'pending', 'expired'] as $status)
                        <option value="{{ $status }}">{{ trans($status) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.status') }}</label>
                <select name="status" class="form-control select2">
                    <option value="">{{ trans('all') }}</option>

                    @foreach(['active', 'inactive'] as $status)
                        <option value="{{ $status }}">{{ trans($status) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @php
            $sortItems = [
                'average_grade_asc',
                'average_grade_desc',
                'submissions_asc',
                'submissions_desc',
                'pending_submissions_asc',
                'pending_submissions_desc',
                'passed_submissions_asc',
                'passed_submissions_desc',
                'failed_submissions_asc',
                'failed_submissions_desc',
                'last_submission_asc',
                'last_submission_desc',
                'including_pending_submissions',
            ];
        @endphp

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('filters') }}</label>
                <select name="sort" class="form-control select2">
                    <option value="">{{ trans('all') }}</option>

                    @foreach($sortItems as $sortItem)
                        <option value="{{ $sortItem }}" {{ ($sortItem == request()->get('sort')) ? 'selected' : '' }}>{{ trans($sortItem) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('update.filter') }}</button>
        </div>
    </div>
</form>
