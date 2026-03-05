<div class="actions-dropdown position-relative d-flex justify-content-end align-items-center mt-8">
    <div class="webinar-card-actions-btn d-flex-center size-40 rounded-8">
        <x-iconsax-lin-more class="icons text-white" width="24px" height="24px"/>
    </div>

    <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
        <ul class="my-8">

            @if($order->status == "open")
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/financial/installments/{{ $order->id }}/pay_upcoming_part" target="_blank" class="">{{ trans('update.pay_upcoming_part') }}</a>
                </li>
            @endif

            @if(!in_array($order->status, ['refunded', 'canceled']))
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/financial/installments/{{ $order->id }}/details" target="_blank" class="">{{ trans('update.view_details') }}</a>
                </li>
            @endif

            @if($itemType == "course" and ($order->isCompleted() or $order->status == "open"))
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="{{ $orderItem->getLearningPageUrl() }}" target="_blank" class="">{{ trans('update.learning_page') }}</a>
                </li>
            @endif

            {{--@if($order->isCompleted() or $order->status == "open")
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/financial/installments/{{ $order->id }}/refund" class=" delete-action">{{ trans('update.refund') }}</a>
                </li>
            @endif--}}

            @if($order->status == "pending_verification" and getInstallmentsSettings("allow_cancel_verification"))
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/financial/installments/{{ $order->id }}/cancel" class="text-danger delete-action" data-msg="{{ trans('public.deleteAlertHint') }}" data-confirm="{{ trans('update.yes_cancel') }}">{{ trans('public.cancel') }}</a>
                </li>
            @endif

        </ul>
    </div>
</div>
