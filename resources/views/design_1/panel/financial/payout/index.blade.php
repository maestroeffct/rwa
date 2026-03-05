@extends('design_1.panel.layouts.panel')

@push('styles_top')

@endpush

@section('content')

    <div class="row">
        <div class="col-12 col-lg-6">
            @include('design_1.panel.financial.payout.ready_to_payout')
        </div>

        <div class="col-12 col-lg-6 mt-16 mt-lg-0">
            @include('design_1.panel.financial.payout.statistics')
        </div>
    </div>

    @if(!empty($payouts) and !$payouts->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('financial.payouts_history') }}</h3>

                </div>
            </div>

            {{-- Filters --}}

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/financial/payout">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('financial.account') }}</th>
                        <th class="text-center">{{ trans('public.type') }}</th>
                        <th class="text-center">{{ trans('panel.amount') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-center">{{ trans('admin/main.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($payouts as $payoutRow)
                        @include('design_1.panel.financial.payout.table_items', ['payout' => $payoutRow])
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
            'file_name' => 'payout.svg',
            'title' => trans('financial.payout_no_result'),
            'hint' => nl2br(trans('financial.payout_no_result_hint')),
        ])
    @endif

    <div id="requestPayoutModal" class="d-none">
        <div class="d-flex-center flex-column text-center">
            <img src="/assets/design_1/img/panel/payout/payout_request.svg" alt="payout_request" class="" width="154px" height="150px">

            <h5 class="font-14 font-weight-bold mt-16">{{ trans('update.review_payout_information') }}</h5>
            <p class="font-12 text-gray-500 mt-8">{{ trans('update.review_payout_information_hint') }}</p>
        </div>

        <div class="mt-16 p-16 rounded-12 border-gray-200">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-gray-500">{{ trans('update.payout_amount') }}</span>
                <span class="">{{ handlePrice($readyPayout ?? 0) }}</span>
            </div>

            @if(!empty($authUser->selectedBank) and !empty($authUser->selectedBank->bank))
                <div class="d-flex align-items-center justify-content-between mt-12">
                    <span class="text-gray-500">{{ trans('financial.account') }}</span>
                    <span class="">{{ $authUser->selectedBank->bank->title }}</span>
                </div>

                @foreach($authUser->selectedBank->bank->specifications as $specification)
                    @php
                        $selectedBankSpecification = $authUser->selectedBank->specifications->where('user_selected_bank_id', $authUser->selectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                    @endphp

                    <div class="d-flex align-items-center justify-content-between mt-12">
                        <span class="text-gray-500">{{ $specification->name }}</span>
                        <span>{{ (!empty($selectedBankSpecification)) ? $selectedBankSpecification->value : '' }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        var payoutDetailsLang = '{{ trans('update.payout_details') }}';
        var closeLang = '{{ trans('public.close') }}';
        var payoutRequestLang = '{{ trans('financial.payout_request') }}';
        var submitRequestLang = '{{ trans('update.submit_request') }}';
    </script>

    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/payout.min.js"></script>
@endpush
