(function ($) {
    "use strict";

    $('body').on('click', '.js-submit-special-offer-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('form');
        const action = $form.attr('action');

        handleSendRequestItemForm($form, $this, action)
    })


})(jQuery);
