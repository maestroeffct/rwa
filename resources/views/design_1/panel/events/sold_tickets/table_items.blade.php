<tr>
    {{-- Participant --}}
    <td class="text-left">
        <div class="user-inline-avatar d-flex align-items-center">
            <div class="avatar size-48 bg-gray-200 rounded-circle">
                <img src="{{ $purchasedTicket->user->getAvatar(48) }}" class="js-avatar-img img-cover rounded-circle" alt="">
            </div>
            <div class=" ml-8">
                <span class="d-block ">{{ $purchasedTicket->user->full_name }}</span>

                @if(!empty($purchasedTicket->user->email))
                    <span class="mt-4 font-12 text-gray-500 d-block">{{ $purchasedTicket->user->email }}</span>
                @endif
            </div>
        </div>
    </td>

    {{-- Ticket Type --}}
    <td class="text-center">
        <span class="">{{ $purchasedTicket->eventTicket->title }}</span>
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
                        <a href="/panel/events/{{ $event->id }}/sold-tickets/{{ $purchasedTicket->id }}/details" target="_blank" class="">{{ trans('update.view_ticket') }}</a>
                    </li>

                </ul>
            </div>
        </div>
    </td>

</tr>
