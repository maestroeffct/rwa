<div class="card mt-20">
    <div class="card-body">
        <form action="{{ getAdminPanelUrl("/meeting-packages") }}" method="get" class="mb-0">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.search') }}</label>
                        <input name="search" type="text" class="form-control" value="{{ request()->get('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                        <div class="input-group">
                            <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                        <div class="input-group">
                            <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.creator') }}</label>
                        <select name="creator_ids[]" multiple="multiple" data-search-option="except_user"
                                class="form-control search-user-select2"
                                data-placeholder="Search users">

                            @if(!empty($creators) and $creators->count() > 0)
                                @foreach($creators as $creator)
                                    <option value="{{ $creator->id }}" selected>{{ $creator->full_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.role') }}</label>
                        <select name="role_id" class="form-control select2">
                            <option value="">{{ trans('admin/main.all') }}</option>

                            @if(!empty($roles) and $roles->count() > 0)
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ (request()->get('role_id') == $role->id) ? 'selected' : '' }}>{{ $role->caption }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.status') }}</label>
                        <select name="status" data-plugin-selectTwo class="form-control populate">
                            <option value="">{{ trans('admin/main.all_status') }}</option>
                            <option value="active" @if(request()->get('status') == 'active') selected @endif>{{ trans('admin/main.active') }}</option>
                            <option value="disabled" @if(request()->get('status') == 'disabled') selected @endif>{{ trans('admin/main.disabled') }}</option>
                        </select>
                    </div>
                </div>

                @php
                    $sorts = [
                        'sessions_asc',
                        'sessions_desc',
                        'price_asc',
                        'price_desc',
                        'created_date_asc',
                        'created_date_desc',
                    ];
                @endphp

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{ trans('admin/main.filters') }}</label>
                        <select name="sort" data-plugin-selectTwo class="form-control populate">
                            <option value="">{{ trans('admin/main.all') }}</option>

                            @foreach($sorts as $sort)
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
</div>
