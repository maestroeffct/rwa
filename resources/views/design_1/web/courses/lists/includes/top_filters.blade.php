<div class="position-relative courses-lists-filters">
    <div class="courses-lists-filters__mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between gap-20 bg-white px-24 py-12 rounded-24 z-index-2">
        <div class="d-flex align-items-center flex-wrap gap-48">
            @foreach(['upcoming', 'free', 'discount', 'downloadable'] as $topFilter1)
                <div class="form-group mb-0 d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="top_filter_{{ $topFilter1 }}" type="checkbox" name="{{ $topFilter1 }}" value="on" class="custom-control-input">
                        <label class="custom-control-label cursor-pointer" for="top_filter_{{ $topFilter1 }}"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="top_filter_{{ $topFilter1 }}">{{ trans("update.{$topFilter1}") }}</label>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex align-items-center gap-16">

            <div class="courses-lists-sort-input form-group  mb-0">
                <select name="sort" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option disabled selected>{{ trans('public.sort_by') }}</option>
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach(['newest', 'expensive', 'inexpensive', 'bestsellers', 'best_rates'] as $filterSort)
                        <option value="{{ $filterSort }}" {{ (request()->get('sort') == $filterSort) ? 'selected' : '' }}>{{ trans("public.{$filterSort}") }}</option>
                    @endforeach
                </select>
            </div>

            <div class="courses-lists-card-view position-relative">
                <input type="radio" class="" name="card" id="card_grid_input" value="grid" {{ (request()->get("card", 'grid') == "grid") ? 'checked' : '' }}>
                <label for="card_grid_input" class="position-relative d-flex-center cursor-pointer">
                    <x-iconsax-lin-category class="icons" width="24px" height="24px"/>
                </label>
            </div>

            <div class="courses-lists-card-view position-relative">
                <input type="radio" class="" name="card" id="card_list_input" value="list" {{ (request()->get("card") == "list") ? 'checked' : '' }}>
                <label for="card_list_input" class="position-relative d-flex-center cursor-pointer">
                    <x-iconsax-lin-row-vertical class="icons" width="24px" height="24px"/>
                </label>
            </div>


        </div>
    </div>
</div>
