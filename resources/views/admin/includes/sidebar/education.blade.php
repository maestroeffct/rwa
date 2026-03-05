@if($authUser->can('admin_webinars') or
                $authUser->can('admin_bundles') or
                $authUser->can('admin_upcoming_courses') or
                $authUser->can('admin_events') or
                $authUser->can('admin_categories') or
                $authUser->can('admin_filters') or
                $authUser->can('admin_quizzes') or
                $authUser->can('admin_certificate') or
                $authUser->can('admin_reviews_lists') or
                $authUser->can('admin_webinar_assignments') or
                $authUser->can('admin_enrollment') or
                $authUser->can('admin_waitlists') or
                $authUser->can('admin_attendances')
            )
    <li class="menu-header">{{ trans('site.education') }}</li>
@endif

@can('admin_webinars')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/webinars*', false)) and !request()->is(getAdminPanelUrl('/webinars/comments*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-video-play class="icons" width="24px" height="24px"/>
            <span>{{ trans('panel.classes') }}</span>
        </a>
        <ul class="dropdown-menu">

            @can('admin_webinars_create')
                <li class="{{ (request()->is(getAdminPanelUrl('/webinars/create', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/webinars/create">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan()

            @can('admin_webinars_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/webinars', false)) and request()->get('type') == 'course') ? 'active' : '' }}">
                    <a class="nav-link @if(!empty($sidebarBeeps['courses']) and $sidebarBeeps['courses']) beep beep-sidebar @endif" href="{{ getAdminPanelUrl() }}/webinars?type=course">{{ trans('admin/main.courses') }}</a>
                </li>

                <li class="{{ (request()->is(getAdminPanelUrl('/webinars', false)) and request()->get('type') == 'webinar') ? 'active' : '' }}">
                    <a class="nav-link @if(!empty($sidebarBeeps['webinars']) and $sidebarBeeps['webinars']) beep beep-sidebar @endif" href="{{ getAdminPanelUrl() }}/webinars?type=webinar">{{ trans('admin/main.live_classes') }}</a>
                </li>

                <li class="{{ (request()->is(getAdminPanelUrl('/webinars', false)) and request()->get('type') == 'text_lesson') ? 'active' : '' }}">
                    <a class="nav-link @if(!empty($sidebarBeeps['textLessons']) and $sidebarBeeps['textLessons']) beep beep-sidebar @endif" href="{{ getAdminPanelUrl() }}/webinars?type=text_lesson">{{ trans('admin/main.text_courses') }}</a>
                </li>
            @endcan()



            @can('admin_agora_history_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/agora_history', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/agora_history">{{ trans('update.agora_history') }}</a>
                </li>
            @endcan

            @can('admin_course_personal_notes')
                @if(!empty(getFeaturesSettings('course_notes_status')))
                    <li class="{{ (request()->is(getAdminPanelUrl('/webinars/personal-notes', false))) ? 'active' : '' }}">
                        <a class="nav-link" href="{{ getAdminPanelUrl() }}/webinars/personal-notes">{{ trans('update.course_notes') }}</a>
                    </li>
                @endif
            @endcan

        </ul>
    </li>
@endcan()

@can('admin_bundles')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/bundles*', false)) and !request()->is(getAdminPanelUrl('/bundles/comments*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-box class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.bundles') }}</span>
        </a>
        <ul class="dropdown-menu">

            @can('admin_bundles_create')
                <li class="{{ (request()->is(getAdminPanelUrl('/bundles/create', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/bundles/create">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan()

            @can('admin_bundles_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/bundles', false)) and request()->get('type') == 'course') ? 'active' : '' }}">
                    <a href="{{ getAdminPanelUrl() }}/bundles" class="nav-link @if(!empty($sidebarBeeps['bundles']) and $sidebarBeeps['bundles']) beep beep-sidebar @endif">{{ trans('admin/main.list') }}</a>
                </li>
            @endcan()

        </ul>
    </li>
@endcan()

@can('admin_upcoming_courses')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/upcoming_courses*', false)) and !request()->is(getAdminPanelUrl('/upcoming_courses/comments*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-video-time class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.upcoming_courses') }}</span>
        </a>
        <ul class="dropdown-menu">

            @can('admin_upcoming_courses_create')
                <li class="{{ (request()->is(getAdminPanelUrl('/upcoming_courses/new', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/upcoming_courses/new') }}">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan()

            @can('admin_upcoming_courses_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/upcoming_courses', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/upcoming_courses') }}">{{ trans('admin/main.list') }}</a>
                </li>
            @endcan()

        </ul>
    </li>
@endcan()


@can('admin_events')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/events*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-ticket-2 class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.events') }}</span>
        </a>
        <ul class="dropdown-menu">

            @can('admin_events_create')
                <li class="{{ (request()->is(getAdminPanelUrl('/events/create', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/events/create') }}">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan()

            @can('admin_events_lists')
                <li class="{{ (request()->is(getAdminPanelUrl('/events', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/events') }}">{{ trans('admin/main.list') }}</a>
                </li>
            @endcan()

            @can('admin_events_sold_tickets')
                <li class="{{ (request()->is(getAdminPanelUrl('/events/sold-tickets', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/events/sold-tickets') }}">{{ trans('update.sold_tickets') }}</a>
                </li>
            @endcan()

            @can('admin_events_settings')
                <li class="{{ (request()->is(getAdminPanelUrl('/events/settings', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/events/settings') }}">{{ trans('admin/main.settings') }}</a>
                </li>
            @endcan()

        </ul>
    </li>
@endcan()

@can('admin_quizzes')
    <li class="{{ (request()->is(getAdminPanelUrl('/quizzes*', false))) ? 'active' : '' }}">
        <a class="nav-link " href="{{ getAdminPanelUrl() }}/quizzes">
            <x-iconsax-bul-task-square class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.quizzes') }}</span>
        </a>
    </li>
@endcan()

@can('admin_certificate')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/certificates*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-archive-book class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.certificates') }}</span>
        </a>
        <ul class="dropdown-menu">
            @can('admin_certificate_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/certificates', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/certificates">{{ trans('update.quizzes_related') }}</a>
                </li>
            @endcan

            @can('admin_course_certificate_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/certificates/course-competition', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/certificates/course-competition">{{ trans('update.course_certificates') }}</a>
                </li>
            @endcan

            @can('admin_certificate_template_create')
                <li class="{{ (request()->is(getAdminPanelUrl('/certificates/templates/new', false))) ? 'active' : '' }}">
                    <a class="nav-link"
                       href="{{ getAdminPanelUrl() }}/certificates/templates/new">{{ trans('admin/main.new_template') }}</a>
                </li>
            @endcan

            @can('admin_certificate_template_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/certificates/templates', false))) ? 'active' : '' }}">
                    <a class="nav-link"
                       href="{{ getAdminPanelUrl() }}/certificates/templates">{{ trans('admin/main.certificates_templates') }}</a>
                </li>
            @endcan

            @can('admin_certificate_settings')
                <li class="{{ (request()->is(getAdminPanelUrl('/certificates/settings', false))) ? 'active' : '' }}">
                    <a class="nav-link"
                       href="{{ getAdminPanelUrl() }}/certificates/settings">{{ trans('admin/main.settings') }}</a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

@can('admin_webinar_assignments')
    <li class="{{ (request()->is(getAdminPanelUrl('/assignments', false))) ? 'active' : '' }}">
        <a href="{{ getAdminPanelUrl() }}/assignments" class="nav-link">
            <x-iconsax-bul-message-edit class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.assignments') }}</span>
        </a>
    </li>
@endcan

@can('admin_course_question_forum_list')
    <li class="{{ (request()->is(getAdminPanelUrl('/webinars/course_forums', false))) ? 'active' : '' }}">
        <a class="nav-link " href="{{ getAdminPanelUrl() }}/webinars/course_forums">
            <x-iconsax-bul-messages-2 class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.course_forum') }}</span>
        </a>
    </li>
@endcan()

@can('admin_course_noticeboards_list')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/course-noticeboards*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-note class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.course_notices') }}</span>
        </a>
        <ul class="dropdown-menu">

            @can('admin_course_noticeboards_send')
                <li class="{{ (request()->is(getAdminPanelUrl('/course-noticeboards/send', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/course-noticeboards/send">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan

            @can('admin_course_noticeboards_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/course-noticeboards', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/course-noticeboards">{{ trans('admin/main.list') }}</a>
                </li>
            @endcan

        </ul>
    </li>
@endcan

@can('admin_enrollment')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/enrollments*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-user-cirlce-add class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.enrollment') }}</span>
        </a>
        <ul class="dropdown-menu">

            @can('admin_enrollment_add_student_to_items')
                <li class="{{ (request()->is(getAdminPanelUrl('/enrollments/add-student-to-class', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/enrollments/add-student-to-class">{{ trans('update.add_student_to_a_class') }}</a>
                </li>
            @endcan

            @can('admin_enrollment_history')
                <li class="{{ (request()->is(getAdminPanelUrl('/enrollments/history', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/enrollments/history">{{ trans('public.history') }}</a>
                </li>
            @endcan

        </ul>
    </li>
@endcan

@can('admin_waitlists_lists')
    <li class="{{ (request()->is(getAdminPanelUrl('/waitlists', false))) ? 'active' : '' }}">
        <a href="{{ getAdminPanelUrl("/waitlists") }}" class="nav-link">
            <x-iconsax-bul-note-favorite class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.waitlists') }}</span>
        </a>
    </li>
@endcan

@can('admin_categories')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/categories*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-category class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.categories') }}</span>
        </a>
        <ul class="dropdown-menu">

            @can('admin_categories_create')
                <li class="{{ (request()->is(getAdminPanelUrl('/categories/create', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/categories/create">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan()

            @can('admin_categories_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/categories', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/categories">{{ trans('admin/main.list') }}</a>
                </li>
            @endcan()

            @can('admin_trending_categories')
                <li class="{{ (request()->is(getAdminPanelUrl('/categories/trends', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/categories/trends">{{ trans('admin/main.trends') }}</a>
                </li>
            @endcan()
        </ul>
    </li>
@endcan()

@can('admin_filters')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/filters*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-filter-square class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.filters') }}</span>
        </a>
        <ul class="dropdown-menu">

            @can('admin_filters_create')
                <li class="{{ (request()->is(getAdminPanelUrl('/filters/create', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/filters/create">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan()

            @can('admin_filters_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/filters', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/filters">{{ trans('admin/main.list') }}</a>
                </li>
            @endcan()

        </ul>
    </li>
@endcan()

@can('admin_reviews_lists')
    <li class="{{ (request()->is(getAdminPanelUrl('/reviews', false))) ? 'active' : '' }}">
        <a href="{{ getAdminPanelUrl() }}/reviews" class="nav-link @if(!empty($sidebarBeeps['reviews']) and $sidebarBeeps['reviews']) beep beep-sidebar @endif">
            <x-iconsax-bul-like-dislike class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.reviews') }}</span>
        </a>
    </li>
@endcan

@can('admin_attendances')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/attendances*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-user-tick class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.attendances') }}</span>
        </a>
        <ul class="dropdown-menu">

            @can('admin_attendances_history')
                <li class="{{ (request()->is(getAdminPanelUrl('/attendances', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/attendances') }}">{{ trans('public.history') }}</a>
                </li>
            @endcan()

            @can('admin_attendances_settings')
                <li class="{{ (request()->is(getAdminPanelUrl('/attendances/settings*', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl("/attendances/settings") }}">{{ trans('admin/main.settings') }}</a>
                </li>
            @endcan()
        </ul>
    </li>
@endcan()
