<div class="form-group select2-bg-white">
    <label class="form-group-label bg-white">{{ trans('update.assign_a_category') }}</label>

    <select name="contents[trending_categories][{{ !empty($itemKey) ? $itemKey : 'record' }}][category]" class="{{ !empty($itemKey) ? 'js-make-select2' : 'js-make-select2-item' }} form-control bg-white" >
        <option value="">{{ trans('public.choose_category') }}</option>

        @if(!empty($trendingCategories) and count($trendingCategories))
            @foreach($trendingCategories as $trendingCategory)
                <option value="{{ $trendingCategory->id }}" {{ (!empty($selectedTrendingCategoryItem) and $selectedTrendingCategoryItem->id == $trendingCategory->id) ? 'selected' : '' }}>{{ $trendingCategory->category->title }}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>
