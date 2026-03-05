@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">

    <h3 class="font-14 font-weight-bold mb-24">{{ trans('public.basic_information') }}</h3>


    @include('design_1.panel.includes.locale.locale_select',[
        'itemRow' => !empty($product) ? $product : null,
        'withoutReloadLocale' => false,
        'extraClass' => ''
    ])

    <div class="form-group ">
        <label class="form-group-label is-required">{{ trans('public.type') }}</label>

        <select name="type" class="form-control select2 @error('type')  is-invalid @enderror" data-minimum-results-for-search="Infinity">
            @if(!empty(getStoreSettings('possibility_create_physical_product')) and getStoreSettings('possibility_create_physical_product'))
                <option value="physical" @if(!empty($product) and $product->isPhysical()) selected @endif>{{ trans('update.physical') }}</option>
            @endif

            @if(!empty(getStoreSettings('possibility_create_virtual_product')) and getStoreSettings('possibility_create_virtual_product'))
                <option value="virtual" @if(!empty($product) and $product->isVirtual()) selected @endif>{{ trans('update.virtual') }}</option>
            @endif
        </select>

        @error('type')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>


    <div class="form-group">
        <label class="form-group-label is-required">{{ trans('public.title') }}</label>
        <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
        <input type="text" name="title" class="form-control @error('title')  is-invalid @enderror" value="{{ (!empty($product) and !empty($product->translate($locale))) ? $product->translate($locale)->title : old('title') }}" placeholder=""/>
        @error('title')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group mt-15">
        <label class="form-group-label is-required">{{ trans('public.seo_description') }}</label>
        <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
        <input type="text" name="seo_description" class="form-control @error('seo_description')  is-invalid @enderror " value="{{ (!empty($product) and !empty($product->translate($locale))) ? $product->translate($locale)->seo_description : old('seo_description') }}" placeholder="{{ trans('forms.50_160_characters_preferred') }}"/>
        @error('seo_description')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    {{-- Course Description --}}
    <h3 class="font-14 font-weight-bold my-24">{{ trans('public.description') }}</h3>

    <div class="form-group">
        <label class="form-group-label is-required">{{ trans('public.summary') }}</label>
        <textarea name="summary" rows="6" class="form-control @error('summary')  is-invalid @enderror " placeholder="{{ trans('update.product_summary_placeholder') }}">{{ (!empty($product) and !empty($product->translate($locale))) ? $product->translate($locale)->summary : old('summary') }}</textarea>
        @error('summary')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group bg-white-editor">
        <label class="form-group-label is-required">{{ trans('public.description') }}</label>
        <textarea name="description" class="main-summernote form-control @error('description')  is-invalid @enderror" data-height="400" placeholder="{{ trans('forms.webinar_description_placeholder') }}">{!! (!empty($product) and !empty($product->translate($locale))) ? $product->translate($locale)->description : old('description')  !!}</textarea>
        @error('description')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>


    <div class="form-group">
        <div class="d-flex align-items-center">
            <div class="custom-switch mr-8">
                <input id="orderingSwitch" type="checkbox" name="ordering" class="custom-control-input" {{ (!empty($product) and $product->ordering) ? 'checked' :  '' }}>
                <label class="custom-control-label cursor-pointer" for="orderingSwitch"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="orderingSwitch">{{ trans('update.enable_ordering') }}</label>
            </div>
        </div>

        <p class="text-gray-500 font-12 mt-6">{{ trans('update.create_product_enable_ordering_hint') }}</p>
    </div>

</div>


@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
@endpush
