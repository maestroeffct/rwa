@php
    $eventCategoryFilters = (!empty($event) and !empty($event->category)) ? $event->category->filters : null;
    $eventFilterOptions = (!empty($event) and !empty($event->category)) ? $event->filterOptions->pluck('filter_option_id')->toArray() : [];
@endphp

<h2 class="section-title after-line mt-24">{{ trans('public.category') }}</h2>

<div class="row">
    <div class="col-12 col-md-6">

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('public.category') }}</label>

            <select id="categories" class="custom-select @error('category_id')  is-invalid @enderror" name="category_id" required>
                <option {{ !empty($event) ? '' : 'selected' }} disabled>{{ trans('public.choose_category') }}</option>

                @foreach($categories as $category)
                    @if(!empty($category->subCategories) and count($category->subCategories))
                        <optgroup label="{{  $category->title }}">
                            @foreach($category->subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}" {{ (!empty($event) and $event->category_id == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                            @endforeach
                        </optgroup>
                    @else
                        <option value="{{ $category->id }}" {{ (!empty($event) and $event->category_id == $category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
                    @endif
                @endforeach
            </select>

            @error('category_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>


    </div>
</div>

<div class="form-group mt-16 {{ (!empty($eventCategoryFilters) and count($eventCategoryFilters)) ? '' : 'd-none' }}" id="categoriesFiltersContainer">
    <span class="input-label d-block">{{ trans('public.category_filters') }}</span>
    <div id="categoriesFiltersCard" class="row mt-16">

        @if(!empty($eventCategoryFilters) and count($eventCategoryFilters))
            @foreach($eventCategoryFilters as $filter)
                <div class="col-12 col-md-3">
                    <div class="webinar-category-filters">
                        <strong class="category-filter-title d-block mb-16">{{ $filter->title }}</strong>

                        @foreach($filter->options as $option)
                            <div class="form-group mt-8 mb-0 d-flex align-items-center justify-content-between">
                                <label class="text-gray-500 font-14 mb-0" for="filterOptions{{ $option->id }}">{{ $option->title }}</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="filters[]" value="{{ $option->id }}" {{ ((!empty($eventFilterOptions) && in_array($option->id, $eventFilterOptions)) ? 'checked' : '') }} class="custom-control-input" id="filterOptions{{ $option->id }}">
                                    <label class="custom-control-label" for="filterOptions{{ $option->id }}"></label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

    </div>
</div>
