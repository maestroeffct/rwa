(function ($) {
    "use strict";

    $('body').on('click', '.js-submit-gift-form-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest("form");
        const path = $form.attr("action")

        handleSendRequestItemForm($form, $this, path)
    })
})(jQuery);
