@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.financial.sales.top_stats')

    @if(!empty($sales) and !$sales->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('financial.sales_history') }}</h3>

                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.financial.sales.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/financial/sales">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('quiz.student') }}</th>
                        <th class="text-left">{{ trans('product.content') }}</th>
                        <th class="text-center">{{ trans('public.price') }}</th>
                        <th class="text-center">{{ trans('public.discount') }}</th>
                        <th class="text-center">{{ trans('financial.total_amount') }}</th>
                        <th class="text-center">{{ trans('financial.income') }}</th>
                        <th class="text-center">{{ trans('public.type') }}</th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($sales as $saleRow)
                        @include('design_1.panel.financial.sales.table_items', ['sale' => $saleRow])
                    @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                    {!! $pagination !!}
                </div>
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
          'file_name' => 'sales.svg',
          'title' => trans('financial.sales_no_result'),
          'hint' => nl2br(trans('financial.sales_no_result_hint')),
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
