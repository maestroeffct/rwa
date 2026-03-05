@if(
    $authUser->can('admin_consultants_lists') or
    $authUser->can('admin_appointments_lists') or
    $authUser->can('admin_meeting_packages')
)
    <li class="menu-header">{{ trans('site.appointments') }}</li>
@endif

@can('admin_consultants_lists')
    <li class="{{ (request()->is(getAdminPanelUrl('/consultants', false))) ? 'active' : '' }}">
        <a href="{{ getAdminPanelUrl() }}/consultants" class="nav-link">
            <x-iconsax-bul-tag-user class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.consultants') }}</span>
        </a>
    </li>
@endcan

@can('admin_appointments_lists')
    <li class="{{ (request()->is(getAdminPanelUrl('/appointments', false))) ? 'active' : '' }}">
        <a class="nav-link" href="{{ getAdminPanelUrl() }}/appointments">
            <x-iconsax-bul-calendar class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.appointments') }}</span>
        </a>
    </li>
@endcan


@can('admin_meeting_packages')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/meeting-packages*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-box-time class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.meeting_packages') }}</span>
        </a>
        <ul class="dropdown-menu">

            @can('admin_meeting_packages_create')
                <li class="{{ (request()->is(getAdminPanelUrl('/meeting-packages/create', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/meeting-packages/create') }}">{{ trans('public.create') }}</a>
                </li>
            @endcan()

            @can('admin_meeting_packages_lists')
                <li class="{{ (request()->is(getAdminPanelUrl('/meeting-packages', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/meeting-packages') }}">{{ trans('admin/main.lists') }}</a>
                </li>
            @endcan()

            @can('admin_meeting_packages_sold')
                <li class="{{ (request()->is(getAdminPanelUrl('/meeting-packages/sold', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/meeting-packages/sold') }}">{{ trans('update.sold_packages') }}</a>
                </li>
            @endcan()

            @can('admin_meeting_packages_settings')
                <li class="{{ (request()->is(getAdminPanelUrl('/meeting-packages/settings*', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl("/meeting-packages/settings") }}">{{ trans('admin/main.settings') }}</a>
                </li>
            @endcan()
        </ul>
    </li>
@endcan()
