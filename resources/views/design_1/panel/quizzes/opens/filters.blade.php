<form action="" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('quiz.quiz_or_webinar') }}</label>
                <select name="quiz_id" class="form-control select2" data-placeholder="{{ trans('public.all') }}">
                    <option value="all">{{ trans('public.all') }}</option>

                    @foreach($allQuizzesLists as $allQuiz)
                        <option value="{{ $allQuiz->id }}">{{ $allQuiz->title .' - '. ($allQuiz->webinar ? $allQuiz->webinar->title : '-') }}</option>
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
