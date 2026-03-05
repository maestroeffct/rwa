(function ($) {
    "use strict";


    $('body').on('change', '#reset_cart_itemsSwitch', function (e) {
        const $el = $('.js-reset-hours-field');

        if (this.checked) {
            $el.removeClass('d-none');
        } else {
            $el.addClass('d-none');
        }
    });



})(jQuery);
