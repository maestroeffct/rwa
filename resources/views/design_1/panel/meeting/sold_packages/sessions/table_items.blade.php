<tr>
    {{-- Session Number --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-gray-300 rounded-circle">{{ $session->number_row }}</div>
            <div class=" ml-12">{{ trans('update.session') }} #{{ $session->number_row }}</div>
        </div>
    </td>

    {{-- Session Date --}}
    <td class="text-center">
        {{ !empty($session->date) ? dateTimeFormat($session->date, 'j M Y') : '-' }}
    </td>

    {{-- Session Time --}}
    <td class="text-center">
        {{ !empty($session->date) ? dateTimeFormat($session->date, 'H:i') : '-' }}
    </td>


    {{-- Status --}}
    <td class="text-center">
        @if($session->status == "finished")
            <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 text-success bg-success-30">{{ trans('public.finished') }}</div>
        @elseif(!empty($session->date))
            <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 text-warning bg-warning-30">{{ trans('update.scheduled') }}</div>
        @else
            <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 text-primary bg-primary-30">{{ trans('update.not_scheduled') }}</div>
        @endif
    </td>

    <td class="text-right">
        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    @if($session->status != "finished")
                        @if(empty($session->date))
                            <li class="actions-dropdown__dropdown-menu-item">
                                <button type="button"
                                        data-path="/panel/meetings/sold-packages/{{ $meetingPackageSold->id }}/sessions/{{ $session->id }}/set-date"
                                        data-title="{{ trans('update.set_session_time') }}"
                                        class="js-meeting-package-session-set-time"
                                >
                                    {{ trans('update.set_session_time') }}
                                </button>
                            </li>
                        @else
                            <li class="actions-dropdown__dropdown-menu-item">
                                <button type="button"
                                        data-path="/panel/meetings/sold-packages/{{ $meetingPackageSold->id }}/sessions/{{ $session->id }}/set-date"
                                        data-title="{{ trans('update.set_session_time') }}"
                                        class="js-meeting-package-session-set-time"
                                >
                                    {{ trans('update.edit_session_time') }}
                                </button>
                            </li>

                            @if($session->status == "draft")
                                <li class="actions-dropdown__dropdown-menu-item">
                                    <button type="button"
                                            data-path="/panel/meetings/sold-packages/{{ $meetingPackageSold->id }}/sessions/{{ $session->id }}/set-api"
                                            data-title="{{ trans('update.create_a_session') }}"
                                            class="js-meeting-package-create-session"
                                    >
                                        {{ trans('update.create_a_session') }}
                                    </button>
                                </li>
                            @else
                                <li class="actions-dropdown__dropdown-menu-item">
                                    <button type="button"
                                            data-path="/panel/meetings/sold-packages/{{ $meetingPackageSold->id }}/sessions/{{ $session->id }}/join-modal"
                                            data-title="{{ trans('update.join_to_session') }}"
                                            class="js-meeting-package-session-join"
                                    >
                                        {{ trans('update.join_to_session') }}
                                    </button>
                                </li>
                            @endif


                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="{{ $session->addToCalendarLink() }}" target="_blank" class="">{{ trans('public.add_to_calendar') }}</a>
                            </li>

                            <li class="actions-dropdown__dropdown-menu-item">
                                <button type="button"
                                        data-path="/panel/meetings/sold-packages/{{ $meetingPackageSold->id }}/sessions/{{ $session->id }}/finish-modal"
                                        data-title="{{ trans('panel.finish_meeting') }}"
                                        class="js-get-finish-meeting-package-session"
                                >
                                    {{ trans('panel.finish_meeting') }}
                                </button>
                            </li>
                        @endif
                    @endif


                    <li class="actions-dropdown__dropdown-menu-item">
                        <button type="button" data-path="/panel/meetings/sold-packages/{{ $meetingPackageSold->id }}/get-student-detail" data-title="{{ trans('update.student_information') }}" class="js-meeting-sold-package-student-detail">{{ trans('update.student_detail') }}</button>
                    </li>

                </ul>
            </div>
        </div>
    </td>

</tr>
