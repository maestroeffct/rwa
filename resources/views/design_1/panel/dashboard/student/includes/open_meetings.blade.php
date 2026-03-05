<div class="bg-white p-16 rounded-24 mt-24">
    <h4 class="font-14 text-dark">{{ trans('update.open_meetings') }}</h4>

    @if(!empty($openMeetings['totalMeetings']))
        <a href="/panel/meetings/reservation" target="_blank" class="">
            <div class="d-flex align-items-center justify-content-between p-12 rounded-16 bg-gray-100 mt-16">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                        <x-iconsax-bul-profile-2user class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-weight-bold text-dark">{{ $openMeetings['totalMeetings'] }}</span>
                        <span class="d-block font-12 text-gray-500 mt-4">{{ trans('update.open_meetings') }}</span>
                    </div>
                </div>

                <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
            </div>
        </a>

        {{-- Card --}}
        @if(!empty($openMeetings['reserveMeetings']))
            @foreach($openMeetings['reserveMeetings'] as $openReserveMeeting)
                <div class="bg-gray-100 rounded-16 p-12 mt-16">
                    <div class="d-flex align-items-center justify-content-between bg-white p-12 rounded-12">
                        <div class="d-flex align-items-center">
                            <div class="size-48 rounded-circle bg-gray-100">
                                <img src="{{ $openReserveMeeting->meeting->creator->getAvatar() }}" alt="" class="rounded-circle img-cover">
                            </div>
                            <div class="ml-8">
                                <h6 class="font-14 text-dark">{{ truncate($openReserveMeeting->meeting->creator->full_name, 28) }}</h6>
                                <div class="d-flex align-items-center gap-8 font-12 text-gray-500 mt-4">
                                    <span class="">{{ dateTimeFormat($openReserveMeeting->start_at, 'j M Y') }}</span>

                                    <div class="d-flex align-items-center font-12 text-gray-500">
                                        <span class="">{{ dateTimeFormat($openReserveMeeting->start_at, 'H:i') }}</span>
                                        <span class="mx-2">-</span>
                                        <span class="">{{ dateTimeFormat($openReserveMeeting->end_at, 'H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-16">
                        <div class="d-flex align-items-center gap-16">
                            @if($openReserveMeeting->student_count > 1)
                                <div class="d-flex-center p-8 rounded-8 bg-gray-200 font-12 text-gray-500">{{ trans('update.group') }}</div>
                            @endif

                            <div class="d-flex-center p-8 rounded-8 bg-gray-200 font-12 text-gray-500">{{ trans('update.'.$openReserveMeeting->meeting_type) }}</div>
                        </div>

                        <div class="d-flex align-items-center gap-16">
                            <a href="{{ $openReserveMeeting->addToCalendarLink() }}" target="_blank" class="d-flex-center size-40 rounded-circle bg-gray-200 bg-hover-gray-300"
                               data-tippy-content="{{ trans('public.add_to_calendar') }}"
                            >
                                <x-iconsax-bul-notification-bing class="icons text-gray-500" width="24px" height="24px"/>
                            </a>

                            <div class="js-join-to-meeting-session d-flex-center size-40 rounded-circle bg-gray-200 bg-hover-gray-300 cursor-pointer"
                                 data-tippy-content="{{ trans('footer.join') }}"
                                 data-path="/panel/meetings/{{ $openReserveMeeting->id }}/join-modal"
                            >
                                <x-iconsax-bul-video class="icons text-primary" width="24px" height="24px"/>
                            </div>

                        </div>

                    </div>
                </div>
            @endforeach
        @endif

        {{-- Meeting Package Scheduled Sessions --}}
        @if(!empty($openMeetings['meetingPackageScheduledSessions']))
            @foreach($openMeetings['meetingPackageScheduledSessions'] as $meetingPackageScheduledSession)
                <div class="bg-gray-100 rounded-16 p-12 mt-16">
                    <div class="d-flex align-items-center justify-content-between bg-white p-12 rounded-12">
                        <div class="d-flex align-items-center">
                            <div class="size-48 rounded-circle bg-gray-100">
                                <img src="{{ $meetingPackageScheduledSession->creator->getAvatar() }}" alt="" class="rounded-circle img-cover">
                            </div>
                            <div class="ml-8">
                                <h6 class="font-14 text-dark">{{ truncate($meetingPackageScheduledSession->creator->full_name, 28) }}</h6>
                                <div class="d-flex align-items-center gap-8 font-12 text-gray-500 mt-4">
                                    <span class="">{{ dateTimeFormat($meetingPackageScheduledSession->date, 'j M Y') }}</span>

                                    <div class="d-flex align-items-center font-12 text-gray-500">
                                        <span class="">{{ dateTimeFormat($meetingPackageScheduledSession->date, 'H:i') }}</span>
                                        <span class="mx-2">-</span>
                                        <span class="">{{ dateTimeFormat(($meetingPackageScheduledSession->date + ($meetingPackageScheduledSession->duration * 60)), 'H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-16">
                        <div class="d-flex align-items-center gap-16">
                            <div class="d-flex-center p-8 rounded-8 bg-gray-200 font-12 text-gray-500">{{ trans('update.package') }}</div>
                        </div>

                        <div class="d-flex align-items-center gap-16">
                            <a href="{{ $meetingPackageScheduledSession->addToCalendarLink() }}" target="_blank" class="d-flex-center size-40 rounded-circle bg-gray-200 bg-hover-gray-300"
                               data-tippy-content="{{ trans('public.add_to_calendar') }}"
                            >
                                <x-iconsax-bul-notification-bing class="icons text-gray-500" width="24px" height="24px"/>
                            </a>

                            <div class="js-join-to-meeting-session d-flex-center size-40 rounded-circle bg-gray-200 bg-hover-gray-300 cursor-pointer"
                                 data-tippy-content="{{ trans('footer.join') }}"
                                 data-path="/panel/meetings/purchased-packages/{{ $meetingPackageScheduledSession->meeting_package_sold_id }}/sessions/{{ $meetingPackageScheduledSession->id }}/join-modal"
                                 data-title="{{ trans('update.join_to_session') }}"
                            >
                                <x-iconsax-bul-video class="icons text-primary" width="24px" height="24px"/>
                            </div>

                        </div>

                    </div>
                </div>
            @endforeach
        @endif

    @else
        {{-- If Empty --}}
        <div class="d-flex-center flex-column text-center mt-20 border-dashed border-gray-200 bg-gray-100 p-32 rounded-16">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <x-iconsax-bul-calendar-2 class="icons text-primary" width="24px" height="24px"/>
            </div>
            <h5 class="font-14 text-dark mt-12">{{ trans('update.no_meeting!') }}</h5>
            <div class="mt-4 font-12 text-gray-500">{{ trans('update.you_don’t_have_any_meetings_you_can_find_your_desired_instructor_and_book_a_meeting') }}</div>
        </div>

        @if(!empty($openMeetings['instructors']) and count($openMeetings['instructors']))
            <div class="d-flex-center m-16">
                {{-- Avatar 1 --}}
                @foreach($openMeetings['instructors'] as $openMeetingsInstructorKey => $openMeetingsInstructor)
                    @php
                        $extraClass = "";
                        $isSecondItem = $openMeetingsInstructorKey == 1;

                        if ($openMeetingsInstructorKey != 1) { // Not Second Item
                            $extraClass = "student-dashboard__no-meeting-avatars";

                            if ($openMeetingsInstructorKey == 0) {
                                $extraClass .= " avatar-1";
                            } else {
                                $extraClass .= " avatar-3";
                            }
                        }
                    @endphp

                    <div class="d-flex-center {{ $isSecondItem ? ' position-relative z-index-2 size-68' : 'size-48' }} bg-gray-100 rounded-circle {{ $extraClass }}">
                        <div class="{{ $isSecondItem ? 'size-60' : 'size-40' }} rounded-circle">
                            <img src="{{ $openMeetingsInstructor->getAvatar() }}" alt="" class="img-cover rounded-circle">
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="">
                <h6 class="font-14 text-dark">{{ trans('update.find_an_instructor') }}</h6>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.book_a_meeting_right_now...') }}</p>
            </div>

            <a href="/instructor-finder" target="_blank" class="d-flex-center size-40 bg-white border-gray-200 rounded-circle bg-hover-gray-100">
                <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
            </a>
        </div>
    @endif

</div>
