<div class="navbar-auth-user">
    <div class="d-flex align-items-center cursor-pointer">
        <div class="navbar-auth-user__avatar is-panel-nav mr-4">
            <img src="{{ $authUser->getAvatar(44) }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}">
        </div>

        <div class="d-none d-lg-block ml-8">
            <div class="navbar-auth-user__info font-14 text-white">{{ $authUser->full_name }}</div>
            <span class="mt-4 text-white font-12" style="opacity: 0.7;">{{ $authUser->role->caption }}</span>
        </div>

        <x-iconsax-lin-arrow-down class="icons text-dark ml-8" width="14px" height="14px"/>
    </div>

    <div class="navbar-auth-user__dropdown is-panel-nav">
        <div class="d-flex align-items-center m-4 rounded-10 bg-gray p-12">
            <div class="dropdown__user-avatar position-relative">
                <img src="{{ $authUser->getAvatar(38) }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}">
            </div>

            <div class="ml-8">
                <div class="font-14 font-weight-bold text-dark">{{ $authUser->full_name }}</div>
                <span class="mt-4 text-gray-500 font-12">{{ $authUser->role->caption }}</span>
            </div>
        </div>

        <ul class="my-8">

             <li class="navbar-auth-user__dropdown-item">
                    <a href="{{ $authUser->isAdmin() ? '/' : '#' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                        <x-iconsax-lin-global class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('admin/main.show_website') }}</span>
                    </a>
                </li>

            <li class="navbar-auth-user__dropdown-item">
                <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/") : '/panel' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-chart-2 class="icons" width="24px" height="24px"/>
                    <span class="ml-8">{{ trans('panel.dashboard') }}</span>
                </a>
            </li>

            @if($authUser->isAdmin())
                <li class="navbar-auth-user__dropdown-item">
                    <a href="{{ getAdminPanelUrl() }}/users/{{ $authUser->id }}/edit" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                        <x-iconsax-lin-lock class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('admin/main.change_password') }}</span>
                    </a>
                </li>
            @endif

            <li class="navbar-auth-user__dropdown-item">
                <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/notifications") : '/panel' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-notification class="icons" width="24px" height="24px"/>
                    <span class="ml-8">{{ trans('panel.notifications') }}</span>

                    @if(!empty($unReadNotifications) and count($unReadNotifications))
                        <span class="count-badge d-inline-flex align-items-center justify-content-center text-white rounded-circle ml-auto font-12 bg-danger">{{ count($unReadNotifications) }}</span>
                    @endif
                </a>
            </li>

            @if(!$authUser->isUser())
                <li class="navbar-auth-user__dropdown-item">
                    <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/webinars?type=course") : '/panel' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                        <x-iconsax-lin-video-play class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.my_courses') }}</span>
                    </a>
                </li>

                <li class="navbar-auth-user__dropdown-item">
                    <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/financial/sales") : '/panel' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                        <x-iconsax-lin-moneys class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('panel.sales') }}</span>
                    </a>
                </li>
           
            @endif

            <li class="navbar-auth-user__dropdown-item">
                <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/supports") : '/panel' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-message-question class="icons" width="24px" height="24px"/>
                    <span class="ml-8">{{ trans('panel.support') }}</span>
                </a>
            </li>

            <li class="navbar-auth-user__dropdown-item">
                <a href="{{ $authUser->getProfileUrl() }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-profile class="icons" width="24px" height="24px"/>
                    <span class="ml-8">{{ trans('public.profile') }}</span>
                </a>
            </li>

            <li class="navbar-auth-user__dropdown-item">
                <a  href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/settings") : '/panel' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-setting-2 class="icons" width="24px" height="24px"/>
                    <span class="ml-8">{{ trans('panel.settings') }}</span>
                </a>
            </li>

            <li class="navbar-auth-user__dropdown-item">
                <a href="/logout" class="d-flex align-items-center w-100 px-16 py-8  bg-transparent">
                    <x-iconsax-lin-logout class="icons text-danger" width="24px" height="24px"/>
                    <span class="ml-8 text-danger">{{ trans('panel.log_out') }}</span>
                </a>
            </li>
        </ul>

    </div>
</div>
