<div class="theme-header-1__dropdown position-relative">
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 bg-white rounded-circle">
            <div class="d-flex-center size-32 rounded-circle">
                <img src="{{ $authUser->getAvatar(32) }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}">
            </div>
        </div>
        <div class="ml-8 text-white opacity-75">{{ $authUser->full_name }}</div>
        <x-iconsax-lin-arrow-down class="icons text-white ml-12 opacity-75" width="16px" height="16px"/>
    </div>

    <div class="header-1-dropdown-menu auth-user-info-dropdown-menu py-12">
        {{-- User --}}
        <div class="px-12">
            <div class="d-flex align-items-center justify-content-between bg-gray-100 rounded-12 p-12">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 bg-white rounded-circle">
                        <div class="position-relative d-flex-center size-40 rounded-circle">
                            <img src="{{ $authUser->getAvatar(40) }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}">

                            @if($authUser->verified)
                                <div class="theme-header-1__dropdown-user-verify-badge d-flex-center rounded-circle size-16 p-2 bg-primary" data-tippy-content="{{ trans('public.verified') }}">
                                    <x-tick-icon class="icons text-white"/>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-14 font-weight-bold">{{ truncate($authUser->full_name, 19) }}</h5>
                        <p class="mt-4 font-12 text-gray-500">{{ $authUser->role->caption }}</p>
                    </div>
                </div>

                <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
            </div>
        </div>
        {{-- End User --}}

        <ul class="my-8">
            <li class="header-1-dropdown-menu__item">
                <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/") : '/panel' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-chart-2 class="icons" width="24px" height="24px"/>
                    <span class="ml-8">{{ trans('panel.dashboard') }}</span>
                </a>
            </li>

            <li class="header-1-dropdown-menu__item">
                <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/notifications") : '/panel/notifications' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-notification class="icons" width="24px" height="24px"/>
                    <span class="ml-8">{{ trans('panel.notifications') }}</span>

                    @if(!empty($unReadNotifications) and count($unReadNotifications))
                        <span class="count-badge d-inline-flex align-items-center justify-content-center text-white rounded-circle ml-auto font-12 bg-danger">{{ count($unReadNotifications) }}</span>
                    @endif
                </a>
            </li>

            @if(!$authUser->isUser())
                <li class="header-1-dropdown-menu__item">
                    <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/webinars?type=course") : '/panel/courses' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                        <x-iconsax-lin-video-play class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.my_courses') }}</span>
                    </a>
                </li>

                <li class="header-1-dropdown-menu__item">
                    <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/financial/sales") : '/panel/financial/sales' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                        <x-iconsax-lin-moneys class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('panel.sales') }}</span>
                    </a>
                </li>
            @else
                <li class="header-1-dropdown-menu__item">
                    <a href="/panel/courses/purchases" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                        <x-iconsax-lin-video-play class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('panel.my_classes') }}</span>
                    </a>
                </li>
            @endif

            <li class="header-1-dropdown-menu__item">
                <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/supports") : '/panel/support/new' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-message-question class="icons" width="24px" height="24px"/>
                    <span class="ml-8">{{ trans('panel.support') }}</span>
                </a>
            </li>

            <li class="header-1-dropdown-menu__item">
                <a href="{{ $authUser->getProfileUrl() }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-profile class="icons" width="24px" height="24px"/>
                    <span class="ml-8">{{ trans('public.profile') }}</span>
                </a>
            </li>

            <li class="header-1-dropdown-menu__item">
                <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/settings") : '/panel/setting' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-setting-2 class="icons" width="24px" height="24px"/>
                    <span class="ml-8">{{ trans('panel.settings') }}</span>
                </a>
            </li>

            <li class="header-1-dropdown-menu__item">
                <a href="/logout" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-logout class="icons text-danger" width="24px" height="24px"/>
                    <span class="ml-8 text-danger">{{ trans('panel.log_out') }}</span>
                </a>
            </li>
        </ul>

    </div>
</div>
