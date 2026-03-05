@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush

<div class="bg-white rounded-16 p-16 mt-32">
    <div class="d-flex align-items-center p-12 rounded-16 border-dashed border-gray-300">
        <div class="d-flex-center size-48 bg-primary-20 rounded-12">
            <x-iconsax-bul-ticket class="text-primary" width="24px" height="24px"/>
        </div>
        <div class="ml-8">
            <h4 class="font-14">{{ trans('public.tickets') }}</h4>
            <p class="font-12 mt-4 text-gray-500">{{ trans('update.define_various_ticket_options_with_custom_pricing_and_availability') }}</p>
        </div>
    </div>

    <div class="mt-16 p-16 rounded-16 border-gray-300 bg-gray-100">
        <p class="font-12 text-gray-500">{{ trans('update.event_wizard_tickets_hints_line_1') }}</p>
        <p class="font-12 text-gray-500 mt-12">{{ trans('update.event_wizard_tickets_hints_line_2') }}</p>
        <p class="font-12 text-gray-500 mt-12">{{ trans('update.event_wizard_tickets_hints_line_3') }}</p>
    </div>


    <div class="row">
        <div class="col-lg-6">
            @include('design_1.panel.events.create.includes.accordions.ticket')
        </div>

        <div class="col-lg-6 mt-16">
            @if(!empty($event->tickets) and count($event->tickets))
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold">{{ trans('public.tickets') }}</h3>

                    <ul class="draggable-content-lists ticket-draggable-lists" data-path="/panel/events/{{ $event->id }}/tickets/order-items" data-drag-class="ticket-draggable-lists">
                        @foreach($event->tickets as $ticketInfo)
                            @include('design_1.panel.events.create.includes.accordions.ticket',['ticket' => $ticketInfo])
                        @endforeach
                    </ul>

                </div>
            @else
                <div class="d-flex-center flex-column px-32 py-120 text-center rounded-16 border-gray-200">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-ticket-expired class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.event_ticket_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('update.event_ticket_no_result_hint') !!}</p>
                </div>
            @endif
        </div>
    </div>

</div>


@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
@endpush
