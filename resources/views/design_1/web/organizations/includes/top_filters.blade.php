<div class="organizations-lists-top-filters position-relative mt-28">
    <div class="organizations-lists-top-filters__mask"></div>

    <div class="position-relative z-index-2 d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white p-12 rounded-24">
        <div class="form-group mb-0 d-flex align-items-center">
            <div class="custom-switch mr-8">
                <input id="top_filter_available_for_meetings" type="checkbox" name="available_for_meetings" value="on" class="custom-control-input">
                <label class="custom-control-label cursor-pointer" for="top_filter_available_for_meetings"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="top_filter_available_for_meetings">{{ trans("update.show_only_available_organizations") }}</label>
            </div>
        </div>

        <div class="form-group mb-0 mt-16 mt-lg-0" style="width: 200px">
            <select name="sort" class="form-control select2" data-minimum-results-for-search="Infinity">
                <option disabled selected>{{ trans('public.sort_by') }}</option>
                <option value="">{{ trans('public.all') }}</option>
                <option value="top_rate" @if(request()->get('sort') == 'top_rate') selected="selected" @endif>{{ trans('site.top_rate') }}</option>
                <option value="top_sale" @if(request()->get('sort') == 'top_sale') selected="selected" @endif>{{ trans('site.top_sellers') }}</option>
            </select>
        </div>
    </div>
</div>
