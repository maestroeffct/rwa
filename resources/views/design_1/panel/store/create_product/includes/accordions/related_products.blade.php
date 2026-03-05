<li data-id="{{ !empty($relatedProduct) ? $relatedProduct->id :'' }}" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="relatedProduct_{{ !empty($relatedProduct) ? $relatedProduct->id :'record' }}">
        <div class="font-weight-bold font-14 cursor-pointer" href="#collapseRelatedProduct{{ !empty($relatedProduct) ? $relatedProduct->id :'record' }}" data-parent="#relatedProductsAccordion" role="button" data-toggle="collapse">
            <span>{{ (!empty($relatedProduct) and !empty($relatedProduct->product)) ? $relatedProduct->product->title .' - '. $relatedProduct->product->creator->full_name : trans('update.add_related_products') }}</span>
        </div>

        @if(!empty($relatedProduct))
            <div class="d-flex align-items-center">
                <span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 class="icons" width="18"/></span>

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/relatedProducts/{{ $relatedProduct->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseRelatedProduct{{ !empty($relatedProduct) ? $relatedProduct->id :'record' }}" data-parent="#relatedProductsAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="18"/>
                </span>
            </div>
        @endif

    </div>

    <div id="collapseRelatedProduct{{ !empty($relatedProduct) ? $relatedProduct->id :'record' }}" class="accordion__collapse {{ empty($relatedProduct) ? 'show' : '' }}" role="tabpanel">
        <div class="js-content-form js-relatedProduct-form" data-action="/panel/relatedProducts/{{ !empty($relatedProduct) ? $relatedProduct->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($relatedProduct) ? $relatedProduct->id : 'new' }}][item_id]" value="{{ $product->id }}">
            <input type="hidden" name="ajax[{{ !empty($relatedProduct) ? $relatedProduct->id : 'new' }}][item_type]" value="product">

            <div class="form-group mt-20">
                <label class="form-group-label">{{ trans('update.select_related_product') }}</label>

                <select name="ajax[{{ !empty($relatedProduct) ? $relatedProduct->id : 'new' }}][product_id]" class="js-ajax-product_id form-control searchable-select bg-white" data-allow-clear="false" data-placeholder="{{ trans('update.search_products') }}"
                        data-api-path="/panel/store/products/search"
                        data-item-column-name="title"
                        data-option=""
                        data-item-id="{{ $product->id }}"
                >
                    @if(!empty($relatedProduct) and !empty($relatedProduct->product))
                        <option selected value="{{ $relatedProduct->product->id }}">{{ $relatedProduct->product->title .' - '. $relatedProduct->product->creator->full_name }}</option>
                    @endif
                </select>
                <div class="invalid-feedback"></div>
            </div>


            <div class="mt-30 d-flex align-items-center">
                <button type="button" class="js-save-course-content btn btn-primary">{{ trans('public.save') }}</button>

                @if(!empty($relatedProduct))
                    <a href="/panel/relatedProducts/{{ $relatedProduct->id }}/delete" class="delete-action btn btn-outline-danger ml-8 cancel-accordion">{{ trans('delete') }}</a>
                @endif
            </div>
        </div>
    </div>
</li>
