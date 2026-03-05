@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.meeting.purchased_packages.lists.top_stats')

    @if(!empty($meetingPackagesSold) and !$meetingPackagesSold->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.purchased_packages') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_and_manage_all_purchased_meeting_packages') }}</p>
                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.meeting.purchased_packages.lists.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/meetings/purchased-packages">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('update.instructor') }}</th>
                        <th class="text-center">{{ trans('update.meeting_package') }}</th>
                        <th class="text-center">{{ trans('public.paid_amount') }}</th>
                        <th class="text-center">{{ trans('update.total_sessions') }}</th>
                        <th class="text-center">{{ trans('update.ended') }}</th>
                        <th class="text-center">{{ trans('update.scheduled') }}</th>
                        <th class="text-center">{{ trans('update.not_scheduled') }}</th>
                        <th class="text-center">{{ trans('update.purchase_date') }}</th>
                        <th class="text-center">{{ trans('update.expiry_date') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-right">{{ trans('update.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($meetingPackagesSold as $meetingPackageRow)
                        @include('design_1.panel.meeting.purchased_packages.lists.table_items', ['meetingPackageSold' => $meetingPackageRow])
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
            'file_name' => 'purchased_meeting_packages.svg',
            'title' => trans('update.purchased_meeting_packages_no_result'),
            'hint' => nl2br(trans('update.purchased_meeting_packages_no_result_hint')),
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script>

    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/meeting_sold_packages.min.js"></script>
@endpush
