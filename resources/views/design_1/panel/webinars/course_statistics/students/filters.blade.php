<form action="" method="get" class="">

    <div class="row mt-24">
        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.search') }}</label>
                <input type="text" name="search" class="form-control" value="" placeholder="{{ trans('search') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('update.enrollment_date_range') }}</label>
                <input type="text" name="date_range" class="form-control date-range-picker" data-format="YYYY/MM/DD" value="{{ request()->get('date_range') }}">
            </div>
        </div>

        @php
            $sortItems = [
                /*'progress_asc',
                'progress_desc',
                'learning_activity_asc',
                'learning_activity_desc',
                'passed_quizzes_asc',
                'passed_quizzes_desc',
                'passed_assignments_asc',
                'passed_assignments_desc',*/
                'certificates_asc',
                'certificates_desc',
                'enrollment_date_asc',
                'enrollment_date_desc',
            ];
        @endphp

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.filters') }}</label>
                <select name="sort" class="form-control select2">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($sortItems as $sortItem)
                        <option value="{{ $sortItem }}" {{ ($sortItem == request()->get('sort')) ? 'selected' : '' }}>{{ trans("update.{$sortItem}") }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form view-data-without-scroll btn btn-primary btn-lg btn-block">{{ trans('update.filters') }}</button>
        </div>
    </div>
</form>
