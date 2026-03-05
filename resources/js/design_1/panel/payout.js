(function ($) {
    "use strict";

    $('body').on('click', '.js-request-payout-modal', function (e) {
        e.preventDefault();

        const html = $('#requestPayoutModal').html();

        const footer = `<div class="d-flex align-items-center justify-content-end">
            <form method="post" action="/panel/financial/payout/request">
                <input type="hidden" name="_token" value="${window.csrfToken}">
                <button type="button" class="js-submit-payout btn btn-primary">${submitRequestLang}</button>
            </form>
        </div>`;

        const body = makeModalHtml(payoutRequestLang, closeIcon, html, footer)

        Swal.fire({
            html: body,
            showCancelButton: false,
            showConfirmButton: false,
            width: '36rem',
            didOpen: function () {
            },
        })
    });

    $('body').on('click', '.js-submit-payout', function (e) {
        e.preventDefault();

        $(this).addClass('loadingbar primary').prop('disabled', true);

        $(this).closest('form').trigger('submit');
    });



    $('body').on('click', '.js-show-details', function () {
        const $this = $(this);
        const path = $this.attr('data-path');

        handleBasicModal(path, payoutDetailsLang, function (result, $body, $footer) {
            $footer.remove()
        }, '', '36rem')
    });


})(jQuery);
