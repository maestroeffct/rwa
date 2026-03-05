<form action="/panel/quizzes" method="get" class="px-16">
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
                <label class="form-group-label">{{ trans('quiz.quiz_or_webinar') }}</label>
                <select name="quiz_id" class="form-control select2" data-placeholder="{{ trans('public.all') }}">
                    <option value="all">{{ trans('public.all') }}</option>

                    @foreach($allQuizzesLists as $allQuiz)
                        <option value="{{ $allQuiz->id }}" @if(request()->get('quiz_id') == $allQuiz->id) selected @endif>{{ $allQuiz->title .' - '. ($allQuiz->webinar ? $allQuiz->webinar->title : '-') }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.total_mark') }}</label>
                <input type="text" name="total_mark" class="form-control" value="{{ request()->get('total_mark','') }}"/>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.questions') }}</label>
                <select class="form-control select2" id="questions_type" name="questions_type" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('public.all') }}</option>
                    <option value="multiple" >{{ trans('update.multiple') }}</option>
                    <option value="descriptive" >{{ trans('quiz.descriptive') }}</option>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.status') }}</label>
                <select class="form-control select2" id="status" name="status" data-minimum-results-for-search="Infinity">
                    <option value="all">{{ trans('public.all') }}</option>
                    <option value="active" >{{ trans('public.active') }}</option>
                    <option value="inactive" >{{ trans('public.inactive') }}</option>
                </select>
            </div>
        </div>

        @php
            $sorts = [
                'questions_asc',
                'questions_desc',
                'time_asc',
                'time_desc',
                'pass_mark_asc',
                'pass_mark_desc',
                'create_date_asc',
                'create_date_desc',
            ];
        @endphp
        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.filters') }}</label>
                <select class="form-control select2" id="sort" name="sort" data-minimum-results-for-search="Infinity">
                    <option value="all">{{ trans('public.all') }}</option>

                    @foreach($sorts as $sort)
                        <option value="{{ $sort }}">{{ trans("update.{$sort}") }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('filter') }}</button>
        </div>
    </div>
</form>
