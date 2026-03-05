@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.rewards.top_stats')

    <div class="row">
        {{-- Convert your points --}}
        <div class="col-12 col-lg-6 mt-20">
            @include('design_1.panel.rewards.convert_points')
        </div>

        <div class="col-12 col-lg-6 mt-20">
            @include('design_1.panel.rewards.leaderboard')
        </div>
    </div>


    {{-- Financial Documents --}}
    @if(!empty($rewards) and count($rewards))
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="px-16 pb-16 border-bottom-gray-200">
                <h3 class="font-16 font-weight-bold">{{ trans('financial.financial_documents') }}</h3>
                <p class="mt-2 text-gray-500">{{ trans('update.view_and_manage_transactions_in_your_account') }}</p>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.rewards.filters')

            {{-- Lists --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/rewards">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('public.title') }}</th>
                        <th class="text-center">{{ trans('public.type') }}</th>
                        <th class="text-center">{{ trans('update.points') }}</th>
                        <th class="text-center">{{ trans('public.date_time') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($rewards as $rewardRow)
                        @include('design_1.panel.rewards.table_items',['reward' => $rewardRow])
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
            'file_name' => 'rewards.svg',
            'title' => trans('update.reward_no_result'),
            'hint' => nl2br(trans('update.reward_no_result_hint')),
        ])
    @endif


    <div class="d-none" id="exchangePointsModal">
        <div class="d-flex-center flex-column text-center pb-24">
            <img src="/assets/design_1/img/panel/reward/exchange-points.svg" alt="exchange-points" class="img-fluid" width="215px" height="160px">

            <h3 class="mt-12 font-14 font-weight-bold">{{ trans('update.convert_points_to_wallet_balance') }}</h3>
            <p class="font-12 text-gray-500 mt-8">
                <span class="d-block">{{ trans('update.you_will_get_n_for_points',['amount' => handlePrice($earnByExchange) ,'points' => $availablePoints]) }}</span>
                <span class="d-block">{{ trans('update.the_amount_will_be_charged_to_your_wallet') }}</span>
                <span class="d-block">{{ trans('update.do_you_want_to_proceed') }}</span>
            </p>

            <div class="reward-your-points-exchange-card d-flex align-items-center justify-content-around mt-16 py-16 px-32 rounded-16 bg-white soft-shadow-2 border-gray-200">
                <div class="text-center">
                    <span class="d-block font-24 font-weight-bold">{{ $availablePoints }}</span>
                    <span class="d-block font-12 text-gray-500 mt-2">{{ trans('update.points') }}</span>
                </div>

                <div class="d-flex-center size-40 rounded-circle border-gray-200 mx-20 mx-md-32">
                    <x-iconsax-lin-repeat class="icons text-primary" width="20px" height="20px"/>
                </div>

                <div class="text-center">
                    <span class="d-block font-24 font-weight-bold">{{ handlePrice($earnByExchange) }}</span>
                    <span class="d-block font-12 text-gray-500 mt-2">{{ trans('update.wallet_charge') }}</span>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script>
        var exchangePointsLang = '{{ trans('update.exchange_points') }}';
        var convertPointsLang = '{{ trans('update.convert_points') }}';
        var closeLang = '{{ trans('public.close') }}';
        var exchangeSuccessAlertTitleLang = '{{ trans('update.exchange_success_alert_title') }}';
        var exchangeSuccessAlertDescLang = '{{ trans('update.exchange_success_alert_desc') }}';
        var exchangeErrorAlertTitleLang = '{{ trans('update.exchange_error_alert_title') }}';
        var exchangeErrorAlertDescLang = '{{ trans('update.exchange_error_alert_desc') }}';
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>

    <script src="/assets/design_1/js/panel/reward.min.js"></script>
@endpush
