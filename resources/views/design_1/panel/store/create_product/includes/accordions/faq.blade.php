<li data-id="{{ !empty($faq) ? $faq->id :'' }}" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="faq_{{ !empty($faq) ? $faq->id :'record' }}">
        <div class="font-weight-bold font-14 cursor-pointer" href="#collapseFaq{{ !empty($faq) ? $faq->id :'record' }}" data-parent="#faqsAccordion" role="button" data-toggle="collapse">
            <span>{{ !empty($faq) ? $faq->title : trans('update.new_faq') }}</span>
        </div>

        @if(!empty($faq))
            <div class="d-flex align-items-center">
                <span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 class="icons" width="18"/></span>

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/store/products/faqs/{{ $faq->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseFaq{{ !empty($faq) ? $faq->id :'record' }}" data-parent="#faqsAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="18"/>
                </span>
            </div>
        @endif

    </div>

    <div id="collapseFaq{{ !empty($faq) ? $faq->id :'record' }}" class="accordion__collapse {{ empty($faq) ? 'show' : '' }}" role="tabpanel">
        <div class="js-content-form js-faq-form mt-20" data-action="/panel/store/products/faqs/{{ !empty($faq) ? $faq->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][product_id]" value="{{ !empty($product) ? $product->id :'' }}">

            <div class="row">
                <div class="col-12 col-lg-12">

                    @include('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($faq) ? $faq : null,
                        'withoutReloadLocale' => true,
                        'extraClass' => 'js-upcoming-course-content-locale',
                        'extraData' => "data-upcoming-course-id='".(!empty($upcomingCourse) ? $upcomingCourse->id : '')."'  data-id='".(!empty($faq) ? $faq->id : '')."'  data-relation='faqs' data-fields='title,answer'"
                    ])

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.question') }}</label>

                        <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>

                        <input type="text" name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ !empty($faq) ? $faq->title : '' }}"/>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('public.answer') }}</label>

                        <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>

                        <textarea name="ajax[{{ !empty($faq) ? $faq->id : 'new' }}][answer]" class="js-ajax-answer form-control" rows="6">{{ !empty($faq) ? $faq->answer : '' }}</textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="mt-30 d-flex align-items-center">
                <button type="button" class="js-save-course-content btn btn-primary">{{ trans('public.save') }}</button>

                @if(!empty($faq))
                    <a href="/panel/store/products/faqs/{{ $faq->id }}/delete" class="delete-action btn btn-outline-danger ml-8 cancel-accordion">{{ trans('delete') }}</a>
                @endif
            </div>
        </div>
    </div>
</li>
