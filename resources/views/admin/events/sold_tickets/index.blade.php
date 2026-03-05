@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.events') }}</div>
            </div>
        </div>

        <div class="section-body">

            {{-- Filters --}}
            @include('admin.events.sold_tickets.filters')


            <div class="card">
                <div class="card-header justify-content-between">
                    <div>
                        <h5 class="font-14 mb-0">{{ trans('update.sold_tickets') }}</h5>
                        <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.view_all_ticket_holders_and_their_purchase_details') }}</p>
                    </div>

                    <div class="d-flex align-items-center gap-12">
                        @can('admin_events_lists_export_excel')
                            <a href="{{ $pageBaseUrl .'/export?'. http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div>
                        <table class="table custom-table font-14 ">
                            <tr>
                                @if(empty($selectedEvent))
                                    <th class="text-left">{{trans('update.event')}}</th>
                                @endif

                                <th class="text-left">{{trans('update.participant')}}</th>
                                <th>{{trans('update.ticket_type')}}</th>
                                <th>{{trans('public.paid_amount')}}</th>
                                <th>{{trans('update.ticket_code')}}</th>
                                <th>{{trans('update.purchase_date')}}</th>
                                <th width="120">{{trans('admin/main.actions')}}</th>
                            </tr>

                            @foreach($purchasedTickets as $purchasedTicket)
                                @include('admin.events.sold_tickets.table_items')
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $purchasedTickets->appends(request()->input())->links() }}
                </div>

            </div>

        </div>
    </section>
@endsection
