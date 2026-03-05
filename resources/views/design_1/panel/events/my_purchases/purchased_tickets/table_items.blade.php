<tr>
    {{-- Ticket --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-gray-300 rounded-circle">
                <x-iconsax-bul-ticket class="icons text-gray-500" width="24px" height="24px"/>
            </div>
            <div class=" ml-12">
                <span class="d-block ">{{ $purchasedTicket->eventTicket->title }}</span>
            </div>
        </div>
    </td>

    {{-- Paid Amount --}}
    <td class="text-center">
        <span class="">{{ ($purchasedTicket->paid_amount > 0) ? handlePrice($purchasedTicket->paid_amount) : trans('update.free') }}</span>
    </td>

    {{-- Ticket Code --}}
    <td class="text-center">
        <span>{{ $purchasedTicket->code }}</span>
    </td>

    {{-- Purchase Date --}}
    <td class="text-center">
        <span class="">{{ dateTimeFormat($purchasedTicket->paid_at, 'j M Y H:i') }}</span>
    </td>


    <td class="text-right">
        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/events/my-purchases/{{ $event->id }}/tickets/{{ $purchasedTicket->event_ticket_id }}/details" target="_blank" class="">{{ trans('update.view_ticket') }}</a>
                    </li>

                </ul>
            </div>
        </div>
    </td>

</tr>
