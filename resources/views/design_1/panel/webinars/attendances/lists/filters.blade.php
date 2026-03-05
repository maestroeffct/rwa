<form action="/panel/courses/attendances" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.search') }}</label>
                <input type="text" name="search" class="form-control" placeholder="{{ trans('update.search_in_live_sessions') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('webinars.course') }}</label>

                <select name="course_id" class="form-control select2"  data-placeholder="{{ trans('update.search_courses') }}">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($myCourses as $myCourse)
                        <option value="{{ $myCourse->id }}" {{ ((!empty($selectedCourse) and $selectedCourse->id == $myCourse->id) or (request()->get('course_id') == $myCourse->id)) ? 'selected' : '' }}>{{ $myCourse->title }}</option>
                    @endforeach

                </select>
            </div>
        </div>

        @php
            $sortItems = [
                'present_students_asc',
                'present_students_desc',
                'late_students_asc',
                'late_students_desc',
                'session_start_date_asc',
                'session_start_date_desc',
            ];
        @endphp

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('filters') }}</label>
                <select name="sort" class="form-control select2">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($sortItems as $sortItem)
                        <option value="{{ $sortItem }}" {{ ($sortItem == request()->get('sort')) ? 'selected' : '' }}>{{ trans("update.{$sortItem}") }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('update.filter') }}</button>
        </div>
    </div>
</form>
