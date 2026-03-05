<tr>
    <td class="text-left">
        <div class="d-flex flex-column">
            @if(!empty($offlinePayment->offlineBank))
                <span class="">{{ $offlinePayment->offlineBank->title }}</span>
            @else
                <span class="">-</span>
            @endif
            <span class="font-12 text-gray-500 mt-2">{{ dateTimeFormat($offlinePayment->pay_date, 'j M Y H:i') }}</span>
        </div>
    </td>

    <td class="text-left">
        <span>{{ $offlinePayment->reference_number }}</span>
    </td>

    <td class="text-center">
        <span class="font-weight-bold">{{ handlePrice($offlinePayment->amount, false) }}</span>
    </td>

    <td class="text-center">
        @if(!empty($offlinePayment->attachment))
            <a href="{{ $offlinePayment->getAttachmentPath() }}" target="_blank" class="text-primary">{{ trans('public.view') }}</a>
        @else
            -
        @endif
    </td>


    <td class="text-center">
        @switch($offlinePayment->status)
            @case(\App\Models\OfflinePayment::$waiting)
                <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-warning-20 text-warning">{{ trans('public.waiting') }}</span>
                @break
            @case(\App\Models\OfflinePayment::$approved)
                <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-success-20 text-success">{{ trans('financial.approved') }}</span>
                @break
            @case(\App\Models\OfflinePayment::$reject)
                <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-danger-20 text-danger">{{ trans('public.rejected') }}</span>
                @break
        @endswitch
    </td>
    <td class="text-right">
        @if($offlinePayment->status != 'approved')
            <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                    <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                </button>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                    <ul class="my-8">

                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/financial/offline-payments/{{ $offlinePayment->id }}/edit" class="">{{ trans('public.edit') }}</a>
                        </li>

                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/financial/offline-payments/{{ $offlinePayment->id }}/delete" data-item-id="1" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                        </li>

                    </ul>
                </div>
            </div>
        @endif
    </td>
</tr>
