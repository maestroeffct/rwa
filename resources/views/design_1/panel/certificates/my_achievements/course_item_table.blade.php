
<tr>
    {{-- Title --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                <x-iconsax-bul-teacher class="icons text-primary" width="24px" height="24px"/>
            </div>
            <div class="ml-12">
                <div class="">{{ trans('update.course_completion') }}</div>
                <a href="{{ $course->getUrl() }}" target="_blank" class="mt-4 font-12 text-gray-500">{{ $course->title }}</a>
            </div>
        </div>
    </td>

    {{-- Generated Certificates --}}
    <td class="text-center">
        <span class="">{{ !empty($course->lastCertificate) ? $course->lastCertificate->id : '-' }}</span>
    </td>

    {{-- Last Certificate --}}
    <td class="text-center">
        @if(!empty($course->lastCertificate))
            <div class="text-center">
                <span class="d-block">{{ dateTimeFormat($course->lastCertificate->created_at, 'j M Y H:i') }}</span>
                <span class="d-block mt-2 font-12 text-gray-500">{{ dateTimeFormat($course->lastCertificate->created_at, 'j M Y H:i', 1) }}</span>
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

                    @if(!empty($course->lastCertificate))
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/certificates/my-achievements/{{ $course->lastCertificate->id }}/show" class="">{{ trans('quiz.download_certificate') }}</a>
                        </li>
                    @endif

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="{{ $course->getUrl() }}" target="_blank" class="">{{ trans('update.view_course') }}</a>
                        </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
