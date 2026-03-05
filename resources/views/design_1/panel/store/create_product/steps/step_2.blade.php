@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush

<div class="bg-white rounded-16 p-16 mt-32">

    {{-- Pricing Options --}}
    <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.pricing_options') }}</h3>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.price') }}</label>
        <span class="has-translation bg-gray-100 text-gray-500">{{ $currency }}</span>
        <input type="text" name="price" class="form-control @error('price')  is-invalid @enderror" value="{{ (!empty($product) and !empty($product->price)) ? convertPriceToUserCurrency($product->price) : old('price') }}" placeholder="{{ trans('public.0_for_free') }}" oninput="validatePrice(this)"/>
        <div class="invalid-feedback d-block">@error('price') {{ $message }} @enderror</div>
    </div>

    @if($product->isPhysical())
        <div class="form-group">
            <label class="form-group-label">{{ trans('update.delivery_fee') }}</label>
            <span class="has-translation bg-gray-100 text-gray-500">{{ $currency }}</span>
            <input type="text" name="delivery_fee" value="{{ (!empty($product) and !empty($product->delivery_fee)) ? convertPriceToUserCurrency($product->delivery_fee) : old('delivery_fee') }}" class="form-control @error('delivery_fee')  is-invalid @enderror" placeholder="{{ trans('public.0_for_free') }}" oninput="validatePrice(this)"/>
            @error('delivery_fee')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('update.delivery_estimated_time') }}</label>
            <span class="has-translation bg-gray-100 text-gray-500">{{ trans('public.day') }}</span>
            <input type="number" name="delivery_estimated_time" value="{{ !empty($product) ? $product->delivery_estimated_time : old('delivery_estimated_time') }}" class="form-control @error('delivery_estimated_time')  is-invalid @enderror" placeholder=""/>
            @error('delivery_estimated_time')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    @endif

    <div class="form-group js-inventory-inputs {{ (!empty($product) and $product->unlimited_inventory) ? 'd-none' : '' }}">
        <label class="form-group-label is-required">{{ trans('update.inventory') }}</label>
        <input type="number" name="inventory" value="{{ (!empty($product) and $product->getAvailability() != 99999) ? $product->getAvailability() : old('inventory') }}" class="form-control @error('inventory')  is-invalid @enderror" placeholder=""/>
        @error('inventory')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group js-inventory-inputs {{ (!empty($product) and $product->unlimited_inventory) ? 'd-none' : '' }}">
        <label class="form-group-label">{{ trans('update.inventory_warning') }}</label>
        <input type="number" name="inventory_warning" value="{{ !empty($product) ? $product->inventory_warning : old('inventory_warning') }}" class="form-control @error('inventory_warning')  is-invalid @enderror" placeholder=""/>
        @error('inventory_warning')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <div class="d-flex align-items-center">
            <div class="custom-switch mr-8">
                <input id="unlimitedInventorySwitch" type="checkbox" name="unlimited_inventory" class="custom-control-input" {{ (!empty($product) and $product->unlimited_inventory) ? 'checked' :  '' }}>
                <label class="custom-control-label cursor-pointer" for="unlimitedInventorySwitch"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="unlimitedInventorySwitch">{{ trans('update.unlimited_inventory') }}</label>
            </div>
        </div>

        <p class="text-gray-500 font-12 mt-8">{{ trans('update.create_product_unlimited_inventory_hint') }}</p>
    </div>



    <h3 class="font-14 font-weight-bold my-24">{{ trans('update.taxonomy') }}</h3>

    <div class="form-group ">
        <label class="form-group-label is-required">{{ trans('public.category') }}</label>

        <select name="category_id" id="productCategories" class="select2 @error('category_id')  is-invalid @enderror">
            <option {{ (!empty($product) and !empty($product->category_id)) ? '' : 'selected' }} disabled>{{ trans('public.choose_category') }}</option>
            @foreach($productCategories as $productCategory)
                @if(!empty($productCategory->subCategories) and $productCategory->subCategories->count() > 0)
                    <optgroup label="{{  $productCategory->title }}">
                        @foreach($productCategory->subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}" {{ ((!empty($product) and $product->category_id == $subCategory->id) or old('category_id') == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                        @endforeach
                    </optgroup>
                @else
                    <option value="{{ $productCategory->id }}" {{ ((!empty($product) and $product->category_id == $productCategory->id) or old('category_id') == $productCategory->id) ? 'selected' : '' }}>{{ $productCategory->title }}</option>
                @endif
            @endforeach
        </select>

        @error('category_id')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="mt-24 {{ (!empty($productCategoryFilters) and count($productCategoryFilters)) ? '' : 'd-none' }}" id="categoriesFiltersContainer">
        <h3 class="font-14 font-weight-bold">{{ trans('public.category_filters') }}</h3>

        <div id="categoriesFiltersCard" class="row">
            @if(!empty($productCategoryFilters) and count($productCategoryFilters))
                @foreach($productCategoryFilters as $filter)
                    <div class="col-12 col-md-3 mt-16">
                        <div class="create-course-filter-card bg-white p-16 rounded-12 border-gray-200">
                            <h5 class="font-14 font-weight-bold mb-16">{{ $filter->title }}</h5>

                            @php
                                $productFilterOptions = $product->selectedFilterOptions->pluck('filter_option_id')->toArray();

                                if (!empty(old('filters'))) {
                                    $productFilterOptions = array_merge($productFilterOptions, old('filters'));
                                }
                            @endphp

                            @foreach($filter->options as $option)
                                <div class="custom-control custom-checkbox {{ $loop->first ? '' : 'mt-12' }}">
                                    <input type="checkbox" name="filters[]" value="{{ $option->id }}" id="filterOptions{{ $option->id }}" class="custom-control-input" {{ ((!empty($productFilterOptions) && in_array($option->id, $productFilterOptions)) ? 'checked' : '') }}>
                                    <label class="custom-control__label cursor-pointer" for="filterOptions{{ $option->id }}">{{ $option->title }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>


    {{-- Related Products --}}
    <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed mt-32">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-video-tick class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.related_products') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.display_related_products_on_the_product_page') }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            @include('design_1.panel.store.create_product.includes.accordions.related_products')
        </div>

        <div class="col-lg-6 mt-16">
            @if(!empty($product->relatedProducts) and count($product->relatedProducts))
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold">{{ trans('update.related_products') }}</h3>

                    <ul class="draggable-content-lists related_products-draggable-lists" data-path="" data-drag-class="related_products-draggable-lists">
                        @foreach($product->relatedProducts as $relatedProductInfo)
                            @include('design_1.panel.store.create_product.includes.accordions.related_products',['relatedProduct' => $relatedProductInfo])
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="d-flex-center flex-column px-32 py-120 text-center rounded-16 border-gray-200">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-arrange-circle-2 class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.related_products_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('update.related_products_no_result_hint') !!}</p>
                </div>
            @endif

        </div>
    </div>

    {{-- Related Courses --}}
    <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed mt-32">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-video-tick class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.related_courses') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.display_related_courses_on_the_product_page') }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            @include('design_1.panel.store.create_product.includes.accordions.related_courses')
        </div>

        <div class="col-lg-6 mt-16">
            @if(!empty($product->relatedCourses) and count($product->relatedCourses))
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold">{{ trans('update.related_courses') }}</h3>

                    <ul class="draggable-content-lists related_courses-draggable-lists" data-path="" data-drag-class="related_courses-draggable-lists">
                        @foreach($product->relatedCourses as $relatedCourseInfo)
                            @include('design_1.panel.store.create_product.includes.accordions.related_courses',['relatedCourse' => $relatedCourseInfo])
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="d-flex-center flex-column px-32 py-120 text-center rounded-16 border-gray-200">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-arrange-circle-2 class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.related_courses_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('update.related_courses_no_result_hint') !!}</p>
                </div>
            @endif

        </div>
    </div>

</div>

@push('scripts_bottom')
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

@endpush
