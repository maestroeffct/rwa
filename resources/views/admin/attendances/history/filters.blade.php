<section class="card mt-32">
    <div class="card-body pb-4">
        <form method="get" class="mb-0">
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.start_date')}}</label>
                        <div class="input-group">
                            <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.end_date')}}</label>
                        <div class="input-group">
                            <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.class') }}</label>
                        <select name="webinar_ids[]" multiple="multiple" class="form-control search-webinar-select2"
                                data-placeholder="Search classes">

                            @if(!empty($webinars) and $webinars->count() > 0)
                                @foreach($webinars as $webinar)
                                    <option value="{{ $webinar->id }}" selected>{{ $webinar->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.instructor') }}</label>
                        <select name="teacher_ids[]" multiple="multiple" data-search-option="just_teacher_role" class="form-control search-user-select2"
                                data-placeholder="{{ trans('update.search_instructor') }}">

                            @if(!empty($teachers) and $teachers->count() > 0)
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" selected>{{ $teacher->full_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.student') }}</label>
                        <select name="student_ids[]" multiple="multiple" data-search-option="just_student_role" class="form-control search-user-select2"
                                data-placeholder="{{ trans('webinars.select_student') }}">

                            @if(!empty($students) and $students->count() > 0)
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" selected>{{ $student->full_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('update.source')}}</label>
                        <select name="session_api" class="form-control">
                            <option value="">{{ trans('admin/main.all') }}</option>

                            @foreach(\App\Models\Session::$sessionApis as $sessionApi)
                                <option value="{{ $sessionApi }}" {{ request()->get('session_api') == $sessionApi ? 'selected' : '' }}>{{ trans('update.session_api_'.$sessionApi) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @php
                    $filters = ['amount_asc', 'amount_desc', 'submit_date_asc', 'submit_date_desc', 'receive_date_asc', 'receive_date_desc'];
                @endphp
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.filters')}}</label>
                        <select name="sort" data-plugin-selectTwo class="form-control populate">
                            <option value="">{{trans('admin/main.all')}}</option>

                            @foreach($filters as $filter)
                                <option value="{{ $filter }}" @if(request()->get('sort') == $filter) selected @endif>{{trans('update.'.$filter)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 d-flex align-items-center ">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                </div>
            </div>
        </form>
    </div>
</section>
