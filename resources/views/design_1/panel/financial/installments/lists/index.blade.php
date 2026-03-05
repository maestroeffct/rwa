@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
@endpush

@section('content')

    @if(!empty($overdueInstallments) and count($overdueInstallments))
        <div class="d-flex align-items-center mb-20 p-12 bg-danger-20 text-danger rounded-24">
            <div class="d-flex-center size-40 rounded-circle bg-danger-30">
                <x-iconsax-lin-info-circle class="icons text-danger" width="20px" height="20px"/>
            </div>

            <div class="ml-8">
                <div class="font-14 font-weight-bold text-danger">{{ trans('update.overdue_installments') }}</div>
                <div class="font-12 text-danger">{{ trans('update.you_have_count_overdue_installments_please_pay_them_to_avoid_restrictions_and_negative_effects_on_your_account',['count' => count($overdueInstallments)]) }}</div>
            </div>
        </div>
    @endif

    {{-- Top Stats --}}
    @include('design_1.panel.financial.installments.lists.top_stats')

    {{-- Overdue Installments --}}
    @include('design_1.panel.financial.installments.lists.overdue')


    {{-- My Installments --}}
    @if(!empty($orders) and !$orders->isEmpty())
        <div class="pt-16 rounded-24 mt-16">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.my_installments') }}</h3>

                </div>
            </div>

            {{-- Filters --}}

            {{-- List Table --}}
            <div id="tableListContainer" class="" data-view-data-path="/panel/financial/installments">
                <div class="js-page-bundles-lists row px-16">
                    @foreach($orders as $orderRow)
                        <div class="col-12 col-lg-3 mt-16">
                            @include("design_1.panel.financial.installments.lists.grid_card", ['order' => $orderRow])
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-page-bundles-lists">
                    {!! $pagination !!}
                </div>
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
                'file_name' => 'installments.svg',
                'title' => trans('update.you_not_have_any_installment'),
                'hint' =>  trans('update.you_not_have_any_installment_hint'),
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>
    <script src="/assets/design_1/js/panel/installment_lists.min.js"></script>
@endpush
