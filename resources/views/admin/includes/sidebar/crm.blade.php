@if($authUser->can('admin_supports') or
                $authUser->can('admin_comments') or
                $authUser->can('admin_reports') or
                $authUser->can('admin_contacts') or
                $authUser->can('admin_noticeboards') or
                $authUser->can('admin_notifications')
            )
    <li class="menu-header">{{ trans('admin/main.crm') }}</li>
@endif

@can('admin_supports')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/supports*', false)) and request()->get('type') != 'course_conversations') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-sms class="icons" width="24px" height="24px"/>
            <span>{{ trans('panel.support_tickets') }}</span>
        </a>

        <ul class="dropdown-menu">

            @can('admin_support_send')
                <li class="{{ (request()->is(getAdminPanelUrl('/supports/create', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/supports/create">{{ trans('admin/main.new_ticket') }}</a>
                </li>
            @endcan

            @can('admin_supports_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/supports', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/supports">{{ trans('admin/main.list') }}</a>
                </li>
            @endcan

            @can('admin_support_departments')
                <li class="{{ (request()->is(getAdminPanelUrl('/supports/departments', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/supports/departments">{{ trans('admin/main.departments') }}</a>
                </li>
            @endcan
        </ul>
    </li>

    @can('admin_support_course_conversations')
        <li class="{{ (request()->is(getAdminPanelUrl('/supports*', false)) and request()->get('type') == 'course_conversations') ? 'active' : '' }}">
            <a class="nav-link" href="{{ getAdminPanelUrl() }}/supports?type=course_conversations">
                <x-iconsax-bul-messages class="icons" width="24px" height="24px"/>
                <span>{{ trans('admin/main.classes_conversations') }}</span>
            </a>
        </li>
    @endcan
@endcan

@can('admin_comments')
    <li class="nav-item dropdown {{ (!request()->is(getAdminPanelUrl('admin/comments/products, false')) and (request()->is(getAdminPanelUrl('/comments*', false)) and !request()->is(getAdminPanelUrl('/comments/webinars/reports', false)))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
            <x-iconsax-bul-message-text class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.comments') }}</span></a>
        <ul class="dropdown-menu">
            @can('admin_webinar_comments')
                <li class="{{ (request()->is(getAdminPanelUrl('/comments/webinars', false))) ? 'active' : '' }}">
                    <a class="nav-link @if(!empty($sidebarBeeps['classesComments']) and $sidebarBeeps['classesComments']) beep beep-sidebar @endif" href="{{ getAdminPanelUrl() }}/comments/webinars">{{ trans('admin/main.classes_comments') }}</a>
                </li>
            @endcan

            @can('admin_bundle_comments')
                <li class="{{ (request()->is(getAdminPanelUrl('/comments/bundles', false))) ? 'active' : '' }}">
                    <a class="nav-link @if(!empty($sidebarBeeps['bundleComments']) and $sidebarBeeps['bundleComments']) beep beep-sidebar @endif" href="{{ getAdminPanelUrl() }}/comments/bundles">{{ trans('update.bundle_comments') }}</a>
                </li>
            @endcan

            @can('admin_blog_comments')
                <li class="{{ (request()->is(getAdminPanelUrl('/comments/blog', false))) ? 'active' : '' }}">
                    <a class="nav-link @if(!empty($sidebarBeeps['blogComments']) and $sidebarBeeps['blogComments']) beep beep-sidebar @endif" href="{{ getAdminPanelUrl() }}/comments/blog">{{ trans('admin/main.blog_comments') }}</a>
                </li>
            @endcan

            @can('admin_product_comments')
                <li class="{{ (request()->is(getAdminPanelUrl('/comments/products', false))) ? 'active' : '' }}">
                    <a class="nav-link @if(!empty($sidebarBeeps['productComments']) and $sidebarBeeps['productComments']) beep beep-sidebar @endif" href="{{ getAdminPanelUrl() }}/comments/products">{{ trans('update.product_comments') }}</a>
                </li>
            @endcan

            @can('admin_event_comments')
                <li class="{{ (request()->is(getAdminPanelUrl('/comments/events', false))) ? 'active' : '' }}">
                    <a class="nav-link @if(!empty($sidebarBeeps['eventsComments']) and $sidebarBeeps['eventsComments']) beep beep-sidebar @endif" href="{{ getAdminPanelUrl() }}/comments/events">{{ trans('update.event_comments') }}</a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

@can('admin_reports')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/reports*', false)) or request()->is(getAdminPanelUrl('/comments/webinars/reports', false)) or request()->is(getAdminPanelUrl('/comments/blog/reports', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
            <x-iconsax-bul-info-circle class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.reports') }}</span></a>

        <ul class="dropdown-menu">
            @can('admin_webinar_reports')
                <li class="{{ (request()->is(getAdminPanelUrl('/reports/webinars', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/reports/webinars">{{ trans('panel.classes') }}</a>
                </li>
            @endcan

            @can('admin_webinar_comments_reports')
                <li class="{{ (request()->is(getAdminPanelUrl('/comments/webinars/reports', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/comments/webinars/reports">{{ trans('admin/main.classes_comments_reports') }}</a>
                </li>
            @endcan

            @can('admin_blog_comments_reports')
                <li class="{{ (request()->is(getAdminPanelUrl('/comments/blog/reports', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/comments/blog/reports">{{ trans('admin/main.blog_comments_reports') }}</a>
                </li>
            @endcan

            @can('admin_report_reasons')
                <li class="{{ (request()->is(getAdminPanelUrl('/reports/reasons', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/reports/reasons">{{ trans('admin/main.report_reasons') }}</a>
                </li>
            @endcan()

            @can('admin_forum_topic_post_reports')
                <li class="{{ (request()->is(getAdminPanelUrl('/reports/forum-topics', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/reports/forum-topics">{{ trans('update.forum_topics') }}</a>
                </li>
            @endcan()
        </ul>
    </li>
@endcan

@can('admin_contacts')
    <li class="{{ (request()->is(getAdminPanelUrl('/contacts*', false))) ? 'active' : '' }}">
        <a class="nav-link" href="{{ getAdminPanelUrl() }}/contacts">
            <x-iconsax-bul-directbox-receive class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.contacts') }}</span>
        </a>
    </li>
@endcan

@can('admin_noticeboards')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/noticeboards*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
            <x-iconsax-bul-signpost class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.noticeboard') }}</span></a>
        <ul class="dropdown-menu">
            @can('admin_noticeboards_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/noticeboards', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/noticeboards">{{ trans('admin/main.list') }}</a>
                </li>
            @endcan

            @can('admin_noticeboards_send')
                <li class="{{ (request()->is(getAdminPanelUrl('/noticeboards/send', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/noticeboards/send">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

@can('admin_notifications')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/notifications*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-notification class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.notifications') }}</span>
        </a>

        <ul class="dropdown-menu">
            @can('admin_notifications_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/notifications', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/notifications">{{ trans('public.history') }}</a>
                </li>
            @endcan

            @can('admin_notifications_posted_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/notifications/posted', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/notifications/posted">{{ trans('admin/main.posted') }}</a>
                </li>
            @endcan

            @can('admin_notifications_send')
                <li class="{{ (request()->is(getAdminPanelUrl('/notifications/send', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/notifications/send">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan

            @can('admin_notifications_template_create')
                <li class="{{ (request()->is(getAdminPanelUrl('/notifications/templates/create', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/notifications/templates/create">{{ trans('admin/main.new_template') }}</a>
                </li>
            @endcan

            @can('admin_notifications_templates')
                <li class="{{ (request()->is(getAdminPanelUrl('/notifications/templates', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/notifications/templates">{{ trans('admin/main.templates') }}</a>
                </li>
            @endcan


        </ul>
    </li>
@endcan
