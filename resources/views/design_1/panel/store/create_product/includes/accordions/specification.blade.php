<li data-id="{{ !empty($selectedSpecification) ? $selectedSpecification->id :'' }}" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="specification_{{ !empty($selectedSpecification) ? $selectedSpecification->id :'record' }}">
        <div class="font-weight-bold font-14 cursor-pointer" href="#collapseSpecification{{ !empty($selectedSpecification) ? $selectedSpecification->id :'record' }}" data-parent="#specificationsAccordion" role="button" data-toggle="collapse">
            <span>{{ !empty($selectedSpecification) ? $selectedSpecification->specification->title : trans('update.add_new_specification') }}</span>
        </div>


        @if(!empty($selectedSpecification))
            <div class="d-flex align-items-center">

                @if($selectedSpecification->status != 'active')
                    <span class="px-8 py-4 bg-danger-20 text-danger font-12 mr-12 rounded-8">{{ trans('public.disabled') }}</span>
                @endif

                <span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 class="icons" width="18"/></span>

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/store/products/specifications/{{ $selectedSpecification->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseSpecification{{ !empty($selectedSpecification) ? $selectedSpecification->id :'record' }}" data-parent="#specificationsAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="18"/>
                </span>
            </div>
        @endif

    </div>

    <div id="collapseSpecification{{ !empty($selectedSpecification) ? $selectedSpecification->id :'record' }}" class="accordion__collapse {{ empty($selectedSpecification) ? 'show' : '' }}" role="tabpanel">
        <div class="js-content-form specification-form" data-action="/panel/store/products/specifications/{{ !empty($selectedSpecification) ? $selectedSpecification->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][product_id]" value="{{ !empty($product) ? $product->id :'' }}">
            <input type="hidden" class="js-input-type" name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][input_type]" value="{{ !empty($selectedSpecification) ? $selectedSpecification->type :'' }}">

            <div class="mt-20">
                @include('design_1.panel.includes.locale.locale_select',[
                    'itemRow' => !empty($file) ? $file : null,
                    'withoutReloadLocale' => true,
                    'extraClass' => 'js-product-content-locale',
                    'extraData' => "data-product-id='".(!empty($product) ? $product->id : '')."'  data-id='".(!empty($selectedSpecification) ? $selectedSpecification->id : '')."'  data-relation='selectedSpecifications' data-fields='value'"
                ])
            </div>

            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.specification') }}</label>

                <select name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][specification_id]"
                        class="js-ajax-specification_id form-control search-specification-select2"
                        data-placeholder="{{ trans('update.search_and_select_specifications') }}"
                        data-allow-clear="false"
                        data-category="{{ !empty($product) ? $product->category_id : '' }}"
                    {{ !empty($selectedSpecification) ? 'disabled' : '' }}
                >

                    @if(!empty($productSpecifications))
                        <option value="">{{ trans('update.search_and_select_specifications') }}</option>
                        @foreach($productSpecifications as $productSpecification)
                            <option value="{{ $productSpecification->id }}" {{ (!empty($selectedSpecification) and $selectedSpecification->product_specification_id == $productSpecification->id) ? 'selected' : '' }}>{{ $productSpecification->title }}</option>
                        @endforeach
                    @elseif(!empty($selectedSpecification))
                        <option value="{{ $selectedSpecification->specification->id }}" selected>{{ $selectedSpecification->specification->title }}</option>
                    @endif
                </select>
                <div class="invalid-feedback"></div>

                @if(!empty($selectedSpecification))
                    <input type="hidden" name="ajax[{{ $selectedSpecification->id }}][specification_id]" value="{{ $selectedSpecification->specification->id }}">
                @endif
            </div>

            <div class="js-specification-extra-fields-loading d-none">
                <div class="d-flex align-items-center justify-content-center w-100 my-40">
                    <img src="/assets/design_1/img/loading.gif" width="56" height="56">
                </div>
            </div>

            <div class="js-specification-extra-fields">

                <div class="form-group  js-multi-values-input  {{ (!empty($selectedSpecification) and $selectedSpecification->type == 'multi_value') ? '' : 'd-none' }}">
                    <label class="form-group-label">{{ trans('update.parameters') }}</label>

                    @php
                        $selectedMultiValues = [];

                        if (!empty($selectedSpecification)) {
                            $selectedMultiValues = $selectedSpecification->selectedMultiValues->pluck('specification_multi_value_id')->toArray();
                        }
                    @endphp

                    <select name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][multi_values][]"
                            class="js-ajax-multi_values form-control {{ !empty($selectedSpecification) ? 'select-multi-values-select2' : 'multi_values-select2' }}"
                            multiple
                            data-placeholder="{{ trans('update.select_specification_params') }}"
                            data-allow-clear="false"
                            data-search="false"
                    >

                        @if(!empty($selectedSpecification->specification) and !empty($selectedSpecification->specification->multiValues))
                            @foreach($selectedSpecification->specification->multiValues as $multiValue)
                                <option value="{{ $multiValue->id }}" {{ in_array($multiValue->id,$selectedMultiValues) ? 'selected' : '' }}>{{ $multiValue->title }}</option>
                            @endforeach
                        @endif
                    </select>

                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group js-summery-input {{ (!empty($selectedSpecification) and $selectedSpecification->type == 'textarea') ? '' : 'd-none' }}">
                    <label class="form-group-label">{{ trans('update.product_summary') }}</label>
                    <textarea name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][summary]" rows="4" class="js-ajax-summary form-control ">{{ (!empty($selectedSpecification) and $selectedSpecification->type == 'textarea') ? $selectedSpecification->value : '' }}</textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group js-allow-selection-input {{ (!empty($selectedSpecification) and $selectedSpecification->type == 'multi_value') ? '' : 'd-none' }}">
                    <div class="d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="specificationAllowSelectionSwitch{{ !empty($selectedSpecification) ? $selectedSpecification->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][allow_selection]" class="custom-control-input" {{ (!empty($selectedSpecification) and $selectedSpecification->allow_selection) ? 'checked' : ''  }}>
                            <label class="custom-control-label cursor-pointer" for="specificationAllowSelectionSwitch{{ !empty($selectedSpecification) ? $selectedSpecification->id : '_record' }}"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="specificationAllowSelectionSwitch{{ !empty($selectedSpecification) ? $selectedSpecification->id : '_record' }}">{{ trans('update.allow_user_selection') }}</label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-group d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="specificationStatusSwitch{{ !empty($selectedSpecification) ? $selectedSpecification->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($selectedSpecification) ? $selectedSpecification->id : 'new' }}][status]" class="custom-control-input" {{ (empty($selectedSpecification) or $selectedSpecification->status == \App\Models\ProductSelectedSpecification::$Active) ? 'checked' : ''  }}>
                    <label class="custom-control-label cursor-pointer" for="specificationStatusSwitch{{ !empty($selectedSpecification) ? $selectedSpecification->id : '_record' }}"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="specificationStatusSwitch{{ !empty($selectedSpecification) ? $selectedSpecification->id : '_record' }}">{{ trans('public.active') }}</label>
                </div>
            </div>


            <div class="mt-28 d-flex align-items-center">
                <button type="button" class="js-save-course-content btn btn-primary">{{ trans('public.save') }}</button>

                @if(!empty($selectedSpecification))
                    <a href="/panel/store/products/specifications/{{ $selectedSpecification->id }}/delete" class="delete-action btn btn-outline-danger ml-8 cancel-accordion">{{ trans('delete') }}</a>
                @endif
            </div>
        </div>
    </div>
</li>
