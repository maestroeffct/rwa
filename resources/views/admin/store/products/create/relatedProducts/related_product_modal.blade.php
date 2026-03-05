<div class="" id="relatedCourseModal">
    <h3 class="section-title after-line font-20 text-dark-blue mb-25">{{ trans('update.add_new_related_products') }}</h3>

    <div class="js-related-course-form" data-action="{{ getAdminPanelUrl("/relatedProducts/").(!empty($relatedProduct) ? $relatedProduct->id.'/update' : 'store') }}" >
        <input type="hidden" name="item_id" value="{{ $itemId }}">
        <input type="hidden" name="item_type" value="{{ $itemType }}">

        <div class="form-group mt-15">
            <label class="input-label d-block">{{ trans('update.select_related_product') }}</label>
            <select name="product_id" class="js-ajax-product_id form-control search-product-select2" data-placeholder="{{ trans('update.search_products') }}">
                @if(!empty($relatedProduct) and !empty($relatedProduct->product))
                    <option selected value="{{ $relatedProduct->product->id }}">{{ $relatedProduct->product->title .' - '. $relatedProduct->product->creator->full_name }}</option>
                @endif
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <div class="mt-4 d-flex align-items-center justify-content-end">
            <button type="button" id="saveRelateCourse" class="btn btn-primary size-100">{{ trans('public.save') }}</button>
            <button type="button" class="btn btn-danger ml-2 close-swl size-100">{{ trans('public.close') }}</button>
        </div>
    </div>
</div>
