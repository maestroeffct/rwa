<form action="" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.from') }}</label>
                <input type="text" name="from" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('from') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.to') }}</label>
                <input type="text" name="to" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('to') }}">
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
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.instructor') }}</label>
                <select name="instructor_id" class="form-control select2" data-placeholder="{{ trans('public.all') }}">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($allInstructors as $allInstructor)
                        <option value="{{ $allInstructor->id }}">{{ $allInstructor->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.status') }}</label>
                <select class="form-control select2" id="status" name="status" data-minimum-results-for-search="Infinity">
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
                <select name="sort" class="form-control select2">
                    <option value="">{{ trans('all') }}</option>

                    @foreach($sortItems as $sortItem)
                        <option value="{{ $sortItem }}" {{ ($sortItem == request()->get('sort')) ? 'selected' : '' }}>{{ trans("update.{$sortItem}") }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-12 col-lg-3 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('filter') }}</button>
        </div>
    </div>
</form>
