<tr class="noticeboard-item">
    <td class="text-left align-middle" width="25%">
        <span class="js-noticeboard-title d-block">{{ $noticeboard->title }}</span>

        @if(!empty($noticeboard->webinar))
            <span class="d-block text-gray-500 font-12">{{ $noticeboard->webinar->title }}</span>
        @endif
    </td>

    <td class="text-center">
        <button type="button" class="js-view-message btn btn-sm bg-gray-200">{{ trans('public.view') }}</button>

        <input type="hidden" class="js-noticeboard-message" value="{{ nl2br($noticeboard->message) }}">
    </td>

    <td class="text-center">
        @if(!empty($isCourseNotice) and $isCourseNotice)
            {{ trans('update.course_noticeboard_color_'.$noticeboard->color) }}
        @else
            @if(!empty($noticeboard->instructor_id) and !empty($noticeboard->webinar_id))
                {{ trans('product.course') }}
            @else
                {{ trans('public.'.$noticeboard->type) }}
            @endif
        @endif
    </td>

    <td class="js-noticeboard-time text-center">{{ dateTimeFormat($noticeboard->created_at,'j M Y | H:i') }}</td>

    <td class="text-right">

        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    @can('panel_noticeboard_create')
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/{{ (!empty($isCourseNotice) and $isCourseNotice) ? 'course-noticeboard' : 'noticeboard' }}/{{ $noticeboard->id }}/edit" class="">{{ trans('public.edit') }}</a>
                        </li>
                    @endcan

                    @can('panel_noticeboard_delete')
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/{{ (!empty($isCourseNotice) and $isCourseNotice) ? 'course-noticeboard' : 'noticeboard' }}/{{ $noticeboard->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                        </li>
                    @endcan

                </ul>
            </div>
        </div>

    </td>

</tr>
