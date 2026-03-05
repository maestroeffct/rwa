(function ($) {
    "use strict";

    $('body').on('click', '.js-enter-tracking-code', function () {
        const $this = $(this);
        const saleId = $this.attr('data-sale-id');
        const orderId = $this.attr('data-product-order-id');

        const path = `/panel/store/sales/${saleId}/getProductOrder/${orderId}`;


        handleBasicModal(path, enterTrackingCodeModalTitleLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-end mt-25">
                <button type="button" class="close-swl btn btn-transparent">${cancelLang}</button>
                <button type="button" class="js-proceed-to-set-tracking-code btn btn-primary ml-24">${saveLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '44rem')
    });

    $('body').on('click', '.js-proceed-to-set-tracking-code', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('.js-custom-modal').find('.js-set-tracking-code-form');
        const action = $form.attr('action')

        handleSendRequestItemForm($form, $this, action)
    });


})(jQuery);
