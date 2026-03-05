<section class="card mt-32">
    <div class="card-body pb-4">
        <form method="get" class="mb-0">

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.search')}}</label>
                        <input name="search" type="text" class="form-control" value="{{ request()->get('search') }}">
                    </div>
                </div>

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
                        <label class="input-label">{{trans('admin/main.instructor')}}</label>
                        <select name="instructor_ids[]" multiple="multiple" data-search-option="just_teacher_role" class="form-control search-user-select2"
                                data-placeholder="{{ trans('public.search_instructors') }}">

                            @if(!empty($instructors) and $instructors->count() > 0)
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" selected>{{ $instructor->full_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('update.event_type')}}</label>
                        <select name="type" data-plugin-selectTwo class="form-control populate">
                            <option value="">{{trans('admin/main.all')}}</option>

                            @foreach(['in_person', 'online'] as $type)
                                <option value="{{ $type }}" {{ (request()->get('type') == $type) ? 'selected' : '' }}>{{ trans("update.{$type}") }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('public.status')}}</label>
                        <select name="status" data-plugin-selectTwo class="form-control populate">
                            <option value="">{{trans('admin/main.all_status')}}</option>

                            @foreach(['publish','draft','pending','unpublish','canceled'] as $status)
                                <option value="{{ $status }}" {{ (request()->get('status') == $status) ? 'selected' : '' }}>{{ trans("update.{$status}") }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.filters')}}</label>
                        <select name="sort" data-plugin-selectTwo class="form-control populate">
                            <option value="">{{ trans('public.all') }}</option>

                            @foreach(['create_date_asc', 'create_date_desc', 'highest_base_price', 'lowest_base_price', 'best_sellers', 'top_rated'] as $sort)
                                <option value="{{ $sort }}" {{ (request()->get('sort') == $sort) ? 'selected' : '' }}>{{ trans("update.{$sort}") }}</option>
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
