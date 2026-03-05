@if($authUser->can('admin_users') or
                $authUser->can('admin_roles') or
                $authUser->can('admin_users_not_access_content') or
                $authUser->can('admin_group') or
                $authUser->can('admin_users_badges') or
                $authUser->can('admin_become_instructors_list') or
                $authUser->can('admin_delete_account_requests') or
                $authUser->can('admin_user_login_history') or
                $authUser->can('admin_user_ip_restriction')
            )
    <li class="menu-header">{{ trans('panel.users') }}</li>
@endif

@can('admin_users')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/all-users', false)) or request()->is(getAdminPanelUrl('/staffs', false)) or request()->is(getAdminPanelUrl('/students', false)) or request()->is(getAdminPanelUrl('/instructors', false)) or request()->is(getAdminPanelUrl('/organizations', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-profile-2user class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.users_list') }}</span>
        </a>

        <ul class="dropdown-menu">

            @can('admin_users_create')
                <li class="{{ (request()->is(getAdminPanelUrl('/users/create', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/users/create">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan()

            @can('admin_users_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/all-users', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/all-users">{{ trans('admin/main.all_users') }}</a>
                </li>
            @endcan()

            @can('admin_staffs_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/staffs', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/staffs">{{ trans('admin/main.staff') }}</a>
                </li>
            @endcan()

            @can('admin_users_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/students', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/students">{{ trans('public.students') }}</a>
                </li>
            @endcan()

            @can('admin_instructors_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/instructors', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/instructors">{{ trans('home.instructors') }}</a>
                </li>
            @endcan()

            @can('admin_organizations_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/organizations', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/organizations">{{ trans('admin/main.organizations') }}</a>
                </li>
            @endcan()


        </ul>
    </li>
@endcan


@can('admin_users_not_access_content_lists')
    <li class="{{ (request()->is(getAdminPanelUrl('/users/not-access-to-content', false))) ? 'active' : '' }}">
        <a class="nav-link" href="{{ getAdminPanelUrl() }}/users/not-access-to-content">
            <x-iconsax-bul-user-minus class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.not_access_to_content') }}</span>
        </a>
    </li>
@endcan

@can('admin_roles')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/roles*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-user-octagon class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.roles') }}</span>
        </a>
        <ul class="dropdown-menu">

            @can('admin_roles_create')
                <li class="{{ (request()->is(getAdminPanelUrl('/roles/create', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/roles/create">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan()

            @can('admin_roles_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/roles', false))) ? 'active' : '' }}">
                    <a class="nav-link active" href="{{ getAdminPanelUrl() }}/roles">{{ trans('admin/main.list') }}</a>
                </li>
            @endcan()

        </ul>
    </li>
@endcan()

@can('admin_group')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/users/groups*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-people class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.groups') }}</span>
        </a>
        <ul class="dropdown-menu">

            @can('admin_group_create')
                <li class="{{ (request()->is(getAdminPanelUrl('/users/groups/create', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/users/groups/create">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan

            @can('admin_group_list')
                <li class="{{ (request()->is(getAdminPanelUrl('/users/groups', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/users/groups">{{ trans('admin/main.list') }}</a>
                </li>
            @endcan

        </ul>
    </li>
@endcan

@can('admin_users_badges')
    <li class="{{ (request()->is(getAdminPanelUrl('/users/badges', false))) ? 'active' : '' }}">
        <a class="nav-link" href="{{ getAdminPanelUrl() }}/users/badges">
            <x-iconsax-bul-medal-star class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.badges') }}</span>
        </a>
    </li>
@endcan()



@can('admin_become_instructors_list')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/users/become-instructors*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-teacher class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.instructor_requests') }}</span>
        </a>
        <ul class="dropdown-menu">
            <li class="{{ (request()->is(getAdminPanelUrl('/users/become-instructors/instructors', false))) ? 'active' : '' }}">
                <a class="nav-link" href="{{ getAdminPanelUrl() }}/users/become-instructors/instructors">
                    <span>{{ trans('admin/main.instructors') }}</span>
                </a>
            </li>

            <li class="{{ (request()->is(getAdminPanelUrl('/users/become-instructors/organizations', false))) ? 'active' : '' }}">
                <a class="nav-link" href="{{ getAdminPanelUrl() }}/users/become-instructors/organizations">
                    <span>{{ trans('admin/main.organizations') }}</span>
                </a>
            </li>

            <li class="{{ (request()->is(getAdminPanelUrl('/users/become-instructors/settings', false))) ? 'active' : '' }}">
                <a class="nav-link" href="{{ getAdminPanelUrl() }}/users/become-instructors/settings">
                    <span>{{ trans('admin/main.settings') }}</span>
                </a>
            </li>
        </ul>
    </li>
@endcan()

@can('admin_delete_account_requests')
    <li class="nav-item {{ (request()->is(getAdminPanelUrl('/users/delete-account-requests*', false))) ? 'active' : '' }}">
        <a href="{{ getAdminPanelUrl() }}/users/delete-account-requests" class="nav-link">
            <x-iconsax-bul-user-remove class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.delete-account-requests') }}</span>
        </a>
    </li>
@endcan

@if($authUser->can("admin_user_login_history") or $authUser->can("admin_user_ip_restriction"))
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/users/login-history*', false)) or request()->is(getAdminPanelUrl('/users/ip-restriction*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-shield-search class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.ip_management') }}</span>
        </a>
        <ul class="dropdown-menu">
            @can('admin_user_login_history')
                <li class="nav-item {{ (request()->is(getAdminPanelUrl('/users/login-history*', false))) ? 'active' : '' }}">
                    <a href="{{ getAdminPanelUrl() }}/users/login-history" class="nav-link">
                        <span>{{ trans('update.login_history') }}</span>
                    </a>
                </li>
            @endcan

            @can('admin_user_ip_restriction')
                <li class="nav-item {{ (request()->is(getAdminPanelUrl('/users/ip-restriction*', false))) ? 'active' : '' }}">
                    <a href="{{ getAdminPanelUrl() }}/users/ip-restriction" class="nav-link">
                        <span>{{ trans('update.ip_restriction') }}</span>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endif
