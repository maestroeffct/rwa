@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
    {{-- Event --}}
    <div class="sold-tickets-event-card position-relative mb-20">
        <div class="sold-tickets-event-card__mask"></div>
        <div class="position-relative z-index-2 d-flex align-items-center justify-content-between p-20 rounded-16 bg-white ">
            <div class="d-flex align-items-center">
                <div class="size-64">
                    <img src="{{ $event->getIcon() }}" alt="{{ $event->title }}" class="img-cover">
                </div>
                <div class="ml-8">
                    <h4 class="font-14">{{ $event->title }}</h4>
                    <p class="font-12 text-gray-500 mt-4">{{ $event->subtitle }}</p>
                </div>
            </div>

            <a href="{{ $event->getUrl() }}" target="_blank" class="d-flex align-items-center gap-4 text-primary font-12">
                <span class="font-weight-bold">{{ trans('update.view_event_page') }}</span>
                <x-iconsax-lin-arrow-right class="icons text-primary" width="16" height="16"/>
            </a>
        </div>
    </div>

    {{-- Top Stats --}}
    @include('design_1.panel.events.sold_tickets.top_stats')

    @if(!empty($purchasedTickets) and !$purchasedTickets->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.sold_tickets') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_all_ticket_holders_and_their_purchase_details') }}</p>
                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.events.sold_tickets.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/events/{{ $event->id }}/sold-tickets">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('update.participant') }}</th>
                        <th class="text-center">{{ trans('update.ticket_type') }}</th>
                        <th class="text-center">{{ trans('public.paid_amount') }}</th>
                        <th class="text-center">{{ trans('update.ticket_code') }}</th>
                        <th class="text-center">{{ trans('update.purchase_date') }}</th>
                        <th class="text-right">{{ trans('update.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($purchasedTickets as $ticketRow)
                        @include('design_1.panel.events.sold_tickets.table_items', ['purchasedTicket' => $ticketRow])
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
            'file_name' => 'event_sold_tickets.svg',
            'title' => trans('update.event_sold_tickets_no_result'),
            'hint' => nl2br(trans('update.event_sold_tickets_no_result_hint')),
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script>

    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    {{--<script src="/assets/design_1/js/panel/meeting_requests.min.js"></script>--}}
@endpush
