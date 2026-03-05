<tr>
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="{{ $user->getAvatar() }}" class="img-cover rounded-circle" alt="{{ $user->full_name }}">
            </div>
            <div class=" ml-8">
                <span class="d-block">{{ $user->full_name }}</span>
                <span class="d-block font-12 text-{{ ($user->status == 'active') ? 'gray-500' : 'danger' }}">{{ ($user->status == 'active') ? trans('public.active') : trans('public.inactive') }}</span>
            </div>
        </div>
    </td>

    <td class="text-left">
        <div class="">
            <span class="d-block">{{ $user->email }}</span>
            <span class="d-block mt-4 font-12 text-gray-500">{{ trans('update.id') }}: {{ $user->id }}</span>
        </div>
    </td>

    <td class="text-center">
        <span class="">{{ $user->mobile ?? '-' }}</span>
    </td>

    <td class="text-center">
        <span class="">{{ $user->webinars->count() }}</span>
    </td>

    <td class="text-center">
        <span class="">{{ $user->salesCount() }}</span>
    </td>

    <td class="text-center">
        <span class="">{{ handlePrice($user->sales()) }}</span>
    </td>

    <td class="text-center">{{ dateTimeFormat($user->created_at,'j M Y | H:i') }}</td>

    <td class="text-right">
        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="{{ $user->getProfileUrl() }}" target="_blank" class="">{{ trans('public.profile') }}</a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/manage/students/{{ $user->id }}/edit" class="">{{ trans('public.edit') }}</a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/manage/students/{{ $user->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                    </li>

                </ul>
            </div>
        </div>
    </td>
</tr>
