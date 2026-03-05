@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">

    {{-- Pricing Options --}}
    <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.pricing_options') }}</h3>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.price') }}</label>
        <span class="has-translation bg-gray-100 text-gray-500">{{ $currency }}</span>
        <input type="text" name="price" class="form-control @error('price')  is-invalid @enderror" value="{{ (!empty($bundle) and !empty($bundle->price)) ? convertPriceToUserCurrency($bundle->price) : old('price') }}" placeholder="{{ trans('public.0_for_free') }}" oninput="validatePrice(this)"/>
        <div class="invalid-feedback d-block">@error('price') {{ $message }} @enderror</div>
    </div>


    <div class="form-group">
        <label class="form-group-label">{{ trans('update.access_days') }} ({{ trans('public.optional') }})</label>
        <span class="has-translation bg-gray-100 text-gray-500 w-auto px-8">{{ trans('public.days') }}</span>
        <input type="number" name="access_days" class="form-control @error('access_days') is-invalid @enderror" value="{{ !empty($bundle) ? $bundle->access_days : old('access_days') }}"/>

        @error('access_days')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

        <p class="font-12 text-gray-500 mt-8">- {{ trans('update.access_days_input_hint') }}</p>
    </div>

    <div class="form-group">
        <div class="d-flex align-items-center">
            <div class="custom-switch mr-8">
                <input id="subscribeSwitch" type="checkbox" name="subscribe" class="custom-control-input" {{ (!empty($bundle) and $bundle->subscribe) ? 'checked' :  '' }}>
                <label class="custom-control-label cursor-pointer" for="subscribeSwitch"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="subscribeSwitch">{{ trans('update.include_subscribe') }}</label>
            </div>
        </div>

        <p class="font-12 text-gray-500 mt-8">- {{ trans('forms.subscribe_hint') }}</p>
    </div>

    {{-- Pricing Plans --}}

    <div class="d-flex align-items-center justify-content-between mt-32 p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-moneys class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.pricing_plans') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.create_different_pricing_plans_and_present_your_course_in_different_prices') }}</p>
            </div>
        </div>
    </div>

    <div class="mt-16 p-16 rounded-16 bg-gray-100 border-gray-300">
        <p class="font-12 text-gray-500">- {{ trans('webinars.sale_plans_hint_1') }}</p>
        <p class="font-12 text-gray-500 mt-12">- {{ trans('webinars.sale_plans_hint_2') }}</p>
        <p class="font-12 text-gray-500 mt-12">- {{ trans('webinars.sale_plans_hint_3') }}</p>
    </div>

    {{-- Pricing Plans Form --}}
    <div class="row">
        <div class="col-lg-6">
            @include('design_1.panel.bundles.create.includes.accordions.price_plan')
        </div>

        @if(!empty($bundle->tickets) and count($bundle->tickets))
            <div class="col-lg-6 mt-20 mt-lg-16">
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold">{{ trans('update.pricing_plans') }}</h3>

                    <ul class="draggable-content-lists price-plan-draggable-lists" data-path="/panel/tickets/orders" data-drag-class="price-plan-draggable-lists">
                        @foreach($bundle->tickets as $pricePlan)
                            @include('design_1.panel.bundles.create.includes.accordions.price_plan',['plan' => $pricePlan])
                        @endforeach
                    </ul>
                </div>
            </div>
        @else
            <div class="col-lg-6 d-flex-center flex-column px-32 py-120 text-center">
                <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                    <x-iconsax-bul-receipt-2 class="icons text-primary" width="32px" height="32px"/>
                </div>
                <h3 class="font-16 font-weight-bold mt-12">{{ trans('public.ticket_no_result') }}</h3>
                <p class="mt-4 font-12 text-gray-500">{!! trans('public.ticket_no_result_hint') !!}</p>
            </div>
        @endif
    </div>


</div>

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
@endpush
