<tr>
    {{-- Event --}}
    @if(empty($selectedEvent))
        <td class="text-left">
            <a href="{{ $purchasedTicket->eventTicket->event->getUrl() }}" target="_blank" class="">
                <div class="d-flex align-items-center">
                    <div class="size-48">
                        <img src="{{ $purchasedTicket->eventTicket->event->getIcon() }}" alt="" class="img-cover">
                    </div>
                    <div class="ml-8">
                        <div class="text-dark">{{ $purchasedTicket->eventTicket->event->title }}</div>

                        @if(!empty($purchasedTicket->eventTicket->event->category))
                            <div class="font-12 mt-4 text-gray-500">{{ $purchasedTicket->eventTicket->event->category->title }}</div>
                        @endif
                    </div>
                </div>
            </a>
        </td>
    @endif

    {{-- Participant --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle">
                <img src="{{ $purchasedTicket->user->getAvatar(48) }}" alt="" class="img-cover rounded-circle">
            </div>
            <div class="ml-8">
                <div class="text-dark">{{ $purchasedTicket->user->full_name }}</div>

                @if(!empty($purchasedTicket->user->email))
                    <div class="font-12 text-gray-500">{{ $purchasedTicket->user->email }}</div>
                @endif

                @if(!empty($purchasedTicket->user->mobile))
                    <div class="font-12 text-gray-500">+{{ $purchasedTicket->user->mobile }}</div>
                @endif
            </div>
        </div>
    </td>

    {{-- Ticket Type --}}
    <td>
        <span class="">{{ $purchasedTicket->eventTicket->title }}</span>
    </td>

    {{-- Paid Amount --}}
    <td>
        <span class="">{{ ($purchasedTicket->paid_amount > 0) ? handlePrice($purchasedTicket->paid_amount) : trans('update.free') }}</span>
    </td>

    {{-- Ticket Code --}}
    <td>
        <span>{{ $purchasedTicket->code }}</span>
    </td>

    {{-- Purchase Date --}}
    <td>
        <span class="">{{ dateTimeFormat($purchasedTicket->paid_at, 'j M Y H:i') }}</span>
    </td>

    {{-- Actions --}}
    <td>
        <div class="btn-group dropdown table-actions position-relative">
            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
            </button>

            <div class="dropdown-menu dropdown-menu-right">

                <a href="{{ getAdminPanelUrl("/events/sold-tickets/{$purchasedTicket->id}/details") }}" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-ticket class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('update.view_ticket') }}</span>
                </a>
            </div>
        </div>
    </td>
</tr>
