<form action="" method="get" class="px-16">

    <div class="row">
        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.search') }}</label>
                <input type="text" name="search" class="form-control" value="{{ request()->get('search') }}" placeholder="{{ trans('search') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('update.last_submission_date_range') }}</label>
                <input type="text" name="last_submission_date" class="form-control date-range-picker" data-format="YYYY/MM/DD" value="">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('quiz.quiz') }}</label>
                <select name="quiz_id" class="form-control select2" data-placeholder="{{ trans('public.all') }}">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($allQuizzesLists as $allQuiz)
                        <option value="{{ $allQuiz->id }}">{{ $allQuiz->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('product.course') }}</label>
                <select name="course_id" class="form-control select2" data-placeholder="{{ trans('public.all') }}">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($allCoursesLists as $allCourseList)
                        <option value="{{ $allCourseList->id }}">{{ $allCourseList->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('quiz.student') }}</label>
                <select name="student_id" class="form-control select2">
                    <option value="">{{ trans('all') }}</option>

                    @foreach($allStudents as $allStudent)
                        <option value="{{ $allStudent->id }}">{{ $allStudent->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.status') }}</label>
                <select name="status" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('all') }}</option>

                    @foreach(['passed', 'failed', 'waiting'] as $status)
                        <option value="{{ $status }}">{{ trans($status) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @php
            $sortItems = [
                'grade_asc',
                'grade_desc',
                'create_date_asc',
                'create_date_desc',
            ];
        @endphp

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('filters') }}</label>
                <select name="sort" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('all') }}</option>

                    @foreach($sortItems as $sortItem)
                        <option value="{{ $sortItem }}" {{ ($sortItem == request()->get('sort')) ? 'selected' : '' }}>{{ trans("update.{$sortItem}") }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('update.filter') }}</button>
        </div>
    </div>
</form>
