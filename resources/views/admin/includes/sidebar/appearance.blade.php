@if(
        $authUser->can('admin_themes') or
        $authUser->can('admin_landing_builder')
    )
    <li class="menu-header">{{ trans('update.appearance') }}</li>
@endif

@can('admin_themes')
    <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/themes*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
        <x-iconsax-bul-designtools class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.themes') }}</span>
        </a>

        <ul class="dropdown-menu">
            @can('admin_themes_create')
            <li class="{{ (request()->is(getAdminPanelUrl('/themes/create', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/themes/create">{{ trans('admin/main.new') }}</a>
                </li>

                <li class="{{ (request()->is(getAdminPanelUrl('/themes', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl() }}/themes">{{ trans('admin/main.list') }}</a>
                </li>
            @endcan

            @can('admin_themes_colors')
                <li class="{{ (request()->is(getAdminPanelUrl('/themes/colors', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/themes/colors') }}">{{ trans('update.colors_lists') }}</a>
                </li>
            @endcan

            @can('admin_themes_fonts')
                <li class="{{ (request()->is(getAdminPanelUrl('/themes/fonts', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/themes/fonts') }}">{{ trans('update.fonts_lists') }}</a>
                </li>
            @endcan

            @can('admin_themes_headers')
                <li class="{{ (request()->is(getAdminPanelUrl('/themes/headers', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/themes/headers') }}">{{ trans('update.headers_lists') }}</a>
                </li>
            @endcan

            @can('admin_themes_footers')
                <li class="{{ (request()->is(getAdminPanelUrl('/themes/footers', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getAdminPanelUrl('/themes/footers') }}">{{ trans('update.footers_lists') }}</a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

@can('admin_landing_builder')
    <li class="nav-item dropdown {{ (request()->is(getLandingBuilderUrl('*', false))) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
        <x-iconsax-bul-colorfilter class="icons" width="24px" height="24px"/>
            <span>{{ trans('update.landing_builder') }}</span>
        </a>
        <ul class="dropdown-menu">
            @can('admin_landing_builder_create')
                <li class="{{ (request()->is(getLandingBuilderUrl('/start', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getLandingBuilderUrl("/start") }}">{{ trans('update.new_landing') }}</a>
                </li>
            @endcan

            @can('admin_landing_builder_all_pages')
                <li class="{{ (request()->is(getLandingBuilderUrl('/all-pages', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getLandingBuilderUrl('/all-pages') }}">{{ trans('update.landing_pages') }}</a>
                </li>
            @endcan

            {{--@can('admin_landing_builder_settings')
                <li class="{{ (request()->is(getLandingBuilderUrl('/settings', false))) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ getLandingBuilderUrl('/settings') }}">{{ trans('admin/main.settings') }}</a>
                </li>
            @endcan--}}
        </ul>
    </li>
@endcan
