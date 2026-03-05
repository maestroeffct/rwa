<div class="learning-page__dropdown position-relative">
    <div class="d-flex align-items-center gap-8 p-8 pr-12 rounded-24 bg-gray-100">
        <div class="d-flex-center size-32 bg-primary rounded-circle">
            <x-iconsax-bul-teacher class="icons text-white" width="16px" height="16px"/>
        </div>
        <span class="font-12 text-dark font-weight-bold">{{ trans('update.course_tools') }}</span>
        <x-iconsax-lin-arrow-down class="icons text-gray-400" width="16px" height="16px"/>
    </div>

    <div class="learning-page__dropdown-menu py-12">

        <ul class="my-8">
            @if(!empty($authUser) and $authUser->isAdmin())
                <li class="learning-page__dropdown-menu__item">
                    <a href="{{ getAdminPanelUrl("/attendances?webinar_ids[]={$course->id}") }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-user-tick class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.attendance') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="{{ getAdminPanelUrl("/quizzes?webinar_ids[]={$course->id}") }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-clipboard-tick class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.student_quizzes') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="{{ getAdminPanelUrl("/assignments?webinar_ids[]={$course->id}") }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-archive-book class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.student_assignments') }}</span>
                    </a>
                </li>

                @if($course->forum)
                    <li class="learning-page__dropdown-menu__item">
                        <a href="{{ $course->getForumPageUrl() }}" class="d-flex align-items-center w-100 px-16 py-8">
                            <x-iconsax-lin-messages class="icons" width="24px" height="24px"/>
                            <span class="ml-8">{{ trans('update.forums') }}</span>
                        </a>
                    </li>
                @endif

                <li class="learning-page__dropdown-menu__item">
                    <a href="{{ getAdminPanelUrl("/course-noticeboards/send?webinar_id={$course->id}") }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-message-notif class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.send_notice') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="{{ getAdminPanelUrl("/webinars/{$course->id}/students") }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-teacher class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.students_list') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="{{ getAdminPanelUrl("/webinars/{$course->id}/statistics") }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-status-up class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.statistics') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="{{ getAdminPanelUrl("/webinars/{$course->id}/edit") }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-edit class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.edit_course') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="{{ $course->getUrl() }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-video-play class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.course_page') }}</span>
                    </a>
                </li>
            @elseif($userIsCourseTeacher)
                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/courses/attendances?course_id={{ $course->id }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-user-tick class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.attendance') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/quizzes/results" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-clipboard-tick class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.student_quizzes') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/assignments/histories" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-archive-book class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.student_assignments') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/certificates/students" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-medal class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.student_certificates') }}</span>
                    </a>
                </li>

                @if($course->forum)
                    <li class="learning-page__dropdown-menu__item">
                        <a href="{{ $course->getForumPageUrl() }}" class="d-flex align-items-center w-100 px-16 py-8">
                            <x-iconsax-lin-messages class="icons" width="24px" height="24px"/>
                            <span class="ml-8">{{ trans('update.forums') }}</span>
                        </a>
                    </li>
                @endif

                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/noticeboard/new" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-message-notif class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.send_notice') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/support" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-device-message class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.course_support') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/courses/{{ $course->id }}/export-students-list" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-teacher class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.students_list') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/courses/{{ $course->id }}/statistics" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-status-up class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.statistics') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/courses/{{ $course->id }}/edit" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-edit class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.edit_course') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="{{ $course->getUrl() }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-video-play class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.course_page') }}</span>
                    </a>
                </li>
            @else
                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/quizzes/my-results" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-clipboard-tick class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('quiz.quizzes') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/assignments/my-requests" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-archive-book class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.assignments') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/certificates/my-achievements" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-medal class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('panel.certificates') }}</span>
                    </a>
                </li>

                @if($course->forum)
                    <li class="learning-page__dropdown-menu__item">
                        <a href="{{ $course->getForumPageUrl() }}" class="d-flex align-items-center w-100 px-16 py-8">
                            <x-iconsax-lin-messages class="icons" width="24px" height="24px"/>
                            <span class="ml-8">{{ trans('update.forums') }}</span>
                        </a>
                    </li>
                @endif

                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/courses/personal-notes" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-note-2 class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.notes') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="/panel/support/new" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-device-message class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.get_support') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="{{ $course->teacher->getProfileUrl() }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-profile class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.instructor_profile') }}</span>
                    </a>
                </li>

                <li class="learning-page__dropdown-menu__item">
                    <a href="{{ $course->getUrl() }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                        <x-iconsax-lin-video-play class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.course_page') }}</span>
                    </a>
                </li>

                @if($course->isWebinar())
                    <li class="learning-page__dropdown-menu__item">
                        <a href="{{ $course->addToCalendarLink() }}" target="_blank" class="d-flex align-items-center w-100 px-16 py-8">
                            <x-iconsax-lin-notification-bing class="icons" width="24px" height="24px"/>
                            <span class="ml-8">{{ trans('update.add_expiry_date_to_reminder') }}</span>
                        </a>
                    </li>
                @endif

            @endif
        </ul>

    </div>
</div>
