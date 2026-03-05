@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.marketing.discounts.lists.top_stats')

    @if(!empty($discounts) and !$discounts->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('panel.comments') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_and_manage_coupons_and_related_statistics') }}</p>
                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.marketing.discounts.lists.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/marketing/discounts">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('public.title') }}</th>
                        <th class="text-center">{{ trans('update.source') }}</th>
                        <th class="text-center">{{ trans('panel.amount') }}</th>
                        <th class="text-center">{{ trans('admin/main.usable_times') }}</th>
                        <th class="text-center">{{ trans('update.min_amount') }}</th>
                        <th class="text-center">{{ trans('update.max_amount') }}</th>
                        <th class="text-center">{{ trans('admin/main.sales') }}</th>
                        <th class="text-center">{{ trans('admin/main.created_date') }}</th>
                        <th class="text-center">{{ trans('update.expiry_date') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-right">{{ trans('update.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($discounts as $discountRow)
                        @include('design_1.panel.marketing.discounts.lists.table_items', ['discount' => $discountRow])
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
            'file_name' => 'discounts.svg',
            'title' => trans('panel.discount_no_result'),
            'hint' =>  nl2br(trans('panel.discount_no_result_hint')) ,
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
