(function ($) {
    "use strict"

    function handleCtaCountdownTimer($element) {
        if ($element.length) {
            const endtimeDate = $element.attr('data-day');
            const endtimeHours = $element.attr('data-hour');
            const endtimeMinutes = $element.attr('data-minute');
            const endtimeSeconds = $element.attr('data-second');

            $element.countdown100({
                endtimeYear: 0,
                endtimeMonth: 0,
                endtimeDate: endtimeDate,
                endtimeHours: endtimeHours,
                endtimeMinutes: endtimeMinutes,
                endtimeSeconds: endtimeSeconds,
                timeZone: ""
            });
        }
    }

    $(document).ready(function () {
        const $countdowns = $('.js-call-to-action-countdown-card');

        if ($countdowns.length) {
            for (const countdown of $countdowns) {
                handleCtaCountdownTimer($(countdown));
            }
        }
    })

})(jQuery)
