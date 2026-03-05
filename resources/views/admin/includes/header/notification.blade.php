<div class="language-select position-relative cursor-pointer">
    <div class="size-32 position-relative d-flex-center rounded-8 cursor-pointer" style="background-color: rgba(255, 255, 255, 0.2);">
        <x-iconsax-lin-notification class="icons text-white" width="20px" height="20px"/>

        @if(!empty($unReadNotifications) and count($unReadNotifications))
            <span class="admin-header__badge-counter badge-counter">{{ count($unReadNotifications) }}</span>
        @endif
    </div>

    <div class="language-dropdown language-dropdown__notifications py-12">
        @if(!empty($unReadNotifications) and count($unReadNotifications))
            <div class="px-12">

                <div class="d-flex align-items-center p-12 rounded-12 bg-gray-100">
                    <div class="d-flex-center size-48 bg-white rounded-circle">
                        <div class="d-flex-center size-40 bg-primary rounded-circle">
                            <x-iconsax-bul-notification-bing class="icons text-white" width="24px" height="24px"/>
                        </div>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-14">{{ count($unReadNotifications) }} {{ trans('panel.notifications') }}</h5>
                        <a href="{{ getAdminPanelUrl("/notifications/mark_all_read") }}" class="delete-action d-block mt-4 font-12 cursor-pointer text-gray-500" data-title="{{ trans('update.convert_unread_messages_to_read') }}" data-confirm="{{ trans('update.yes_convert') }}">
                            {{ trans('update.mark_as_read') }}
                        </a>
                    </div>
                </div>

            </div>

            @foreach($unReadNotifications->take(3) as $unReadNotification)
                <a href="{{ getAdminPanelUrl("/notifications?notification={$unReadNotification->id}") }}" class="language-dropdown__item  d-flex align-items-center w-100 px-16 py-8 text-dark bg-transparent">
                    <div class="">
                        <x-iconsax-bul-notification class="icons text-gray-500" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h4 class="font-12">{{ $unReadNotification->title }}</h4>
                        <span class="d-block text-gray-500 font-12 mt-8">{{ dateTimeFormat($unReadNotification->created_at, 'j M Y | H:i') }}</span>
                    </div>
                </a>
            @endforeach

            <div class="px-12">
                <a href="{{ getAdminPanelUrl("/notifications") }}" class="btn btn-lg btn-primary btn-block mt-12">{{ trans('notification.all_notifications') }}</a>
            </div>
        @else
            <div class="d-flex-center flex-column text-center px-16 py-54">
                <div class="d-flex-center size-40 bg-primary rounded-circle">
                    <x-iconsax-bul-notification-bing class="icons text-white" width="24px" height="24px"/>
                </div>

                <span class="mt-12 text-gray-500">{{ trans('notification.empty_notifications') }}</span>
            </div>
        @endif
    </div>
</div>
