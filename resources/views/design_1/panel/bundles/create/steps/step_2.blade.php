@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">
    <h3 class="font-14 font-weight-bold">{{ trans('update.taxonomy') }}</h3>

    <div class="form-group  mt-24">
        <label class="form-group-label is-required">{{ trans('public.category') }}</label>

        <select name="category_id" id="categories" class="select2 @error('category_id')  is-invalid @enderror">
            <option {{ (!empty($bundle) and !empty($bundle->category_id)) ? '' : 'selected' }} disabled>{{ trans('public.choose_category') }}</option>
            @foreach($categories as $category)
                @if(!empty($category->subCategories) and $category->subCategories->count() > 0)
                    <optgroup label="{{  $category->title }}">
                        @foreach($category->subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}" {{ ((!empty($bundle) and $bundle->category_id == $subCategory->id) or old('category_id') == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                        @endforeach
                    </optgroup>
                @else
                    <option value="{{ $category->id }}" {{ ((!empty($bundle) and $bundle->category_id == $category->id) or old('category_id') == $category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
                @endif
            @endforeach
        </select>

        @error('category_id')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="mt-24 {{ (!empty($bundleCategoryFilters) and count($bundleCategoryFilters)) ? '' : 'd-none' }}" id="categoriesFiltersContainer">
        <h3 class="font-14 font-weight-bold">{{ trans('public.category_filters') }}</h3>

        <div id="categoriesFiltersCard" class="row">
            @if(!empty($bundleCategoryFilters) and count($bundleCategoryFilters))
                @foreach($bundleCategoryFilters as $filter)
                    <div class="col-12 col-md-3 mt-16">
                        <div class="create-course-filter-card bg-white p-16 rounded-12 border-gray-200">
                            <h5 class="font-14 font-weight-bold mb-16">{{ $filter->title }}</h5>

                            @php
                                $bundleFilterOptions = $bundle->filterOptions->pluck('filter_option_id')->toArray();

                                if (!empty(old('filters'))) {
                                    $bundleFilterOptions = array_merge($bundleFilterOptions, old('filters'));
                                }
                            @endphp

                            @foreach($filter->options as $option)
                                <div class="custom-control custom-checkbox {{ $loop->first ? '' : 'mt-12' }}">
                                    <input type="checkbox" name="filters[]" value="{{ $option->id }}" id="filterOptions{{ $option->id }}" class="custom-control-input" {{ ((!empty($bundleFilterOptions) && in_array($option->id, $bundleFilterOptions)) ? 'checked' : '') }}>
                                    <label class="custom-control__label cursor-pointer" for="filterOptions{{ $option->id }}">{{ $option->title }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="form-group tagsinput-bg-white mt-15">
        <label class="form-group-label d-block">{{ trans('public.tags') }}</label>
        <input type="text" name="tags" data-max-tag="5" value="{{ !empty($bundle) ? implode(',',$bundleTags) : '' }}" class="form-control inputtags" placeholder="{{ trans('public.type_tag_name_and_press_enter') }} ({{ trans('forms.max') }} : 5)"/>
    </div>


</div>

@push('scripts_bottom')
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
@endpush
