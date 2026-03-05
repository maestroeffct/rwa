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
                <label class="form-group-label">{{ trans('webinars.webinar') }}</label>
                <select name="webinar_id" class="form-control select2">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($userWebinars as $webinar)
                        <option value="{{ $webinar->id }}" @if(request()->get('webinar_id',null) == $webinar->id) selected @endif>{{ $webinar->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('quiz.student') }}</label>

                <select name="student_id" class="form-control select2">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($students as $student)
                        <option value="{{ $student->id }}" >{{ $student->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


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

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.type') }}</label>
                <select class="form-control select2" id="type" name="type" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach(['live_class','text_lesson','course','meeting','product','bundle'] as $saleType)
                        <option value="{{ $saleType }}">{{ trans("update.{$saleType}") }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @php
            $sortItems = [
                'price_asc',
                'price_desc',
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
