(function ($) {
    "use strict"

    $('body').on('click', '.js-submit-newsletter-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('.js-newsletter-form');
        const path = "/newsletters"

        handleSendRequestItemForm($form, $this, path)
    })

})(jQuery)
