<tr>
    <td class="text-left">
        <div class="user-inline-avatar d-flex align-items-center">
            <div class="size-48 bg-gray-200 rounded-circle">
                <img src="{{ !empty($order->buyer) ? $order->buyer->getAvatar() : '' }}" class="img-cover rounded-circle" alt="">
            </div>
            <div class=" ml-4">
                <span class="d-block">{{ !empty($order->buyer) ? $order->buyer->full_name : '' }}</span>
                <span class="mt-4 font-12 text-gray-500 d-block">{{ !empty($order->buyer) ? $order->buyer->email : '' }}</span>
            </div>
        </div>
    </td>

    <td class=" text-left">
        <span class="d-block font-weight-bold">{{ $order->id }}</span>
        <span class="d-block font-12 text-gray-500">{{ $order->quantity }} {{ trans('update.product') }}</span>
    </td>

    <td class="text-center">
        <span>{{ handlePrice($order->sale->amount) }}</span>
    </td>

    <td class="text-center">{{ handlePrice($order->sale->discount ?? 0) }}</td>

    <td class="text-center">
        <span>{{ handlePrice($order->sale->total_amount) }}</span>
    </td>

    <td class="text-center">
        <span>{{ handlePrice($order->sale->getIncomeItem()) }}</span>
    </td>

    <td class="text-center">
        @if(!empty($order) and !empty($order->product))
            <span>{{ trans('update.product_type_'.$order->product->type) }}</span>
        @endif
    </td>

    <td class="text-center">
        @if(!empty($order))
            @if($order->status == \App\Models\ProductOrder::$waitingDelivery)
                <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-warning-30 font-12 text-warning">{{ trans('update.product_order_status_waiting_delivery') }}</span>
            @elseif($order->status == \App\Models\ProductOrder::$success)
                <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-success-30 font-12 text-success">{{ trans('update.product_order_status_success') }}</span>
            @elseif($order->status == \App\Models\ProductOrder::$shipped)
                <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-primary-30 font-12 text-primary">{{ trans('update.product_order_status_shipped') }}</span>
            @elseif($order->status == \App\Models\ProductOrder::$canceled)
                <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-danger-30 font-12 text-danger">{{ trans('update.product_order_status_canceled') }}</span>
            @endif
        @endif
    </td>

    <td class="text-center">
        <span>{{ dateTimeFormat($order->created_at, 'j M Y H:i') }}</span>
    </td>

    <td class="text-right">

        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/store/sales/{{ $order->sale_id }}/productOrder/{{ $order->id }}/invoice" class="" target="_blank">{{ trans('public.invoice') }}</a>
                    </li>


                    @if($order->status == \App\Models\ProductOrder::$waitingDelivery)
                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" data-sale-id="{{ $order->sale_id }}" data-product-order-id="{{ $order->id }}" class="js-enter-tracking-code ">{{ trans('update.enter_tracking_code') }}</button>
                        </li>
                    @endif

                </ul>
            </div>
        </div>

    </td>

</tr>
