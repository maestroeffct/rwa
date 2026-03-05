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

        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('panel.user') }}</label>
                <select name="user_id" class="form-control select2">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($allUsersLists as $allUserList)
                        <option value="{{ $allUserList->id }}">{{ $allUserList->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-6 col-md-3">
            <div class="form-group">
                <div class="form-group">
                    <label class="form-group-label">{{ trans('update.product') }}</label>
                    <select name="product_id" class="form-control select2">
                        <option value="">{{ trans('public.all') }}</option>

                        @foreach($allProductsLists as $allProductList)
                            <option value="{{ $allProductList->id }}">{{ $allProductList->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.search') }}</label>
                <input type="text" name="search" class="form-control" placeholder="{{ trans('update.search_on_comment') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.product_type') }}</label>
                <select name="product_type" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach(\App\Models\Product::$productTypes as $productType)
                        <option value="{{ $productType }}">{{ trans('update.'.$productType) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @php
            $sortItems = [
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
