<div class="js-show-message card-with-dashed-mask d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white p-12 rounded-16 mt-20 cursor-pointer {{ !empty($notification->notificationStatus) ? 'js-seen-at' : '' }}"
     data-id="{{ $notification->id }}"
     id="showNotificationMessage{{ $notification->id }}"
>
    <div class="d-flex align-items-center">

        <div class="position-relative d-flex-center size-56 rounded-12 bg-primary">
            <x-iconsax-bol-notification-bing class="icons text-white" width="24px" height="24px"/>

            @if(empty($notification->notificationStatus))
                <span class="notification-badge"></span>
            @endif
        </div>

        <div class="ml-12">
            <h6 class="js-notification-title font-14 font-weight-bold">{{ $notification->title }}</h6>
            <p class="mt-4 font-12 text-gray-500">{!! truncate(strip_tags($notification->message), 150, true) !!}</p>
        </div>
    </div>

    <span class="js-notification-time text-gray-500 mt-16 mt-lg-0">{{ dateTimeFormat($notification->created_at, 'j M Y | H:i') }}</span>

    <input type="hidden" class="js-notification-message" value="{{ $notification->message }}">
</div>
