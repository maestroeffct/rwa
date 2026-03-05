<div class="actions-dropdown position-relative d-flex justify-content-end align-items-center mt-8">
    <div class="webinar-card-actions-btn d-flex-center size-40 rounded-8">
        <x-iconsax-lin-more class="icons text-white" width="24px" height="24px"/>
    </div>

    <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
        <ul class="my-8">

            @if(!empty($upcomingCourse->webinar_id))
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="{{ $upcomingCourse->webinar->getUrl() }}" class="">{{ trans('update.view_course') }}</a>
                </li>
            @else
                @if($upcomingCourse->status == \App\Models\UpcomingCourse::$isDraft)
                    @can('panel_upcoming_courses_create')
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/upcoming_courses/{{ $upcomingCourse->id }}/step/4" class="">{{ trans('update.send_for_reviewer') }}</a>
                        </li>
                    @endcan
                @elseif($upcomingCourse->status == \App\Models\UpcomingCourse::$active)
                    <li class="actions-dropdown__dropdown-menu-item">
                        <button type="button" data-id="{{ $upcomingCourse->id }}" class="js-mark-as-released ">{{ trans('update.mark_as_released') }}</button>
                    </li>
                @endif

                @can('panel_upcoming_courses_create')
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/upcoming_courses/{{ $upcomingCourse->id }}/edit" class="">{{ trans('public.edit') }}</a>
                    </li>
                @endcan
            @endif

            @if($upcomingCourse->status == \App\Models\UpcomingCourse::$active)
                @can('panel_upcoming_courses_followers')
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/upcoming_courses/{{ $upcomingCourse->id }}/followers" class="">{{ trans('update.view_followers') }}</a>
                    </li>
                @endcan
            @endif

            @if($upcomingCourse->creator_id == $authUser->id)
                @can('panel_upcoming_courses_delete')
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/upcoming_courses/{{ $upcomingCourse->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                    </li>
                @endcan
            @endif

        </ul>
    </div>
</div>
