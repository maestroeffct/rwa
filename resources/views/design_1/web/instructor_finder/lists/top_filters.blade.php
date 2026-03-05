<div class="instructor-finder__top-filters position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white p-12 rounded-24">
    <div class="d-flex flex-wrap align-items-center gap-20 gap-lg-48 px-12 py-10">

        <div class="form-group d-flex align-items-center mb-0">
            <div class="custom-switch mr-8">
                <input id="available_for_meetings" type="checkbox" name="available_for_meetings" class="custom-control-input" {{ (request()->get('available_for_meetings', null) == 'on') ? 'checked="checked"' : '' }}>
                <label class="custom-control-label cursor-pointer" for="available_for_meetings"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="available_for_meetings">{{ trans('public.available_for_meetings') }}</label>
            </div>
        </div>


        <div class="form-group d-flex align-items-center mb-0">
            <div class="custom-switch mr-8">
                <input id="free_meetings" type="checkbox" name="free_meetings" class="custom-control-input" {{ (request()->get('free_meetings', null) == 'on') ? 'checked="checked"' : '' }}>
                <label class="custom-control-label cursor-pointer" for="free_meetings"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="free_meetings">{{ trans('public.free_meetings') }}</label>
            </div>
        </div>

        <div class="form-group d-flex align-items-center mb-0">
            <div class="custom-switch mr-8">
                <input id="discount" type="checkbox" name="discount" class="custom-control-input" {{ (request()->get('discount', null) == 'on') ? 'checked="checked"' : '' }}>
                <label class="custom-control-label cursor-pointer" for="discount"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="discount">{{ trans('public.discount') }}</label>
            </div>
        </div>

    </div>

    <div class="form-group  mb-0 mt-20 mt-lg-0" style="width: 200px">
        <select name="sort" class="form-control select2" data-minimum-results-for-search="Infinity">
            <option disabled selected>{{ trans('public.sort_by') }}</option>
            <option value="">{{ trans('public.all') }}</option>
            <option value="top_rate" @if(request()->get('sort',null) == 'top_rate') selected="selected" @endif>{{ trans('site.top_rate') }}</option>
            <option value="top_sale" @if(request()->get('sort',null) == 'top_sale') selected="selected" @endif>{{ trans('site.top_sellers') }}</option>
        </select>
    </div>
</div>
