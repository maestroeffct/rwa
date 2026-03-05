{{-- List Table --}}
@if(!empty($offlinePayments) and !$offlinePayments->isEmpty())
    <div class="bg-white pt-16 rounded-24 mt-24">
        <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
            <div class="">
                <h3 class="font-16">{{ trans('financial.offline_transactions_history') }}</h3>

            </div>
        </div>


        <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/financial/account">
            <table class="table panel-table">
                <thead>
                <tr>
                    <th class="text-left">{{ trans('financial.bank') }}</th>
                    <th class="text-left">{{ trans('admin/main.referral_code') }}</th>
                    <th class="text-center">{{ trans('panel.amount') }} ({{ $currency }})</th>
                    <th class="text-center">{{ trans('update.attachment') }}</th>
                    <th class="text-center">{{ trans('public.status') }}</th>
                    <th class="text-right">{{ trans('public.controls') }}</th>
                </tr>
                </thead>
                <tbody class="js-table-body-lists">
                @foreach($offlinePayments as $offlinePaymentRow)
                    @include("design_1.panel.financial.account.offline_transactions.table_items", ['offlinePayment' => $offlinePaymentRow])
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
        'file_name' => 'charge_account.svg',
        'title' => trans('financial.offline_no_result'),
        'hint' => nl2br(trans('financial.offline_no_result_hint')),
    ])
@endif
