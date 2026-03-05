<tr>
    {{-- Student --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                <img src="{{ $certificate->student->getAvatar(48) }}" alt="" class="img-cover rounded-circle">
            </div>
            <div class="ml-12">
                <div class="">{{ $certificate->student->full_name }}</div>
                <span class="mt-4 font-12 text-gray-500">{{ $certificate->student->email }}</span>
            </div>
        </div>
    </td>

    {{-- Certificate ID --}}
    <td class="text-center">
        <span class="">{{ $certificate->id }}</span>
    </td>

    {{-- Certificate Reason --}}
    <td class="text-left">
        @if(!empty($certificate->quiz))
            <div class="d-flex align-items-center">
                @if(!empty($certificate->quiz->icon))
                    <div class="d-flex-center size-48 bg-gray-100">
                        <img src="{{ $certificate->quiz->icon }}" alt="" class="img-fluid">
                    </div>
                @else
                    <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                        <x-iconsax-bul-clipboard-tick class="icons text-primary" width="24px" height="24px"/>
                    </div>
                @endif

                <div class="ml-12">
                    <div class="">{{ $certificate->quiz->title }}</div>

                    @if(!empty($certificate->quiz->webinar))
                        <div class="mt-4 font-12 text-gray-500">{{ $certificate->quiz->webinar->title }}</div>
                    @endif
                </div>
            </div>
        @elseif(!empty($certificate->webinar) or !empty($certificate->bundle))
            @php
                $courseTitle = !empty($certificate->webinar) ? $certificate->webinar->title : $certificate->bundle->title;
                $courseUrl = !empty($certificate->webinar) ? $certificate->webinar->getUrl() : $certificate->bundle->getUrl();
            @endphp

            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                    <x-iconsax-bul-teacher class="icons text-primary" width="24px" height="24px"/>
                </div>
                <div class="ml-12">
                    <div class="">{{ trans('update.course_completion') }}</div>
                    <a href="{{ $courseUrl }}" target="_blank" class="mt-4 font-12 text-gray-500">{{ $courseTitle }}</a>
                </div>
            </div>
        @endif
    </td>

    {{-- Certificate Type --}}
    <td class="text-center">
        @if(!empty($certificate->quiz))
            <div class="d-inline-flex-center px-8 py-6 bg-gray-200 rounded-8 font-12 text-gray-500">{{ trans('quiz.quiz') }}</div>
        @else
            <div class="d-inline-flex-center px-8 py-6 bg-primary-30 rounded-8 font-12 text-primary">{{ trans('update.completion') }}</div>
        @endif
    </td>

    {{-- Certificate Date --}}
    <td class="text-center">
        <div class="text-center">
            <span class="d-block">{{ dateTimeFormat($certificate->created_at, 'j M Y H:i') }}</span>
            <span class="d-block mt-2 font-12 text-gray-500">{{ dateTimeFormat($certificate->created_at, 'j M Y H:i', 1) }}</span>
        </div>
    </td>

    {{-- Actions --}}
    <td class="text-right">

        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/certificates/students/{{ $certificate->id }}/show" class="">{{ trans('quiz.download_certificate') }}</a>
                    </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
