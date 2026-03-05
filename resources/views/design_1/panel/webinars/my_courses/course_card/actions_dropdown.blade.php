<div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
    <div class="d-flex-center size-40 bg-white border-gray-200 rounded-8 cursor-pointer">
        <x-iconsax-lin-more class="icons text-gray-500" width="24px" height="24px"/>
    </div>

    <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220">
        <ul class="my-8">

            @if($course->status == \App\Models\Webinar::$active)
                @can('panel_webinars_learning_page')
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="{{ $course->getLearningPageUrl() }}" target="_blank" class="">{{ trans('update.learning_page') }}</a>
                    </li>
                @endcan
            @endif

            @can('panel_webinars_create')
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/courses/{{ $course->id }}/edit" class="">{{ trans('public.edit') }}</a>
                </li>
            @endcan

            @if($course->isWebinar())
                @can('panel_webinars_create')
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/courses/{{ $course->id }}/step/4" class="">{{ trans('public.sessions') }}</a>
                    </li>
                @endcan
            @endif

            @can('panel_webinars_create')
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/courses/{{ $course->id }}/step/4" class="">{{ trans('public.files') }}</a>
                </li>
            @endcan

            @can('panel_webinars_export_students_list')
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/courses/{{ $course->id }}/export-students-list" class="">{{ trans('public.export_list') }}</a>
                </li>
            @endcan

            @if($authUser->id == $course->creator_id)
                @can('panel_webinars_duplicate')
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/courses/{{ $course->id }}/duplicate" class="">{{ trans('public.duplicate') }}</a>
                    </li>
                @endcan
            @endif

            @can('panel_webinars_statistics')
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/courses/{{ $course->id }}/statistics" class="">{{ trans('update.statistics') }}</a>
                </li>
            @endcan

            @if($course->creator_id == $authUser->id)
                @can('panel_webinars_delete')
                    <li class="actions-dropdown__dropdown-menu-item">
                        @include('design_1.panel.includes.content_delete_btn', [
                            'deleteContentUrl' => "/panel/courses/{$course->id}/delete",
                            'deleteContentClassName' => ' text-danger',
                            'deleteContentItem' => $course,
                            'deleteContentItemType' => "course",
                        ])
                    </li>
                @endcan
            @endif

        </ul>
    </div>
</div>
