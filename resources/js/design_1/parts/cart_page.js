(function () {
    "use strict";

    $('document').ready(function () {
        if (typeof hasErrors !== "undefined" && hasErrors === 'true') {
            showToast('error', oopsLang, hasErrorsHintLang);
        }
    })

    $('body').on('click', '.js-cart-checkout', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('form');

        $this.addClass('loadingbar').prop('disabled', true);

        $form.trigger('submit')
    });

    $('body').on('click', '.js-cart-payment-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('form');
        const $selectedChannel = $form.find('input[name="gateway"]:checked');

        if ($selectedChannel.length) {
            $this.addClass('loadingbar').prop('disabled', true);

            showToast("success", pleaseWaitLang, transferringToLang)

            const channelName = $selectedChannel.attr('data-class');
            const gatewayId = $selectedChannel.attr('id');

            if (channelName === 'Razorpay') {
                $('.razorpay-payment-button').trigger('click');
            } else {
                $form.trigger('submit');
            }
        } else {
            showToast('error', '', selectPaymentGatewayLang)
        }
    });

    // Update button label when user selects offline gateway without clicking pay
    $('body').on('change', 'input[name="gateway"]', function () {
        const id = $(this).attr('id');
        const $btnTextEl = $('.js-pay-now-text');
        if ($btnTextEl && $btnTextEl.length) {
            if (id === 'gateway_offline') {
                $btnTextEl.text('Submit offline payment');
            } else {
                $btnTextEl.text($('#gateway_credit').length ? $('#gateway_credit').closest('label').find('h6').text() ? 'Pay Now!' : 'Pay Now!' : 'Pay Now!');
                $btnTextEl.text('Pay Now!');
            }
        }
    });


    $('body').on('click', '.js-validate-coupon-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $parent = $this.parent();
        const coupon = $parent.find('input[name="coupon"]').val();
        const path = "/cart/coupon/validate";

        if (coupon) {
            const $cartSummaryCard = $('.js-cart-summary-container');
            $this.addClass('loadingbar').prop('disabled', true);

            const data = {
                coupon: coupon
            }

            $.post(path, data, function (result) {
                $this.removeClass('loadingbar').prop('disabled', false);

                if (result.code === 200) {
                    $cartSummaryCard.html(result.html);

                    $this.addClass('d-none');
                    $parent.find('.js-remove-coupon-btn').removeClass('d-none')
                }

            }).fail(err => {
                $this.removeClass('loadingbar').prop('disabled', false);
                const errors = err.responseJSON;

                if (errors.error) {
                    showToast('error', errors.error.title, errors.error.msg)
                }
            })
        } else {
            showToast('error', couponLang, enterCouponLang)
        }
    })


    $('body').on('click', '.js-remove-coupon-btn', function (e) {
        e.preventDefault();

        var html = '<div class="px-16 pb-24 pt-16">\n' +
            '    <p class="text-center">' + removeCouponHintLang + '</p>\n' +
            '    <div class="mt-24 d-flex align-items-center justify-content-center">\n' +
            '        <a href="/cart" class="btn btn-sm btn-primary">' + removeLang + '</a>\n' +
            '        <button type="button" class="btn btn-sm btn-danger ml-12 close-swl">' + cancelLang + '</button>\n' +
            '    </div>\n' +
            '</div>';

        Swal.fire({
            title: removeCouponTitleLang,
            html: html,
            icon: 'warning',
            showConfirmButton: false,
            showCancelButton: false,
            allowOutsideClick: () => !Swal.isLoading(),
        })
    });

})(jQuery);
