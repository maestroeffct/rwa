<tr>
    {{-- Title --}}
    <td class="text-left">
        <div class="d-flex flex-column">
            <span class="">{{ $meetingPackage->title }}</span>
            <span class="mt-4 font-12 text-gray-500">{{ trans('update.n_sessions', ['count' => $meetingPackage->sessions]) }} ({{ convertMinutesToHourAndMinute($meetingPackage->session_duration) }} {{ trans('home.hours') }})</span>
        </div>
    </td>

    {{-- Duration --}}
    <td class="text-center">
        <span class="">{{ $meetingPackage->duration }} {{ trans("update.{$meetingPackage->duration_type}") }}</span>
    </td>

    {{-- Price --}}
    <td class="text-center">
        @if(empty($meetingPackage->price) or $meetingPackage->price <=0)
            <span class="">{{ trans('update.free') }}</span>
        @else
            @php
                $packagePrices = $meetingPackage->getPrices();
            @endphp

            <div class="text-center">
                <span class="d-block">{{ !empty($packagePrices['price']) ? handlePrice($packagePrices['price']) : trans('update.free') }}</span>

                @if($meetingPackage->discount > 0)
                    <span class="d-block font-12 text-gray-500 mt-2 text-decoration-line-through">{{ !empty($packagePrices['real_price']) ? handlePrice($packagePrices['real_price']) : trans('update.free') }}</span>
                @endif
            </div>
        @endif
    </td>

    {{-- Sales --}}
    <td class="text-center">
        @if($meetingPackage->sales->count() > 0)
            <div class="d-flex-center flex-column text-center">
                <span class="">{{ $meetingPackage->sales->count() }}</span>
                <span class="mt-4 font-12 text-gray-500">{{ handlePrice($meetingPackage->sales->sum("paid_amount")) }}</span>
            </div>
        @else
            -
        @endif
    </td>

    {{-- Status --}}
    <td class="text-center">
        @if($meetingPackage->enable)
            <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 bg-success-30 text-success">{{ trans('panel.active') }}</div>
        @else
            <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 bg-danger-30 text-danger">{{ trans('public.disabled') }}</div>
        @endif
    </td>

    {{-- Actions --}}
    <td class="text-right">
        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/meetings/settings?tab=packages&package={{ $meetingPackage->id }}" class="">{{ trans('public.edit') }}</a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/meetings/packages/{{ $meetingPackage->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                    </li>

                </ul>
            </div>
        </div>
    </td>

</tr>
