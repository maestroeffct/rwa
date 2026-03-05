<tr>
    {{-- Student --}}
    <td class="text-left">
        <div class="user-inline-avatar d-flex align-items-center">
            <div class="avatar size-48 bg-gray-200 rounded-circle">
                <img src="{{ $meetingPackageSold->user->getAvatar(48) }}" class="js-avatar-img img-cover rounded-circle" alt="">
            </div>
            <div class=" ml-12">
                <span class="d-block ">{{ $meetingPackageSold->user->full_name }}</span>

                @if(!empty($meetingPackageSold->user->email))
                    <span class="mt-4 font-12 text-gray-500 d-block">{{ $meetingPackageSold->user->email }}</span>
                @endif
            </div>
        </div>
    </td>

    {{-- Meeting Package --}}
    <td class="text-center">
        {{ $meetingPackageSold->meetingPackage->title }}
    </td>

    {{-- Paid Amount --}}
    <td class="text-center">
        {{ ($meetingPackageSold->paid_amount > 0) ? handlePrice($meetingPackageSold->paid_amount) : trans('update.free') }}
    </td>

    {{-- Total Sessions --}}
    <td class="text-center">
        {{ $meetingPackageSold->sessions_count }}
    </td>

    {{-- Ended --}}
    <td class="text-center">
        {{ $meetingPackageSold->ended }}
    </td>

    {{-- Scheduled --}}
    <td class="text-center">
        {{ $meetingPackageSold->scheduled }}
    </td>

    {{-- Not Scheduled --}}
    <td class="text-center">
        {{ $meetingPackageSold->notScheduled }}
    </td>

    {{-- Purchase Date --}}
    <td class="text-center">
        <span class="">{{ dateTimeFormat($meetingPackageSold->paid_at, 'j M Y H:i') }}</span>
    </td>

    {{-- Expiry Date --}}
    <td class="text-center">
        <span class="">{{ dateTimeFormat($meetingPackageSold->expire_at, 'j M Y H:i') }}</span>
    </td>

    {{-- Status --}}
    <td class="text-center">
        @if($meetingPackageSold->status == "finished")
            <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 text-success bg-success-30">{{ trans('public.finished') }}</div>
        @else
            <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 text-warning bg-warning-30">{{ trans('public.open') }}</div>
        @endif
    </td>

    <td class="text-right">
        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/meetings/sold-packages/{{ $meetingPackageSold->id }}/sessions" target="_blank" class="">{{ trans('update.view_sessions') }}</a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <button type="button" data-path="/panel/meetings/sold-packages/{{ $meetingPackageSold->id }}/get-student-detail" data-title="{{ trans('update.student_information') }}" class="js-meeting-sold-package-student-detail">{{ trans('update.student_detail') }}</button>
                    </li>

                </ul>
            </div>
        </div>
    </td>

</tr>
