@if(!empty($purchasedTicket))
    <div class="d-flex-center flex-column text-center mt-36">
        <div class="d-flex-center size-72 rounded-16 bg-gray">
            <div class="d-flex-center size-64 rounded-16 bg-success ">
                <x-iconsax-bul-tick-circle class="icons text-white" width="32px" height="32px"/>
            </div>
        </div>

        <h4 class="mt-8 font-14 font-weight-bold">{{ trans('update.valid_ticket') }}</h4>
        <p class="mt-4 font-12 text-gray-500">{{ trans('update.this_ticket_is_valid_and_includes_the_following_details') }}</p>
    </div>

    <div class="w-100 mt-16 rounded-12 border-gray-200 p-16 mb-16">
        {{-- Ticket Code --}}
        <div class="d-flex align-items-center justify-content-between">
            <span class="text-gray-500">{{ trans('update.ticket_code') }}</span>
            <span class="">{{ $purchasedTicket->code }}</span>
        </div>

        {{-- Ticket Type --}}
        <div class="d-flex align-items-center justify-content-between mt-12">
            <span class="text-gray-500">{{ trans('update.ticket_type') }}</span>
            <span class="">{{ $purchasedTicket->eventTicket->title }}</span>
        </div>

        {{-- Paid Amount --}}
        <div class="d-flex align-items-center justify-content-between mt-12">
            <span class="text-gray-500">{{ trans('public.paid_amount') }}</span>
            <span class="">{{ handlePrice($purchasedTicket->paid_amount) }}</span>
        </div>

        {{-- Participant --}}
        <div class="d-flex align-items-center justify-content-between mt-12">
            <span class="text-gray-500">{{ trans('update.participant') }}</span>
            <span class="">{{ $purchasedTicket->user->full_name }}</span>
        </div>

        {{-- Purchase Date --}}
        <div class="d-flex align-items-center justify-content-between mt-12">
            <span class="text-gray-500">{{ trans('update.purchase_date') }}</span>
            <span class="">{{ dateTimeFormat($purchasedTicket->paid_at, 'j M Y H:i') }}</span>
        </div>

        {{-- Event --}}
        <div class="d-flex align-items-center justify-content-between gap-32 mt-12">
            <span class="text-gray-500">{{ trans('update.event') }}</span>
            <span class="flex-1 text-ellipsis text-right">{{ $event->title }}</span>
        </div>

    </div>
@else
    <div class="d-flex-center flex-column text-center py-32">
        <div class="d-flex-center size-72 rounded-16 bg-gray">
            <div class="d-flex-center size-64 rounded-16 bg-danger ">
                <x-iconsax-bul-close-circle class="icons text-white" width="32px" height="32px"/>
            </div>
        </div>

        <h4 class="mt-8 font-14 font-weight-bold">{{ trans('update.invalid_ticket') }}</h4>
        <p class="mt-4 font-12 text-gray-500">{{ trans('update.ticket_not_recognized_please_try_a_different_one') }}</p>
    </div>
@endif
