<tr>
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 rounded-12 border-gray-200 border-dashed">
                @if($accounting->type == \App\Models\Accounting::$addiction)
                    <x-iconsax-bol-money-recive class="icons text-gray-400" width="24px" height="24px"/>
                @else
                    <x-iconsax-bol-money-send class="icons text-gray-400" width="24px" height="24px"/>
                @endif
            </div>

            <div class="d-flex flex-column ml-16">
                <div class="font-14">
                    @if($accounting->is_cashback)
                        {{ trans('update.cashback') }}
                    @elseif(!empty($accounting->webinar_id) and !empty($accounting->webinar))
                        {{ $accounting->webinar->title }}
                    @elseif(!empty($accounting->bundle_id) and !empty($accounting->bundle))
                        {{ $accounting->bundle->title }}
                    @elseif(!empty($accounting->event_ticket_id) and !empty($accounting->eventTicket))
                        {{ $accounting->eventTicket->event->title }}
                    @elseif(!empty($accounting->meeting_package_id))
                        {{ trans('update.meeting_package') }}
                    @elseif(!empty($accounting->product_id) and !empty($accounting->product))
                        {{ $accounting->product->title }}
                    @elseif(!empty($accounting->meeting_time_id))
                        {{ trans('meeting.reservation_appointment') }}
                    @elseif(!empty($accounting->subscribe_id) and !empty($accounting->subscribe))
                        {{ $accounting->subscribe->title }}
                    @elseif(!empty($accounting->promotion_id) and !empty($accounting->promotion))
                        {{ $accounting->promotion->title }}
                    @elseif(!empty($accounting->registration_package_id) and !empty($accounting->registrationPackage))
                        {{ $accounting->registrationPackage->title }}
                    @elseif(!empty($accounting->installment_payment_id))
                        {{ trans('update.installment') }}
                    @elseif($accounting->store_type == \App\Models\Accounting::$storeManual)
                        {{ trans('financial.manual_document') }}
                    @elseif($accounting->type == \App\Models\Accounting::$addiction and $accounting->type_account == \App\Models\Accounting::$asset)
                        {{ trans('financial.charge_account') }}
                    @elseif($accounting->type == \App\Models\Accounting::$deduction and $accounting->type_account == \App\Models\Accounting::$income)
                        {{ trans('financial.payout') }}
                    @elseif($accounting->is_registration_bonus)
                        {{ trans('update.registration_bonus') }}
                    @else
                        ---
                    @endif
                </div>

                @if(!empty($accounting->gift_id) and !empty($accounting->gift))
                    <div class="text-gray-500 font-12">{!! trans('update.a_gift_for_name_on_date',['name' => $accounting->gift->name, 'date' => dateTimeFormat($accounting->gift->date, 'j M Y H:i')]) !!}</div>
                @endif

                <div class="font-12 text-gray-500">
                    @if(!empty($accounting->webinar_id) and !empty($accounting->webinar))
                        #{{ $accounting->webinar->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->webinar->title : '' }}
                    @elseif(!empty($accounting->bundle_id) and !empty($accounting->bundle))
                        #{{ $accounting->bundle->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->bundle->title : '' }}
                    @elseif(!empty($accounting->event_ticket_id) and !empty($accounting->eventTicket))
                        #{{ $accounting->eventTicket->id }} ({{ $accounting->eventTicket->title }})
                    @elseif(!empty($accounting->meeting_package_id) and !empty($accounting->meetingPackage))
                        #{{ $accounting->meeting_package_id }} ({{ $accounting->meetingPackage->title }})
                    @elseif(!empty($accounting->product_id) and !empty($accounting->product))
                        #{{ $accounting->product->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->product->title : '' }}
                    @elseif(!empty($accounting->meeting_time_id) and !empty($accounting->meetingTime))
                        {{ $accounting->meetingTime->meeting->creator->full_name }}
                    @elseif(!empty($accounting->subscribe_id) and !empty($accounting->subscribe))
                        {{ $accounting->subscribe->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->subscribe->title : '' }}
                    @elseif(!empty($accounting->promotion_id) and !empty($accounting->promotion))
                        {{ $accounting->promotion->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->promotion->title : '' }}
                    @elseif(!empty($accounting->registration_package_id) and !empty($accounting->registrationPackage))
                        {{ $accounting->registrationPackage->id }}{{ ($accounting->is_cashback) ? '-'.$accounting->registrationPackage->title : '' }}
                    @elseif(!empty($accounting->installment_payment_id))
                        @php
                            $installmentItemTitle = "--";
                            $installmentOrderPayment = $accounting->installmentOrderPayment;

                            if (!empty($installmentOrderPayment)) {
                                $installmentOrder = $installmentOrderPayment->installmentOrder;
                                if (!empty($installmentOrder)) {
                                    $installmentItem = $installmentOrder->getItem();
                                    if (!empty($installmentItem)) {
                                        $installmentItemTitle = $installmentItem->title;
                                    }
                                }
                            }
                        @endphp
                        {{ $installmentItemTitle }}
                    @else
                        ---
                    @endif
                </div>
            </div>
        </div>
    </td>

    <td class="text-left">
        <span class="">{{ $accounting->description }}</span>
    </td>

    <td class="text-center">
        @switch($accounting->type)
            @case(\App\Models\Accounting::$addiction)
                <span class="font-14 font-weight-bold text-dark">+{{ handlePrice($accounting->amount, false) }}</span>
                @break;
            @case(\App\Models\Accounting::$deduction)
                <span class="font-14 font-weight-bold text-danger">-{{ handlePrice($accounting->amount, false) }}</span>
                @break;
        @endswitch
    </td>

    <td class="text-center">{{ trans('public.'.$accounting->store_type) }}</td>

    <td class="text-center">
        <span>{{ dateTimeFormat($accounting->created_at, 'j M Y H:i') }}</span>
    </td>
</tr>
