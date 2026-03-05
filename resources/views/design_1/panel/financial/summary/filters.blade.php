<form action="" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.from') }}</label>
                <input type="text" name="from" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('from') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.to') }}</label>
                <input type="text" name="to" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('to') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.search') }}</label>
                <input type="text" name="search" class="form-control" value="{{ request()->get('search') }}" placeholder="{{ trans('update.search_title,_description,...') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.type') }}</label>

                <select name="type" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('public.all') }}</option>
                    <option value="addiction">{{ trans('admin/main.addiction') }}</option>
                    <option value="deduction">{{ trans('admin/main.deduction') }}</option>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.creator') }}</label>

                <select name="store_type" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('public.all') }}</option>
                    <option value="automatic">{{ trans('public.automatic') }}</option>
                    <option value="manual">{{ trans('public.manual') }}</option>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('admin/main.type_account') }}</label>

                <select name="type_account" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach(['income','asset','subscribe','promotion','registration_package','installment_payment'] as $typeAccount)
                        <option value="{{ $typeAccount }}">{{ trans("update.{$typeAccount}") }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @php
            $sortItems = [
                'price_asc',
                'price_desc',
                'create_date_asc',
                'create_date_desc',
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

        <div class="col-12 col-lg-3 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('filter') }}</button>
        </div>
    </div>
</form>
