<form action="/panel/marketing/discounts" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-6 col-md-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.from') }}</label>
                <input type="text" name="from" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('from') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.to') }}</label>
                <input type="text" name="to" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('to') }}">
            </div>
        </div>

        <div class="col-6 col-md-2">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.source') }}</label>

                <select name="source" class="form-control select2">
                    @foreach(\App\Models\Discount::$panelDiscountSource as $source)
                        <option value="{{ $source }}" {{ (request()->get('source') == $source) ? 'selected' : '' }}>{{ trans('update.discount_source_'.$source) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-6 col-md-2">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('admin/main.status') }}</label>

                <select name="status" class="form-control select2">
                    <option value="">{{ trans('public.all') }}</option>
                    <option value="active" {{ (request()->get('status') == "active") ? 'selected' : '' }}>{{ trans('public.active') }}</option>
                    <option value="expired" {{ (request()->get('status') == "expired") ? 'selected' : '' }}>{{ trans('panel.expired') }}</option>
                </select>
            </div>
        </div>

        <div class="col-6 col-md-2 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('update.filter') }}</button>
        </div>
    </div>
</form>
