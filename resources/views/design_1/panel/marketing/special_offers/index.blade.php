@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
    <div class="row pb-60">
        <div class="col-12 col-lg-6">
            @include('design_1.panel.marketing.special_offers.create_form')
        </div>

        {{-- Lists --}}
        <div class="col-12 col-lg-6 mt-20 mt-lg-0">
            @if(!empty($specialOffers) and $specialOffers->count() > 0)
                <div class="bg-white pt-16 rounded-24">
                    <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                        <div class="">
                            <h3 class="font-16">{{ trans('panel.discounts') }}</h3>
                            <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_and_manage_course_discounts') }}</p>
                        </div>
                    </div>

                    <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/marketing/special_offers">
                        <table class="table panel-table">
                            <thead>
                            <tr>
                                <th class="text-left">{{ trans('update.title_and_course') }}</th>
                                <th class="text-center">{{ trans('panel.amount') }}</th>
                                <th class="text-center">{{ trans('update.date_range') }}</th>
                                <th class="text-center">{{ trans('public.status') }}</th>
                                <th class="text-right">{{ trans('update.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody class="js-table-body-lists">
                            @foreach($specialOffers as $specialOfferRow)
                                @include('design_1.panel.marketing.special_offers.table_items', ['specialOffer' => $specialOfferRow])
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
                    'file_name' => 'special_offers.svg',
                    'title' => trans('panel.discount_no_result'),
                    'hint' =>  nl2br(trans('panel.discount_no_result_hint')),
                    'extraClass' => 'mt-0',
                ])
            @endif
        </div>

    </div>

@endsection

@push('scripts_bottom')

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/special_offers.min.js"></script>
@endpush
