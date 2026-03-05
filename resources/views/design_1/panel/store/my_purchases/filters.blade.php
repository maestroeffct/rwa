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
            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.seller') }}</label>

                <select name="seller_id" class="form-control select2" data-allow-clear="false">
                    <option value="all">{{ trans('public.all') }}</option>

                    @foreach($sellers as $seller)
                        <option value="{{ $seller->id }}" @if(request()->get('seller_id',null) == $seller->id) selected @endif>{{ $seller->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.type') }}</label>
                <select class="form-control select2" id="type" name="type" data-minimum-results-for-search="Infinity">
                    <option value="" >{{ trans('public.all') }}</option>

                    @foreach(\App\Models\Product::$productTypes as $productType)
                        <option value="{{ $productType }}">{{ trans('update.product_type_'.$productType) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('update.order_id') }}</label>
                <input type="number" name="order_id" class="form-control" placeholder="{{ trans('update.search_order_by_id') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.status') }}</label>
                <select class="form-control select2" id="status" name="status" data-minimum-results-for-search="Infinity">
                    <option value="" >{{ trans('public.all') }}</option>

                    @foreach(\App\Models\ProductOrder::$status as $orderStatus)
                        @if($orderStatus != 'pending')
                            <option value="{{ $orderStatus }}" >{{ trans('update.product_order_status_'.$orderStatus) }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        @php
            $sortItems = [
                'price_asc',
                'price_desc',
                'quantity_asc',
                'quantity_desc',
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
