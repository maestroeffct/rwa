@php
    $lastCertificate = $quizItem->certificates->first(); // OrderBy created_at desc in Controller
@endphp

<tr>
    {{-- Title --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            @if(!empty($quizItem->icon))
                <div class="d-flex-center size-48 bg-gray-100">
                    <img src="{{ $quizItem->icon }}" alt="" class="img-fluid">
                </div>
            @else
                <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                    <x-iconsax-bul-clipboard-tick class="icons text-primary" width="24px" height="24px"/>
                </div>
            @endif

            <div class="ml-12">
                <div class="">{{ $quizItem->title }}</div>

                @if(!empty($quizItem->webinar))
                    <div class="mt-4 font-12 text-gray-500">{{ $quizItem->webinar->title }}</div>
                @endif
            </div>
        </div>
    </td>

    {{-- Generated Certificates --}}
    <td class="text-center">
        <span class="">{{ $quizItem->certificates->count() }}</span>
    </td>

    {{-- Last Certificate --}}
    <td class="text-center">
        @if(!empty($lastCertificate))
            <div class="text-center">
                <span class="d-block">{{ dateTimeFormat($lastCertificate->created_at, 'j M Y H:i') }}</span>
                <span class="d-block mt-2 font-12 text-gray-500">{{ dateTimeFormat($lastCertificate->created_at, 'j M Y H:i', 1) }}</span>
            </div>
        @else
            -
        @endif
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
                        <a href="/panel/certificates/quiz/{{ $quizItem->id }}/details" target="_blank" class="">{{ trans('webinars.all_students') }}</a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/quizzes/{{ $quizItem->id }}/edit" target="_blank" class="">{{ trans('public.edit') }}</a>
                    </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
