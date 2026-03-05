<div class="bg-white p-16 rounded-24">

    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-gray-100 p-16 rounded-12 border-gray-200">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 rounded-8 bg-gray-300">
                <x-iconsax-bul-document-download class="icons text-gray-500" width="24px" height="24px"/>
            </div>
            <div class="ml-4">
                <h5 class="font-14">{{ trans('update.product_files') }}</h5>
                <div class="mt-4 font-12 text-gray-500">{{ trans('update.this_product_includes_files_which_you_can_access_from_the_following_list', ['count' => 2]) }}</div>
            </div>
        </div>

        <a href="/products/{{ $product->slug }}/files" target="_blank" class="d-flex align-items-center mt-16 mt-lg-0">
            <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
            <span class="ml-4 text-primary">{{ trans('update.download_page') }}</span>
        </a>
    </div>

    {{-- Files --}}
    @include("design_1.web.products.show.includes.files_accordion", ['files' => $product->files])

</div>
