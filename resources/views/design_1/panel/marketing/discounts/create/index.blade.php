@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush


@section('content')
    <form action="/panel/marketing/discounts/{{ !empty($discount) ? $discount->id.'/update' : 'store' }}" method="post" class="">
        {{ csrf_field() }}


        <div class="row pb-140 pb-lg-60">
            <div class="col-12 col-lg-6">
                <div class="bg-white p-16 rounded-24">
                    <h3 class="font-16 mb-28">{{ !empty($discount) ? trans('update.edit_coupon') : trans('update.new_coupon') }}</h3>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('public.title') }}</label>
                        <input type="text" name="title" class="js-ajax-title form-control @error('title') is-invalid @enderror" value="{{ !empty($discount) ? $discount->title : old('title') }}"/>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <p class="font-12 text-gray-500 mt-8">{{ trans('update.this_title_will_be_displayed_on_the_product_or_profile_page') }}</p>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.subtitle') }}</label>
                        <input type="text" name="subtitle" class="js-ajax-subtitle form-control @error('subtitle') is-invalid @enderror" value="{{ !empty($discount) ? $discount->subtitle : old('subtitle') }}"/>
                        @error('subtitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <p class="font-12 text-gray-500 mt-8">{{ trans('update.this_subtitle_will_be_displayed_on_the_product_or_profile_page') }}</p>
                    </div>

                    <div class="form-group ">
                        <label class="form-group-label">{{ trans('update.discount_type') }}</label>
                        <select name="discount_type" class="js-discount-type form-control select2 @error('discount_type') is-invalid @enderror" data-minimum-results-for-search="Infinity">
                            <option value="percentage" {{ (empty($discount) or (!empty($discount) and $discount->discount_type == 'percentage')) ? 'selected' : '' }}>{{ trans('admin/main.percentage') }}</option>
                            <option value="fixed_amount" {{ (!empty($discount) and $discount->discount_type == 'fixed_amount') ? 'selected' : '' }}>{{ trans('update.fixed_amount') }}</option>
                        </select>
                        <div class="invalid-feedback">@error('discount_type') {{ $message }} @enderror</div>
                    </div>

                    <div class="form-group ">
                        <label class="form-group-label">{{ trans('update.source') }}</label>
                        <select name="source" class="js-discount-source form-control select2 @error('source') is-invalid @enderror" data-minimum-results-for-search="Infinity">
                            @foreach(\App\Models\Discount::$panelDiscountSource as $source)
                                <option value="{{ $source }}" {{ (!empty($discount) and $discount->source == $source) ? 'selected' : '' }}>{{ trans('update.discount_source_'.$source) }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">@error('source') {{ $message }} @enderror</div>
                    </div>

                    @php
                        $selectedCourseIds = (!empty($discount) and !empty($discount->discountCourses)) ? $discount->discountCourses->pluck('course_id')->toArray() : [];
                        $selectedBundleIds = (!empty($discount) and !empty($discount->discountBundles)) ? $discount->discountBundles->pluck('bundle_id')->toArray() : [];
                    @endphp

                    <div class="form-group  js-courses-input {{ (empty($discount) or $discount->source != \App\Models\Discount::$discountSourceCourse) ? 'd-none' : '' }}">
                        <label class="form-group-label">{{ trans('admin/main.courses') }}</label>
                        <select name="webinar_ids[]" multiple="multiple" class="form-control select2 " data-placeholder="{{ trans('update.select_a_course') }}">
                            @if(!empty($webinars))
                                @foreach($webinars as $webinar)
                                    <option value="{{ $webinar->id }}" {{ in_array($webinar->id, $selectedCourseIds) ? 'selected' : '' }}>{{ $webinar->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group  js-bundles-input {{ (!empty($discount) and $discount->source == \App\Models\Discount::$discountSourceBundle) ? '' : 'd-none' }}">
                        <label class="form-group-label">{{ trans('update.bundles') }}</label>
                        <select name="bundle_ids[]" multiple="multiple" class="form-control select2 " data-placeholder="{{ trans('update.select_a_bundle') }}">
                            @if(!empty($bundles))
                                @foreach($bundles as $bundle)
                                    <option value="{{ $bundle->id }}" {{ in_array($bundle->id, $selectedCourseIds) ? 'selected' : '' }}>{{ $bundle->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>


                    <div class="form-group  js-products-input {{ (empty($discount) or $discount->source != \App\Models\Discount::$discountSourceProduct) ? 'd-none' : '' }}">
                        <label class="form-group-label">{{ trans('update.product_type') }}</label>
                        <select name="product_type" class="form-control select2" data-minimum-results-for-search="Infinity">
                            <option value="all">{{ trans('admin/main.all') }}</option>
                            <option value="physical" {{ (!empty($discount) and $discount->product_type == 'physical') ? 'selected' : '' }}>{{ trans('update.physical') }}</option>
                            <option value="virtual" {{ (!empty($discount) and $discount->product_type == 'virtual') ? 'selected' : '' }}>{{ trans('update.virtual') }}</option>
                        </select>
                    </div>

                    {{-- Percentage Inputs --}}
                    <div class="js-percentage-inputs {{ (empty($discount) or $discount->discount_type == 'percentage') ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label class="form-group-label">{{ trans('admin/main.discount_percentage') }}</label>
                            <span class="has-translation bg-gray-200 rounded-8 p-8 text-gray-500 font-14">%</span>
                            <input type="number" name="percent"
                                   class="form-control  @error('percent') is-invalid @enderror"
                                   value="{{ !empty($discount) ? $discount->percent : old('percent') }}"
                                   min="0"/>

                            @error('percent')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group ">
                            <label class="form-group-label">{{ trans('admin/main.max_amount') }}</label>
                            <span class="has-translation bg-gray-200 rounded-8 p-8 text-gray-500 font-14">{{ $currency }}</span>
                            <input type="text" name="max_amount"
                                   class="form-control @error('max_amount') is-invalid @enderror"
                                   value="{{ !empty($discount) ? convertPriceToUserCurrency($discount->max_amount) : old('max_amount') }}"
                                   placeholder="{{ trans('update.discount_max_amount_placeholder') }}" oninput="validatePrice(this)"/>

                            <div class="invalid-feedback d-block">@error('max_amount') {{ $message }} @enderror</div>
                        </div>
                    </div>

                    {{-- Fixed Amount --}}
                    <div class="js-fixed-amount-inputs {{ (!empty($discount) and $discount->discount_type == 'fixed_amount') ? '' : 'd-none' }}">
                        <div class="form-group ">
                            <label class="form-group-label">{{ trans('admin/main.amount') }}</label>
                            <span class="has-translation bg-gray-200 rounded-8 p-8 text-gray-500 font-14">{{ $currency }}</span>
                            <input type="text" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{ !empty($discount) ? convertPriceToUserCurrency($discount->amount) : old('amount') }}"
                                   placeholder="{{ trans('update.discount_amount_placeholder') }}" oninput="validatePrice(this)"/>

                            <div class="invalid-feedback d-block">@error('amount') {{ $message }} @enderror</div>
                        </div>
                    </div>


                    <div class="form-group ">
                        <label class="form-group-label">{{ trans('update.minimum_order') }}</label>
                        <span class="has-translation bg-gray-200 rounded-8 p-8 text-gray-500 font-14">{{ $currency }}</span>
                        <input type="text" name="minimum_order"
                               class="form-control @error('minimum_order') is-invalid @enderror"
                               value="{{ !empty($discount) ? convertPriceToUserCurrency($discount->minimum_order) : old('minimum_order') }}"
                               placeholder="{{ trans('update.discount_minimum_order_placeholder') }}" oninput="validatePrice(this)"/>

                        <div class="invalid-feedback d-block">@error('minimum_order') {{ $message }} @enderror</div>
                    </div>


                    <div class="form-group ">
                        <label class="form-group-label">{{ trans('admin/main.usable_times') }}</label>
                        <span class="has-translation bg-gray-200 rounded-8 p-8 text-gray-500 font-14"><x-iconsax-lin-profile-2user class="icons text-gray-500" width="24px" height="24px"/></span>
                        <input type="number" name="count"
                               class="form-control @error('count') is-invalid @enderror"
                               value="{{ !empty($discount) ? $discount->count : old('count') }}"
                               placeholder="{{ trans('admin/main.count_placeholder') }}"/>

                        <div class="invalid-feedback d-block">@error('count') {{ $message }} @enderror</div>

                        <div class="font-12 text-gray-500 mt-8">{{ trans('update.the_coupon_will_be_expired_after_reaching_this_parameter_leave_it_blank_for_unlimited') }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.coupon_code') }}</label>
                        <input type="text" name="code"
                               value="{{ !empty($discount) ? $discount->code : old('code') }}"
                               class="form-control @error('code') is-invalid @enderror">
                        @error('code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group ">
                        <label class="form-group-label">{{ trans('update.expiry_date') }}</label>
                        <span class="has-translation bg-gray-200 rounded-8 p-8 text-gray-500 font-14"><x-iconsax-lin-calendar-2 class="icons text-gray-500" width="24px" height="24px"/></span>
                        <input type="text" name="expired_at" class="form-control datetimepicker js-default-init-date-picker @error('expired_at') is-invalid @enderror"
                               aria-describedby="dateRangeLabel" autocomplete="off" data-drops="up"
                               value="{{ !empty($discount) ? dateTimeFormat($discount->expired_at, 'Y-m-d H:i', false) : '' }}"/>

                        @error('expired_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group d-flex align-items-center mt-16">
                        <div class="custom-switch mr-8">
                            <input id="privateSwitch" type="checkbox" name="private" class="custom-control-input" {{ (!empty($discount) and $discount->private) ? 'checked' : '' }}>
                            <label class="custom-control-label cursor-pointer" for="privateSwitch"></label>
                        </div>

                        <label class="input-label cursor-pointer" for="privateSwitch">
                            <div class="">{{ trans('update.private_coupon') }}</div>
                            <p class="font-12 text-gray-500"> {{ trans('update.the_coupon_will_be_hidden_in_frontend') }}</p>
                        </label>
                    </div>

                </div>
            </div>
        </div>

        <div class="panel-bottom-bar d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white px-32 py-16">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                    <x-iconsax-bol-info-circle class="icons text-gray-400" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <h5 class="font-14">{{ trans('product.information') }}</h5>
                    <p class="mt-2 font-12 text-gray-500">{{ trans('update.your_coupon_will_be_published_after_save') }}</p>
                </div>
            </div>

            <button type="submit" class="btn btn-lg btn-primary mt-20 mt-lg-0">{{ trans('public.save') }}</button>
        </div>
    </form>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>

    <script src="/assets/design_1/js/panel/create_discount.min.js"></script>
@endpush

