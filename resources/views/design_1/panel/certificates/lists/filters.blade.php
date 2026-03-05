<form action="" method="get" class="mt-24 px-16">

    <div class="row">
        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.search') }}</label>
                <input type="text" name="search" class="form-control" value="{{ request()->get('search') }}" placeholder="{{ trans('public.search') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('product.course') }}</label>
                <select name="course_type" class="form-control select2">
                    <option value="">{{ trans('public.all') }}</option>

                </select>
            </div>
        </div>

        @php
            $sortItems = [
                'generated_certificates_asc',
                'generated_certificates_desc',
                'last_certificate_date_asc',
                'last_certificate_date_desc',
            ];
        @endphp

        <div class="col-6 col-md-3">
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

        <div class="col-6 col-md-3">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('update.filter') }}</button>
        </div>
    </div>
</form>
