<tr>
    <td class="text-left" width="25%">
        <div class="">{{ $specialOffer->name }}</div>

        <a href="{{ $specialOffer->webinar->getUrl() }}" class="font-12 text-gray-500 mt-2" target="_blank">{{ $specialOffer->webinar->title }}</a>
    </td>

    {{-- Amount --}}
    <td class="text-center">
        <span class="font-weight-bold">{{ $specialOffer->percent }}%</span>
    </td>

    {{-- Date Range --}}
    <td class="text-center">
        <div class="">{{ dateTimeFormat($specialOffer->from_date, 'j M Y') }} - {{ dateTimeFormat($specialOffer->to_date, 'j M Y') }}</div>
    </td>

    {{-- Status --}}
    <td class="text-center">
        @if($specialOffer->status == \App\Models\SpecialOffer::$inactive)
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-danger-20 text-danger">{{ trans('public.disabled') }}</span>
        @else
            <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-success-20 text-success">{{ trans('admin/main.active') }}</span>
        @endif
    </td>

    {{-- Actions --}}
    <td class="text-right">
        @if($specialOffer->status != \App\Models\SpecialOffer::$inactive)
            <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                    <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                </button>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                    <ul class="my-8">

                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/marketing/special_offers/{{ $specialOffer->id }}/disable" class="delete-action">{{ trans('public.disable') }}</a>
                        </li>

                    </ul>
                </div>
            </div>
        @endif
    </td>

</tr>
