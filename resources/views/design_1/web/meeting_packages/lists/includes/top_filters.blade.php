<div class="position-relative meeting-packages-lists-filters">
    <div class="meeting-packages-lists-filters__mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between gap-20 bg-white px-24 py-12 rounded-24 z-index-2">
        <div class="d-flex align-items-center flex-wrap gap-48">
            @foreach(['free', 'discount'] as $topFilter1)
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

            <div class="meeting-packages-lists-sort-input form-group  mb-0">
                <select name="sort" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option disabled selected>{{ trans('public.sort_by') }}</option>
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach(['newest', 'oldest', 'highest_price', 'lowest_price', 'sessions_asc', 'sessions_desc', 'meeting_hours_asc', 'meeting_hours_desc'] as $filterSort)
                        <option value="{{ $filterSort }}" {{ (request()->get('sort') == $filterSort) ? 'selected' : '' }}>{{ trans("update.{$filterSort}") }}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>
</div>
