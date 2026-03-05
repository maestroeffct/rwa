<div class="navbar-auth-user">
    <div class="d-flex align-items-center">
        <div class="navbar-auth-user__avatar {{ !empty($isPanelNav) ? 'is-panel-nav' : '' }} mr-4">
            <img src="{{ $authUser->getAvatar(!empty($isPanelNav) ? 44 : 29) }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}">
        </div>

        <div class="d-none d-lg-block ml-8">
            <div class="navbar-auth-user__info font-14 {{ !empty($isPanelNav) ? 'text-dark' : 'text-gray-500 ' }}">{{ $authUser->full_name }}</div>

            @if(!empty($isPanelNav))
                <span class="font-12 text-gray-500">{{ $authUser->role->caption }}</span>
            @endif
        </div>

        @if(empty($isPanelNav))
            <x-iconsax-lin-arrow-down class="icons text-dark ml-8" width="14px" height="14px"/>
        @endif
    </div>

    <div class="navbar-auth-user__dropdown {{ !empty($isPanelNav) ? 'is-panel-nav' : '' }}">
        <div class="d-flex align-items-center m-4 rounded-10 bg-gray p-12">
            <div class="dropdown__user-avatar position-relative">
                <img src="{{ $authUser->getAvatar(38) }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}">

                @if($authUser->verified)
                    <div class="dropdown__user-avatar__badge d-flex-center rounded-circle size-16 p-2 bg-primary" data-tippy-content="{{ trans('public.verified') }}">
                        <x-tick-icon class="icons text-white"/>
                    </div>
                @endif
            </div>

            <div class="ml-8 flex-1">
                <div class="font-14 font-weight-bold text-dark">{{ $authUser->full_name }}</div>
                <span class="mt-4 text-gray-500 font-12">{{ $authUser->role->caption }}</span>
            </div>
        </div>

        <ul class="my-8">

            <li class="navbar-auth-user__dropdown-item">
                <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl("/") : '/panel' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-chart-2 class="icons" width="24px" height="24px"/>
                    <span class="ml-8">{{ trans('panel.dashboard') }}</span>
                </a>
            </li>

            <li class="navbar-auth-user__dropdown-item">
                <a href="/panel/notifications" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                    <x-iconsax-lin-notification class="icons" width="24px" height="24px"/>
                    <span class="ml-8">{{ trans('panel.notifications') }}</span>

                    @if(!empty($unReadNotifications) and count($unReadNotifications))
                        <span class="count-badge d-inline-flex align-items-center justify-content-center text-white rounded-circle ml-auto font-12 bg-danger">{{ count($unReadNotifications) }}</span>
                    @endif
                </a>
            </li>

            @if(!$authUser->isUser())
                <li class="navbar-auth-user__dropdown-item">
                    <a href="/panel/courses" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                        <x-iconsax-lin-video-play class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('update.my_courses') }}</span>
                    </a>
                </li>

                <li class="navbar-auth-user__dropdown-item">
                    <a href="/panel/financial/sales" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                        <x-iconsax-lin-moneys class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('panel.sales') }}</span>
                    </a>
                </li>
            @else
                <li class="navbar-auth-user__dropdown-item">
                    <a href="/panel/courses/purchases" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                        <x-iconsax-lin-video-play class="icons" width="24px" height="24px"/>
                        <span class="ml-8">{{ trans('panel.my_classes') }}</span>
                    </a>
                </li>
            @endif

            <li class="navbar-auth-user__dropdown-item">
                <a href="/panel/support/new" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
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
                <a href="/panel/setting" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
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
