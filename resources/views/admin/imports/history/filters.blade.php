<section class="card mt-32">
    <div class="card-body pb-4">
        <form method="get" class="mb-0">
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('update.data_type')}}</label>
                        <select name="data_type" data-plugin-selectTwo class="form-control populate">
                            <option value="">{{trans('admin/main.all')}}</option>

                            @foreach(['courses', 'categories', 'users', 'products'] as $dataType)
                                <option value="{{ $dataType }}" @if(request()->get('data_type') == $dataType) selected @endif>{{ trans('update.'.$dataType) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.user')}}</label>
                        <select name="user_ids[]" multiple="multiple" class="form-control search-user-select2" data-placeholder="Search users">

                            @if(!empty($selectedUsers) and $selectedUsers->count() > 0)
                                @foreach($selectedUsers as $user)
                                    <option value="{{ $user->id }}" selected>{{ $user->full_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('update.import_date')}}</label>
                        <div class="input-group">
                            <input type="date" id="import_date" class="text-center form-control" name="import_date" value="{{ request()->get('import_date') }}" placeholder="End Date">
                        </div>
                    </div>
                </div>


                <div class="col-md-3 d-flex align-items-center ">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                </div>
            </div>
        </form>
    </div>
</section>
