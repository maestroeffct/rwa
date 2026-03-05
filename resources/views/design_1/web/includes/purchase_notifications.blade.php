@if(!empty($purchaseNotifications) and count($purchaseNotifications))

    <div class="purchase-notifications-card d-flex flex-column gap-16">
        @foreach($purchaseNotifications as $purchaseNotification)
            @if(!empty($purchaseNotification->content))
                <div class="purchase-notification d-none bg-white rounded-12 soft-shadow-5 js-purchase-notification-{{ $purchaseNotification->id }}">
                    <div class="d-flex align-items-start p-12">
                        <div class="purchase-notification__image bg-gray-100">
                            <img src="{{ $purchaseNotification->content->getImage() }}" alt="{{ $purchaseNotification->content->title }}" class="img-cover">
                        </div>

                        <div class="ml-8 d-flex flex-column align-items-start">
                            <h4 class="font-14 font-weight-bold text-dark">{{ $purchaseNotification->notif_title }}</h4>
                            <p class="mt-8 mb-16 font-12 text-gray-500">{{ $purchaseNotification->notif_subtitle }}</p>

                            <div class="py-4 px-8 rounded-32 bg-gray font-12 text-gray-500 mt-auto">{{ $purchaseNotification->time }}</div>
                        </div>
                    </div>

                    <div class="purchase-notification__progress">
                        <div class="purchase-notification__progress-bar"></div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <script>
        (function () {
            "use strict";

            function handleProgressPurchaseNotification(time) {
                const $el = $('.purchase-notification__progress-bar');
                let timeLeft = time;

                let progressPurchaseNotificationInterval = setInterval(function () {
                    timeLeft -= 1;
                    const percent = ((time - timeLeft) / time) * 100;

                    $el.css('width', percent + '%')

                    if (timeLeft <= 0) {
                        clearInterval(progressPurchaseNotificationInterval);
                    }
                }, 1000)
            }

            @foreach($purchaseNotifications as $purchaseNotification)
            @if(!empty($purchaseNotification->content))
            setTimeout(function () {
                const purchaseNotificationHideAfter = Number('{{ !empty($purchaseNotification->popup_duration) ? ($purchaseNotification->popup_duration * 1000) : 5000 }}');
                const $el = $(`.js-purchase-notification-{{ $purchaseNotification->id }}`);

                $el.removeClass('d-none').addClass('d-block');

                // add Active class
                setTimeout(function () {
                    $el.addClass('active');
                }, 10)

                handleProgressPurchaseNotification(purchaseNotificationHideAfter / 1000)

                setTimeout(function () {
                    $el.removeClass('active');

                    // remove Active class
                    setTimeout(function () {
                        $el.removeClass('d-block').addClass('d-none');
                    }, 500)
                }, purchaseNotificationHideAfter)

            }, Number('{{ !empty($purchaseNotification->popup_delay) ? ($purchaseNotification->popup_delay * 1000) : 0 }}'))
            @endif
            @endforeach

        })(jQuery)
    </script>
@endif
