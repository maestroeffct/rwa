(function ($) {
    "use strict"

    $('body').on('click', '.js-meeting-package-add-to-cart', function (e) {
        e.preventDefault();
        const $this = $(this);
        const packageId = $this.attr('data-package');

        const $html = $this.html();
        const $loading = `<img src="/assets/design_1/img/loading.svg" width="24px" height="24px">`;

        $this.html($loading)

        const data = {
            item_id: packageId,
            item_name: "meeting_package_id",
        }

        $.post('/cart/store', data, function (result) {
            showToast('success', result.title, result.msg);

            const timeout = (result.redirect_timeout) ? Number(result.redirect_timeout) : 1000;

            setTimeout(() => {
                if (result.redirect_to && result.redirect_to !== 'null') {
                    window.location.href = result.redirect_to;
                } else {
                    window.location.reload();
                }
            }, timeout)

            $this.html($html)

        }).fail(function (err) {
            const errors = err.responseJSON;

            // toast
            if (errors.toast_alert) {
                showToast('error', errors.toast_alert.title, errors.toast_alert.msg)
            }

            $this.html($html)
        })
    });

})(jQuery)
