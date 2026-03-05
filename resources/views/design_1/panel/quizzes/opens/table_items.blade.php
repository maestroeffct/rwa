<tr>
    <td class="text-left">
        <div class="user-inline-avatar d-flex align-items-center">
            <div class="size-48 bg-gray-200 rounded-circle">
                <img src="{{ $quiz->creator->getAvatar() }}" class="js-avatar-img img-cover rounded-circle" alt="">
            </div>
            <div class=" ml-8">
                <span class="d-block text-dark">{{ $quiz->creator->full_name }}</span>
                <span class="mt-4 font-12 text-gray-500 d-block">{{ $quiz->creator->email }}</span>
            </div>
        </div>
    </td>

    <td class="text-left">
        <span class="d-block text-dark">{{ $quiz->title }}</span>
        <span class="font-12 mt-4 text-gray-500 d-block">{{ $quiz->webinar->title }}</span>
    </td>

    <td class="text-center">{{ $quiz->quizQuestions->sum('grade') }}</td>


    <td class="text-center">{{ dateTimeFormat($quiz->created_at,'j M Y H:i')}}</td>

    <td class="text-right">

        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/quizzes/{{ $quiz->id }}/overview" class="">{{ trans('public.start') }}</a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="{{ $quiz->webinar->getUrl() }}" target="_blank" class="">{{ trans('webinars.webinar_page') }}</a>
                    </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
