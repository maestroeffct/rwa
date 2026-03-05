@extends('design_1.panel.layouts.panel')

@section('content')

    @if($promotionSales->count() < 1)
        <div class="no-promotions-plans position-relative mb-32">
            <div class="no-promotions-plans__mask"></div>
            <div class="position-relative z-index-2 bg-white p-16 rounded-16">
                <h3 class="font-14 text-dark">{{ trans('update.no_promotion_plans') }}</h3>
                <p class="font-12 text-gray-500 mt-4">{{ trans('update.no_promotion_plans_alert_hint') }}</p>

                <div class="no-promotions-plans__crown-img">
                    <img src="/assets/design_1/img/panel/promotions/crown.png" alt="crown" class="img-cover">
                </div>
            </div>
        </div>
    @endif

    <div class="row mb-108">
        @foreach($promotions as $promotion)
            <div class="col-12 col-md-4 col-lg-3 {{ $loop->first ? '' : 'mt-64 mt-lg-0' }}">
                <div class="position-relative promotion-plan bg-white rounded-16 pt-32 pb-72">
                    <div class="d-flex-center text-center">
                        <div class="d-flex-center size-100 bg-gray-200 rounded-circle">
                            <div class="d-flex-center size-68 rounded-circle">
                                <img src="{{ $promotion->icon }}" class="img-cover rounded-circle" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="px-16">
                        <h3 class="mt-16 font-16 text-dark">{{ $promotion->title }}</h3>
                        <p class="font-12 text-gray-500 mt-4">{{ nl2br($promotion->description) }}</p>

                        <div class="d-flex align-items-end gap-4 mt-20">
                            <span class="font-44 font-weight-bold">{{ (!empty($promotion->price) and $promotion->price > 0) ? handlePrice($promotion->price, true, true, false, null, true) : trans('public.free') }}</span>
                            <span class="text-gray-500">/ {{ trans('update.n_day',['day' => $promotion->days]) }}</span>
                        </div>
                    </div>

                    <div class="js-pay-promotion-modal promotion-plan__footer d-flex align-items-center justify-content-between p-16 rounded-20 cursor-pointer" data-promotion-id="{{ $promotion->id }}">
                        <div class="">
                            <h5 class="font-14">{{ trans('update.promote_your_course') }}</h5>
                            <p class="font-12 mt-2">{{ trans('update.increase_your_course_views_and_sales') }}</p>
                        </div>

                        <div class="promotion-plan__footer-icon-box d-flex-center size-48 rounded-circle">
                            <x-iconsax-bol-flash class="icons" width="24px" height="24px"/>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    {{-- List Table --}}
    @if(!empty($promotionSales) and $promotionSales->isNotEmpty())
        <div class="bg-white pt-16 rounded-16">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.promotion_plans') }}</h3>
                    <p class="font-14 text-gray-500 mt-2">{{ trans('update.view_plans_and_related_statistics') }}</p>
                </div>
            </div>

            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/marketing/promotions">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('update.course') }}</th>
                        <th class="text-center">{{ trans('panel.plan') }}</th>
                        <th class="text-center">{{ trans('panel.amount') }}</th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($promotionSales as $promotionSaleRow)
                        @include('design_1.panel.marketing.promotions.table_items',['promotionSale' => $promotionSaleRow])
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
            'file_name' => 'promotions.svg',
            'title' => trans('panel.promotion_no_result'),
            'hint' =>  nl2br(trans('panel.promotion_no_result_hint')),
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script>
        var promotionLang = "{{ trans('panel.promotion') }}";
        var proceedToPaymentLang = "{{ trans('update.proceed_to_payment') }}";
        var cancelLang = "{{ trans('public.cancel') }}";
    </script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/promotions.min.js"></script>
@endpush
