@if(
    $authUser->can('admin_imports_from_csv') or
    $authUser->can('admin_translator') or
    $authUser->can('admin_settings')
)
    <li class="menu-header">{{ trans('admin/main.settings') }}</li>
@endif


@can('admin_imports_from_csv')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/imports*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <x-iconsax-bul-import class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.bulk_imports') }}</span>
        </a>

        <ul class="dropdown-menu">

            @can('admin_imports_from_csv')
                <li class="{{ (request()->is(getAdminPanelUrl('/imports', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/imports') }}">{{ trans('admin/main.new') }}</a>
                </li>
            @endcan

            @can('admin_imports_from_csv_history')
                <li class="{{ (request()->is(getAdminPanelUrl('/imports/history', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/imports/history') }}">{{ trans('update.history') }}</a>
                </li>
            @endcan

        </ul>
    </li>
@endcan


@can('admin_translator')
    <li class="nav-item {{ (request()->is(getAdminPanelUrl('/translator*', false))) ? 'active' : '' }}">
        <a href="{{ getAdminPanelUrl() }}/translator" class="nav-link">
            <x-iconsax-bul-translate class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.translator') }}</span>
        </a>
    </li>
@endcan

@can('admin_settings')
    <li class="nav-item {{ (request()->is(getAdminPanelUrl('/licenses', false))) ? 'active' : '' }}">
        <a href="{{ getAdminPanelUrl() }}/licenses" class="nav-link">
        <x-iconsax-bul-key class="icons" width="24px" height="24px"/>
            <span>Licenses</span>
        </a>
    </li>
@endcan

@can('admin_settings')
    @php
        $settingClass ='';

        if (request()->is(getAdminPanelUrl('/settings*', false)) and
                !(
                    request()->is(getAdminPanelUrl('/settings/404', false)) or
                    request()->is(getAdminPanelUrl('/settings/contact_us', false)) or
                    request()->is(getAdminPanelUrl('/settings/footer', false)) or
                    request()->is(getAdminPanelUrl('/settings/navbar_links', false))
                )
            ) {
                $settingClass = 'active';
            }
    @endphp

    <li class="nav-item {{ $settingClass ?? '' }}">
        <a href="{{ getAdminPanelUrl() }}/settings" class="nav-link">
            <x-iconsax-bul-setting-2 class="icons" width="24px" height="24px"/>
            <span>{{ trans('admin/main.settings') }}</span>
        </a>
    </li>
@endcan()
