@extends('design_1.web.layouts.app')

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("event_ticket_details") }}">
@endpush

@section('content')
    <div id="printArea" class="container mt-64 mb-100">
        <div class="d-flex-center flex-column">
            <h1 class="font-32">{{ trans('update.event_ticket_details') }}</h1>
            <p class="font-16 mt-8 text-gray-500">{{ trans('update.view_full_details_of_the_event_ticket') }}</p>


            <div class="ticket-details-card position-relative mt-24">
                <div class="ticket-details-card__mask"></div>

                <div class="position-relative z-index-2 bg-white p-16 rounded-24">
                    <div class="ticket-details-card__thumbnail rounded-12">
                        <img src="{{ $event->thumbnail }}" alt="{{ $event->title }}" class="img-cover rounded-12">
                    </div>

                    <div class="ticket-details-card__details mt-24 pt-24">
                        <div class="circle-1"></div>
                        <div class="circle-2"></div>

                        <div class="d-flex align-items-start justify-content-between gap-20">
                            <div class="">
                                <h3 class="font-16 ">{{ $event->title }}</h3>
                                <div class="d-flex align-items-center gap-4 mt-8 text-gray-500">
                                    <span class="">{{ trans('public.by') }}</span>
                                    <span class="font-weight-bold">{{ $event->creator->full_name }}</span>
                                </div>
                            </div>

                            @if($showQrCode and !empty($qrCode))
                                <div class="d-flex-center size-80 rounded-12 bg-gray-100">{!! $qrCode !!}</div>
                            @endif
                        </div>

                        <div class="d-flex-center mt-24">
                            <div class="d-flex px-16 py-8 rounded-32 bg-gray-100 font-24 font-weight-bold">{{ $purchasedTicket->code }}</div>
                        </div>

                        {{-- Ticket Type --}}
                        <div class="d-flex align-items-center justify-content-between mt-24">
                            <span class="text-gray-500">{{ trans('update.ticket_type') }}</span>
                            <span class="">{{ $purchasedTicket->eventTicket->title }}</span>
                        </div>

                        {{-- Holder --}}
                        <div class="d-flex align-items-center justify-content-between mt-16">
                            <span class="text-gray-500">{{ trans('update.holder') }}</span>
                            <span class="">{{ $event->creator->full_name }}</span>
                        </div>

                        {{-- Paid Amount --}}
                        <div class="d-flex align-items-center justify-content-between mt-16">
                            <span class="text-gray-500">{{ trans('public.paid_amount') }}</span>
                            <span class="">{{ ($purchasedTicket->paid_amount > 0) ? handlePrice($purchasedTicket->paid_amount) : trans('update.free') }}</span>
                        </div>

                        {{-- Purchase Date --}}
                        <div class="d-flex align-items-center justify-content-between mt-16">
                            <span class="text-gray-500">{{ trans('update.purchase_date') }}</span>
                            <span class="">{{ dateTimeFormat($purchasedTicket->paid_at, 'j M Y H:i') }}</span>
                        </div>

                        {{-- Event Type --}}
                        <div class="d-flex align-items-center justify-content-between mt-16">
                            <span class="text-gray-500">{{ trans('update.event_type') }}</span>
                            <span class="">{{ trans("update.{$event->type}") }}</span>
                        </div>

                        {{-- Event Date --}}
                        <div class="d-flex align-items-center justify-content-between mt-16">
                            <span class="text-gray-500">{{ trans('update.event_date') }}</span>
                            <span class="">{{ !empty($event->start_date) ? dateTimeFormat($event->start_date, 'j M Y H:i') : '-' }}</span>
                        </div>

                        @if($event->type == "in_person" and !empty($event->specificLocation))
                            @php
                                $specificLocationTitle = $event->specificLocation->getFullAddress(false, true, true, false, false)
                            @endphp

                            <div class="d-flex align-items-center justify-content-between mt-16">
                                <span class="text-gray-500">{{ trans('update.location') }}</span>
                                <span class="">{{ $specificLocationTitle }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Print --}}
            <div onclick="window.print()" class="mt-40 text-gray-500 cursor-pointer">{{ trans('update.print_ticket') }}</div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        var eventTicketDetailsLang = '{{ trans('update.event_ticket_details') }}';
    </script>

    <script src="{{ getDesign1ScriptPath("event_ticket_details") }}"></script>
@endpush
