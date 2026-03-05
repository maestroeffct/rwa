@extends('design_1.panel.layouts.panel')

@push("styles_top")

@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.store.my_products.top_stats')

    {{-- List Table --}}
    @if(!empty($products) and $products->isNotEmpty())
        <div id="tableListContainer" class="" data-view-data-path="/panel/courses">
            <div class="js-page-products-lists row mt-20">
                @foreach($products as $product)
                    <div class="col-12 col-lg-6 mb-32">
                        @include("design_1.panel.store.my_products.product_card.index")
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer"
                 data-container-items=".js-page-products-lists">
                {!! $pagination !!}
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'store_products.svg',
            'title' => trans('update.you_not_have_any_product'),
            'hint' =>  trans('update.you_not_have_any_product_hint') ,
            'btn' => ['url' => '/panel/store/products/new','text' => trans('update.create_a_product') ]
        ])
    @endif

@endsection

@push('scripts_bottom')

    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

@endpush
