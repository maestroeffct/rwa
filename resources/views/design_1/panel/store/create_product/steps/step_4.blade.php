@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">

    {{-- Specifications --}}
    <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-video-tick class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.specifications') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.product_files_hint_1') }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            @include('design_1.panel.store.create_product.includes.accordions.specification')
        </div>

        <div class="col-lg-6 mt-16">
            @if(!empty($product->selectedSpecifications) and count($product->selectedSpecifications))
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold">{{ trans('update.specifications') }}</h3>

                    <ul class="draggable-content-lists file-draggable-lists" data-path="" data-drag-class="file-draggable-lists">
                        @foreach($product->selectedSpecifications as $selectedSpecificationRow)
                            @include('design_1.panel.store.create_product.includes.accordions.specification', ['selectedSpecification' => $selectedSpecificationRow])
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="d-flex-center flex-column px-32 py-120 text-center rounded-16 border-gray-200">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-bill class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.specifications_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('update.specifications_no_result_hint') !!}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- FAQ --}}
    <div class="d-flex align-items-center justify-content-between mt-32 p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-bill class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.frequently_asked_questions') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.add_FAQ_and_display_them_on_the_course_page') }}</p>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-6">
            @include('design_1.panel.store.create_product.includes.accordions.faq')
        </div>

        <div class="col-lg-6 mt-16">
            @if(!empty($product->faqs) and count($product->faqs))
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold">{{ trans('update.faqs') }}</h3>

                    <ul class="draggable-content-lists faq-draggable-lists" data-path="" data-drag-class="faq-draggable-lists">
                        @foreach($product->faqs as $faqInfo)
                            @include('design_1.panel.store.create_product.includes.accordions.faq',['faq' => $faqInfo])
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="d-flex-center flex-column px-32 py-120 text-center rounded-16 border-gray-200">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-message-question class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.product_faq_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('update.product_faq_no_result_hint') !!}</p>
                </div>
            @endif
        </div>
    </div>

</div>


@push('scripts_bottom')
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
@endpush
