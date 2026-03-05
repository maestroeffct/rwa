<tr>
    {{-- Title --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            @if(!empty($assignment->icon))
                <div class="d-flex-center size-48 ">
                    <img src="{{ $assignment->icon }}" alt="" class="img-fluid">
                </div>
            @else
                <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                    <x-iconsax-bul-teacher class="icons text-primary" width="24px" height="24px"/>
                </div>
            @endif

            <div class="ml-12">
                <div class="">{{ $assignment->title }}</div>

                @if(!empty($assignment->webinar))
                    <div class="mt-4 font-12 text-gray-500">{{ $assignment->webinar->title }}</div>
                @endif
            </div>
        </div>
    </td>

    {{-- Min Grade --}}
    <td class="text-center">
        <span class="">{{ $assignment->min_grade ?? '-' }}</span>
    </td>

    {{-- Average Grade --}}
    <td class="text-center">
        <span class="">{{ $assignment->average_grade ?? '-' }}</span>
    </td>

    {{-- Submissions --}}
    <td class="text-center">
        <span class="">{{ $assignment->submissions }}</span>
    </td>

    {{-- Pending --}}
    <td class="text-center">
        <span class="">{{ $assignment->pendingCount }}</span>
    </td>

    {{-- Passed --}}
    <td class="text-center">
        <span>{{ $assignment->passedCount }}</span>
    </td>

    {{-- Failed --}}
    <td class="text-center">
        <span>{{ $assignment->failedCount }}</span>
    </td>

    {{-- Last Submission --}}
    <td class="text-center">
        @if(!empty($assignment->lastSubmission))
            <div class="text-center">
                <span class="d-block">{{ dateTimeFormat($assignment->lastSubmission->created_at, 'j M Y H:i') }}</span>
                <span class="d-block mt-2 font-12 text-gray-500">{{ dateTimeFormat($assignment->lastSubmission->created_at, 'j M Y H:i', 1) }}</span>
            </div>
        @else
            -
        @endif
    </td>

    {{-- Status --}}
    <td class="text-center">
        @if($assignment->status == "active")
            <div class="d-inline-flex-center px-8 py-6 rounded-8 bg-success-30 font-12 text-success">{{ trans('public.active') }}</div>
        @else
            <div class="d-inline-flex-center px-8 py-6 rounded-8 bg-danger-30 font-12 text-danger">{{ trans('public.inactive') }}</div>
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

                    @can('panel_assignments_students')
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/assignments/{{ $assignment->id }}/students?status=pending" target="_blank" class="">{{ trans('update.pending_submissions') }}</a>
                        </li>

                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/assignments/{{ $assignment->id }}/students" target="_blank" class="">{{ trans('update.all_submissions') }}</a>
                        </li>
                    @endcan

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/courses/{{ $assignment->webinar_id }}/step/4" target="_blank" class="">{{ trans('public.edit') }}</a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="{{ $assignment->webinar->getUrl() }}" target="_blank" class="">{{ trans('update.view_course') }}</a>
                    </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
