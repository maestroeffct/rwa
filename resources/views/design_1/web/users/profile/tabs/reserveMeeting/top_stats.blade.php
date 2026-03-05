<div class="d-flex flex-column flex-lg-row align-items-lg-center mt-16 p-24 gap-20 gap-lg-32 rounded-16 bg-gray-100">

    {{-- Nearest Time --}}
    <div class="d-flex align-items-center pr-lg-24 border-right-gray-300-lg">
        <div class="d-flex-center size-40 rounded-circle bg-gray-200">
            <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
        </div>

        <div class="ml-8">
            <div class="font-12 text-gray-500">{{ trans('update.nearest_time') }}</div>
            <div class="font-14 font-weight-bold text-gray-500 mt-4">{{ !empty($nearestDayDate) ? $nearestDayDate : '-' }}</div>
        </div>
    </div>

    {{-- Hourly Charge --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 rounded-circle bg-gray-200">
            <x-iconsax-lin-moneys class="icons text-gray-500" width="20px" height="20px"/>
        </div>

        <div class="ml-8">
            <div class="font-12 text-gray-500">{{ trans('update.hourly_charge') }}</div>

            <div class="d-flex align-items-center font-14 font-weight-bold text-gray-500 mt-4">
                @if(!empty($meeting->amount) and $meeting->amount > 0)
                    @if(!empty($meeting->discount))
                        <span class="font-12 font-weight-500 text-decoration-line-through">{{ handlePrice($meeting->amount, true, true, false, null, true) }}</span>
                        <span class="ml-4">{{ handlePrice($meeting->amount - (($meeting->amount * $meeting->discount) / 100), true, true, false, null, true) }}</span>
                    @else
                        <span class="">{{ handlePrice($meeting->amount, true, true, false, null, true) }}</span>
                    @endif
                @else
                    <span class="">{{ trans('public.free') }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- In Person Charge --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 rounded-circle bg-gray-200">
            <x-iconsax-lin-location class="icons text-gray-500" width="20px" height="20px"/>
        </div>

        <div class="ml-8">
            <div class="font-12 text-gray-500">{{ trans('update.in_person_charge') }}</div>

            <div class="d-flex align-items-center font-14 font-weight-bold text-gray-500 mt-4">
                @if(!empty($meeting->in_person_amount) and $meeting->in_person_amount > 0)
                    @if(!empty($meeting->discount))
                        <span class="font-12 font-weight-500 text-decoration-line-through">{{ handlePrice($meeting->in_person_amount, true, true, false, null, true) }}</span>
                        <span class="ml-4">{{ handlePrice($meeting->in_person_amount - (($meeting->in_person_amount * $meeting->discount) / 100), true, true, false, null, true) }}</span>
                    @else
                        <span class="">{{ handlePrice($meeting->in_person_amount, true, true, false, null, true) }}</span>
                    @endif
                @else
                    <span class="">{{ trans('public.free') }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Group Meeting Charge --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 rounded-circle bg-gray-200">
            <x-iconsax-lin-profile-2user class="icons text-gray-500" width="20px" height="20px"/>
        </div>

        <div class="ml-8">
            <div class="font-12 text-gray-500">{{ trans('update.group_meeting_charge') }}</div>

            <div class="d-flex align-items-center font-14 font-weight-bold text-gray-500 mt-4">
                @if(!empty($meeting->min_group_amount) and $meeting->min_group_amount > 0)
                    @if(!empty($meeting->discount))
                        <span class="font-12 font-weight-500 text-decoration-line-through">{{ handlePrice($meeting->min_group_amount, true, true, false, null, true) }}</span>
                        <span class="ml-4">{{ handlePrice($meeting->min_group_amount - (($meeting->min_group_amount * $meeting->discount) / 100), true, true, false, null, true) }}</span>
                    @else
                        <span class="">{{ handlePrice($meeting->min_group_amount, true, true, false, null, true) }}</span>
                    @endif
                @else
                    <span class="">{{ trans('public.free') }}</span>
                @endif

                <span class="">/{{ trans('update.seat') }}</span>
            </div>
        </div>
    </div>

</div>
