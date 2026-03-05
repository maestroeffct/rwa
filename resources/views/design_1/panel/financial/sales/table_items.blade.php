<tr>
    <td class="text-left">
        @if(!empty($sale->buyer))
            <div class="user-inline-avatar d-flex align-items-center">
                <div class="size-48 bg-gray-200 rounded-circle">
                    <img src="{{ $sale->buyer->getAvatar() }}" class="js-avatar-img img-cover rounded-circle" alt="">
                </div>

                <div class=" ml-8">
                    <span class="d-block">{{ $sale->buyer->full_name }}</span>
                    <span class="mt-4 font-12 text-gray-500 d-block">{{ $sale->buyer->email }}</span>
                </div>
            </div>
        @else
            <span class="text-danger">{{ trans('update.deleted_user') }}</span>
        @endif
    </td>

    <td class="text-left">
        <div class="text-left">
            @php
                $content = trans('update.deleted_item');
                $contentId = null;

                if(!empty($sale->webinar)) {
                    $content = $sale->webinar->title;
                    $contentId =$sale->webinar->id;
                } elseif(!empty($sale->bundle)) {
                    $content = $sale->bundle->title;
                    $contentId =$sale->bundle->id;
                } elseif(!empty($sale->productOrder) and !empty($sale->productOrder->product)) {
                    $content = $sale->productOrder->product->title;
                    $contentId =$sale->productOrder->product->id;
                } elseif(!empty($sale->registrationPackage)) {
                    $content = $sale->registrationPackage->title;
                    $contentId =$sale->registrationPackage->id;
                } elseif(!empty($sale->subscribe)) {
                    $content = $sale->subscribe->title;
                    $contentId =$sale->subscribe->id;
                } elseif(!empty($sale->promotion)) {
                    $content = $sale->promotion->title;
                    $contentId =$sale->promotion->id;
                } elseif (!empty($sale->meeting_id)) {
                    $content = trans('meeting.reservation_appointment');
                }
            @endphp

            <span class="d-block">{{ $content }}</span>

            @if(!empty($contentId))
                <span class="d-block font-12 text-gray-500">Id: {{ $contentId }}</span>
            @endif
        </div>
    </td>

    <td class="text-center">
        @if($sale->payment_method == \App\Models\Sale::$subscribe)
            <span class="">{{ trans('financial.subscribe') }}</span>
        @else
            <span>{{ !empty($sale->amount) ? handlePrice($sale->amount) : '-' }}</span>
        @endif
    </td>

    <td class="text-center">{{ !empty($sale->discount) ? handlePrice($sale->discount) : '-' }}</td>

    <td class="text-center">
        @if($sale->payment_method == \App\Models\Sale::$subscribe)
            <span class="">{{ trans('financial.subscribe') }}</span>
        @else
            <span>{{ !empty($sale->total_amount) ? handlePrice($sale->total_amount) : '-' }}</span>
        @endif
    </td>

    <td class="text-center">
        <span>{{ !empty($sale->getIncomeItem()) ? handlePrice($sale->getIncomeItem()) : '-' }}</span>
    </td>

    <td class="text-center">
        @switch($sale->type)
            @case(\App\Models\Sale::$webinar)
                @if(!empty($sale->webinar))
                    <span>{{ trans('webinars.'.$sale->webinar->type) }}</span>
                @else
                    <span>{{ trans('update.class') }}</span>
                @endif
                @break;
            @case(\App\Models\Sale::$meeting)
                <span>{{ trans('meeting.appointment') }}</span>
                @break;
            @case(\App\Models\Sale::$subscribe)
                <span>{{ trans('financial.subscribe') }}</span>
                @break;
            @case(\App\Models\Sale::$promotion)
                <span>{{ trans('panel.promotion') }}</span>
                @break;
            @case(\App\Models\Sale::$registrationPackage)
                <span>{{ trans('update.registration_package') }}</span>
                @break;
            @case(\App\Models\Sale::$bundle)
                <span>{{ trans('update.bundle') }}</span>
                @break;
            @case(\App\Models\Sale::$product)
                <span>{{ trans('update.product') }}</span>
                @break;
        @endswitch
    </td>

    <td class="text-center">
        <span>{{ dateTimeFormat($sale->created_at, 'j M Y H:i') }}</span>
    </td>

</tr>
