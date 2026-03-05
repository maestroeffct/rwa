@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.store.my_purchases.top_stats')

    @if(!empty($orders) and !$orders->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.purchases_history') }}</h3>

                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.store.my_purchases.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/store/purchases">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class=" text-left">{{ trans('update.seller') }}</th>
                        <th class=" text-center">{{ trans('update.order_id') }}</th>
                        <th class="text-center">{{ trans('public.price') }}</th>
                        <th class="text-center">{{ trans('public.discount') }}</th>
                        <th class="text-center">{{ trans('cart.tax') }}</th>
                        <th class="text-center">{{ trans('update.delivery_fee') }}</th>
                        <th class="text-center">{{ trans('financial.total_amount') }}</th>
                        <th class="text-center">{{ trans('public.type') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                        <th class="text-right">{{ trans('update.controls') }}</th>
                    </tr>
                    </thead>

                    <tbody class="js-table-body-lists">
                    @foreach($orders as $orderRow)
                        @include('design_1.panel.store.my_purchases.table_items', ['order' => $orderRow])
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
           'file_name' => 'product_purchases.svg',
           'title' => trans('update.product_purchases_no_result'),
           'hint' => nl2br(trans('update.product_purchases_no_result_hint')),
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script>
        var viewTrackingCodeModalTitleLang = '{{ trans('update.view_tracking_code') }}';
        var trackingCodeLang = '{{ trans('update.tracking_code') }}';
        var closeLang = '{{ trans('public.close') }}';
        var confirmLang = '{{ trans('update.confirm') }}';
        var gotTheParcelLang = '{{ trans('update.i_got_the_parcel') }}';
        var gotTheParcelConfirmTextLang = '{{ trans('update.i_got_the_parcel_confirm') }}';
        var gotTheParcelSaveSuccessLang = '{{ trans('update.i_got_the_parcel_success_save') }}';
        var gotTheParcelSaveErrorLang = '{{ trans('update.i_got_the_parcel_error_save') }}';
        var shippingTrackingUrlLang = '{{ trans('update.track_shipping') }}';
        var addressLang = '{{ trans('update.address') }}';
        var filesLang = '{{ trans('public.files') }}';
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/store_my_purchase.min.js"></script>
@endpush
