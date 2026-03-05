<section class="card mt-32">
    <div class="card-body pb-4">
        <form method="get" class="mb-0">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.search')}}</label>
                        <input type="text" class="form-control" name="search" placeholder="" value="{{ request()->get('search') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.start_date')}}</label>
                        <div class="input-group">
                            <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.end_date')}}</label>
                        <div class="input-group">
                            <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.class')}}</label>
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

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.status')}}</label>
                        <select name="status" class="form-control populate">
                            <option value="">{{trans('admin/main.all_status')}}</option>
                            <option value="active" @if(request()->get('status') == 'active') selected @endif>{{trans('admin/main.published')}}</option>
                            <option value="pending" @if(request()->get('status') == 'pending') selected @endif>{{trans('admin/main.hidden')}}</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2 d-flex align-items-center ">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                </div>

            </div>
        </form>
    </div>
</section>
