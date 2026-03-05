<form action="/panel/rewards" method="get" class="px-16 mt-16">
    <div class="row mt-24">

        <div class="col-6 col-md-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-400" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.from') }}</label>
                <input type="text" name="from" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('from') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-400" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.to') }}</label>
                <input type="text" name="to" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('to') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.type') }}</label>
                <select name="status" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value="all">{{ trans('public.all') }}</option>

                    <option value="addiction" {{ (request()->get('status') == "addiction") ? 'selected' : '' }}>{{ trans('update.earn') }}</option>
                    <option value="deduction" {{ (request()->get('status') == "deduction") ? 'selected' : '' }}>{{ trans('update.redeem') }}</option>
                </select>
            </div>
        </div>

        <div class="col-6 col-md-3 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('update.filter') }}</button>
        </div>
    </div>
</form>
