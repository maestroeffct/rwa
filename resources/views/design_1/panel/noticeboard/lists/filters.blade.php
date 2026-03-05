<form action="{{ request()->url() }}" method="get" class="px-16">
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
                <label class="form-group-label">{{ trans('product.course') }}</label>
                <select name="webinar_id" class="form-control select2">
                    <option value="">{{ trans('webinars.all_courses') }}</option>

                    @foreach($webinars as $webinar)
                        <option value="{{ $webinar->id }}">{{ $webinar->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if(!empty($isCourseNotice))
            <div class="col-12 col-lg-3">
                <div class="form-group ">
                    <label class="form-group-label">{{ trans('update.course_category') }}</label>

                    <select name="category_id" class="form-control select2">
                        <option value="">{{ trans('public.all') }}</option>

                        @foreach($categories as $categoryFilter)
                            <option value="{{ $categoryFilter->id }}">{{ $categoryFilter->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <div class="col-12 col-lg-3">
                <div class="form-group ">
                    <label class="form-group-label">{{ trans('quiz.student') }}</label>
                    <select name="student_id" class="form-control searchable-select" data-allow-clear="true" data-placeholder="{{ trans('public.search_user') }}"
                            data-api-path="/users/search"
                            data-item-column-name="full_name"
                            data-option=""
                        {{--data-option="just_teachers"--}}
                    >

                    </select>
                </div>
            </div>
        @endif

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.search') }}</label>
                <input type="text" name="search" class="form-control" value="" placeholder="{{ trans('update.search_title,_description,...') }}">
            </div>
        </div>

        @if(!empty($isCourseNotice))
            <div class="col-12 col-lg-3">
                <div class="form-group ">
                    <label class="form-group-label">{{ trans('update.color') }}</label>
                    <select name="color" class="form-control select2">
                        <option value="">{{ trans('update.all_colors') }}</option>

                        @foreach(\App\Models\CourseNoticeboard::$colors as $noticeColor)
                            <option value="{{ $noticeColor }}" @if(request()->get('color') == $noticeColor) selected @endif>{{ trans('update.course_noticeboard_color_'.$noticeColor) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <div class="col-12 col-lg-3">
                <div class="form-group ">
                    <label class="form-group-label">{{ trans('public.type') }}</label>

                    <select name="type" class="form-control select2" data-minimum-results-for-search="Infinity">
                        <option value="">{{ trans('public.all') }}</option>

                        @foreach(['course','organizations','students','instructors','students_and_instructors'] as $typeAccount)
                            <option value="{{ $typeAccount }}">{{ trans("update.{$typeAccount}") }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        @php
            $sortItems = [
                'create_date_asc',
                'create_date_desc',
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
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('filter') }}</button>
        </div>
    </div>
</form>
