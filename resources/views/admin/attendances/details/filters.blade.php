<section class="card mt-32">
    <div class="card-body pb-4">
        <form method="get" class="mb-0">
            <div class="row">

                <div class="col-12 col-lg-3">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('public.search') }}</label>
                        <input type="text" name="search" class="form-control" value="{{ request()->get('search') }}" placeholder="{{ trans('update.search_in_students') }}">
                    </div>
                </div>

                <div class="col-12 col-lg-3">
                    <div class="form-group ">
                        <label class="form-group-label">{{ trans('update.attendance_status') }}</label>

                        <select name="status" class="form-control select2" data-minimum-results-for-search="Infinity">
                            <option value="">{{ trans('public.all') }}</option>

                            @foreach(['present','absent','late'] as $status)
                                <option value="{{ $status }}" {{ ($status == request()->get('status')) ? 'selected' : '' }}>{{ trans("update.{$status}") }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @php
                    $sortItems = [
                        'joined_date_asc',
                        'joined_date_desc',
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

                <div class="col-md-3 d-flex align-items-center ">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                </div>
            </div>
        </form>
    </div>
</section>
