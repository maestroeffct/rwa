@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    @if(!empty($accountings) and !$accountings->isEmpty())
        <div class="bg-white pt-16 rounded-24">
            <div class="px-16 pb-16 border-bottom-gray-100">
                <h2 class="font-16 font-weight-bold">{{ trans('financial.financial_documents') }}</h2>
                <p class="mt-4 text-gray-500">{{ trans('update.view_and_manage_transactions_in_your_account') }}</p>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.financial.summary.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/financial/summary">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('public.title') }}</th>
                        <th class="text-left">{{ trans('public.description') }}</th>
                        <th class="text-center">{{ trans('panel.amount') }} ({{ $currency }})</th>
                        <th class="text-center">{{ trans('public.creator') }}</th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($accountings as $accountingRow)
                        @include('design_1.panel.financial.summary.table_items', ['accounting' => $accountingRow])
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
            'file_name' => 'financial_summary.svg',
            'title' => trans('financial.financial_summary_no_result'),
            'hint' => nl2br(trans('financial.financial_summary_no_result_hint')),
            'extraClass' => 'mt-0',
        ])
    @endif
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
