@if(
                $authUser->can('admin_forum') or
                $authUser->can('admin_featured_topics')
                )
    <li class="menu-header">{{ trans('update.forum') }}</li>
@endif

@can('admin_forum')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/forums*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-device-message class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.forums') }}</span>
                    </a>
                    <ul class="dropdown-menu">

                    @can('admin_forum_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/forums/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/forums/create">{{ trans('admin/main.new') }}</a>
                            </li>
                        @endcan()

                        @can('admin_forum_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/forums', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/forums">{{ trans('admin/main.list') }}</a>
                            </li>
                        @endcan()

                        @can('admin_forum_settings')
                            <li class="{{ (request()->is(getAdminPanelUrl('/forums/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/forums/settings">{{ trans('admin/main.settings') }}</a>
                            </li>
                        @endcan()
                    </ul>
                </li>
            @endcan()

            @can('admin_featured_topics')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/featured-topics*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-message-favorite class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.featured_topics') }}</span>
                    </a>
                    <ul class="dropdown-menu">

                    @can('admin_featured_topics_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/featured-topics/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/featured-topics/create">{{ trans('admin/main.new') }}</a>
                            </li>
                        @endcan()

                        @can('admin_featured_topics_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/featured-topics', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/featured-topics">{{ trans('admin/main.list') }}</a>
                            </li>
                        @endcan()
                       
                    </ul>
                </li>
            @endcan()

            @can('admin_recommended_topics')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/recommended-topics*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-like-tag class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.recommended_topics') }}</span>
                    </a>
                    <ul class="dropdown-menu">

                    @can('admin_recommended_topics_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/recommended-topics/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/recommended-topics/create">{{ trans('admin/main.new') }}</a>
                            </li>
                        @endcan()

                        @can('admin_recommended_topics_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/recommended-topics', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/recommended-topics">{{ trans('admin/main.list') }}</a>
                            </li>
                        @endcan()
                       
                    </ul>
                </li>
            @endcan()
