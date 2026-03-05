<tr>
    <td class="text-left">
        {{ $discount->title }}
    </td>

    <td class="text-center">
        {{ trans("update.discount_source_{$discount->source}") }}
    </td>

    <td class="text-center">
        {{  $discount->amount ?  handlePrice($discount->amount) : '-' }}
    </td>

    <td class="text-center">
        <div class="">{{ $discount->count }}</div>
        <div class="font-12 text-gray-500 mt-4">{{ trans('admin/main.remain') }} : {{ $discount->discountRemain() }}</div>
    </td>

    <td class="text-center">
        {{  $discount->minimum_order ?  handlePrice($discount->minimum_order) : '-' }}
    </td>

    <td class="text-center">
        {{  $discount->max_amount ?  handlePrice($discount->max_amount) : '-' }}
    </td>

    @php
        $salesStats = $discount->salesStats();
    @endphp

    <td class="text-center">
        <div class="">{{ $salesStats['count'] }}</div>

        @if(!empty($salesStats['amount']))
            <div class="font-12 text-gray-500 mt-4">{{ handlePrice($salesStats['amount']) }}</div>
        @endif
    </td>

    <td class="text-center">
        {{ dateTimeFormat($discount->created_at, 'Y M d') }}
    </td>

    <td class="text-center">
        {{ dateTimeFormat($discount->expired_at, 'Y M d - H:i') }}
    </td>

    <td class="text-center">
        @if($discount->expired_at < time())
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-danger-20 text-danger">{{ trans('panel.expired') }}</span>
        @else
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-success-20 text-success">{{ trans('admin/main.active') }}</span>
        @endif
    </td>

    <td class="text-right">
        @if($discount->status != \App\Models\SpecialOffer::$inactive)
            <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                    <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                </button>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                    <ul class="my-8">

                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/marketing/discounts/{{ $discount->id }}/edit" class="">{{ trans('public.edit') }}</a>
                        </li>

                        @can('panel_marketing_delete_coupon')
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/marketing/discounts/{{ $discount->id }}/delete"
                                   class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        @endcan

                    </ul>
                </div>
            </div>
        @endif
    </td>
</tr>
