(function ($) {
    "use strict";

    $('body').on('click', '.js-save-post-content', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('.js-content-form');

        handleSendRequestItemForm($form, $this)
    })


})(jQuery);
