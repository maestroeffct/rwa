(function ($) {
    "use strict";

    $('body').on('click', '.js-exchange-btn', function (e) {
        e.preventDefault();

        const html = $('#exchangePointsModal').html();

        const footer = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="close-swl btn btn-transparent mr-16">${closeLang}</button>
                <button type="button" class="js-apply-exchange btn btn-primary">${convertPointsLang}</button>
        </div>`;

        const body = makeModalHtml(exchangePointsLang, closeIcon, html, footer)

        Swal.fire({
            html: body,
            showCancelButton: false,
            showConfirmButton: false,
            width: '36rem',
            didOpen: function () {
            },
        })

    });

    $('body').on('click', '.js-apply-exchange', function (e) {
        const $this = $(this);

        $this.addClass('loadingbar primary').prop('disabled', true);

        $.post('/panel/rewards/exchange', {}, function (result) {
            if (result && result.code === 200) {
                showToast('success', exchangeSuccessAlertTitleLang, exchangeSuccessAlertDescLang)

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        }).fail(err => {
            showToast('error', exchangeErrorAlertTitleLang, exchangeErrorAlertDescLang)
        });
    });

})(jQuery);
