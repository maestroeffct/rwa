(function ($) {
    "use strict"

    window.formsDatetimepicker = function () {
        const $datetimepicker = $('.datetimepicker');

        if ($datetimepicker.length) {
            const drops = $datetimepicker.attr("data-drops") ?? 'down';
            resetDatePickers(drops);
        }
    }

})(jQuery)
