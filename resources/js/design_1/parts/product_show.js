(function ($) {
    "use strict"

    $(document).ready(function () {

        var offerCountDown = $('#offerCountDown');

        if (offerCountDown.length) {
            var endtimeDate = offerCountDown.attr('data-day');
            var endtimeHours = offerCountDown.attr('data-hour');
            var endtimeMinutes = offerCountDown.attr('data-minute');
            var endtimeSeconds = offerCountDown.attr('data-second');

            offerCountDown.countdown100({
                endtimeYear: 0,
                endtimeMonth: 0,
                endtimeDate: endtimeDate,
                endtimeHours: endtimeHours,
                endtimeMinutes: endtimeMinutes,
                endtimeSeconds: endtimeSeconds,
                timeZone: ""
            });
        }

    })

    $('body').on('click', '.js-product-other-image', function (e) {
        e.preventDefault();
        const $image = $(this).find('img');
        const $mainImage = $('.js-product-main-image');

        const newPath = $image.attr('src');
        $mainImage.attr("src", newPath);

    })

    /*===========
    | Quantity
    * *********/
    function handleQuantityValue(type) {
        const input = $('.js-product-quantity-card input[name="quantity"]');

        let value = input.val();

        const productAvailabilityCount = $('#productAvailabilityCount').val();

        if (type === 'minus' && value > 1) {
            value = Number(value) - 1;
        } else if (type === 'plus') {
            value = Number(value) + 1;
        }

        if (!isNaN(productAvailabilityCount) && value > Number(productAvailabilityCount)) {
            value = Number(productAvailabilityCount);
        }

        input.val(value);

        const $productPoints = $('.js-product-points');
        const productRequirePointText = $('.js-product-require-point-text');

        if ($productPoints.length) {
            const requirePoint = value * $productPoints.val();

            $('.js-buy-with-point-show-btn').find('span').text(requirePoint);

            if (productRequirePointText.length) {
                productRequirePointText.find('span').text(value * $productPoints.val());
            }
        }
    }

    $('body').on('click', '.js-product-quantity-card .minus', function (e) {
        e.preventDefault();

        handleQuantityValue('minus');
    });

    $('body').on('click', '.js-product-quantity-card .plus', function (e) {
        e.preventDefault();

        handleQuantityValue('plus');
    });

    /*===========
    | Cart
    * *********/
    $('body').on('click', '.js-add-to-cart-btn', function (e) {
        e.preventDefault();
        const $this = $(this);

        $this.addClass('loadingbar').prop('disabled', true);

        let data = {};
        const $specifications = $('.js-product-specifications');
        if ($specifications.length) {
            data = serializeObjectByTag($specifications)
        }

        const $quantity = $('input[name="quantity"]');

        data['item_id'] = $quantity.attr("data-item");
        data['item_name'] = "product_id";
        data['quantity'] = $quantity.val();

        let path = "/cart/store";

        if ($this.attr('data-direct-payment') === 'true') {
            path = "/products/direct-payment";
        }

        $.post(path, data, function (result) {
            showToast('success', result.title, result.msg);

            if (result.redirect_to && result.redirect_to !== '') {
                window.location.href = result.redirect_to;
            } else {
                setTimeout(function () {
                    window.location.reload();
                }, 2000)
            }
        }).fail(function (err) {
            $this.removeClass('loadingbar primary').prop('disabled', false);
            const errors = err.responseJSON;

            // toast
            if (errors && errors.toast_alert) {
                showToast('error', errors.toast_alert.title, errors.toast_alert.msg)
            } else if (errors && errors.msg) {
                showToast('error', errors.title, errors.msg);
            } else {
                showToast('error', oopsLang, somethingWentWrongLang);
            }
        })
    });


    /*===========
    * Installment
    * =========*/
    $('body').on('click', '.js-installments-btn', function (e) {
        e.preventDefault();

        const href = $(this).attr('href');
        const quantity = $('input[name="quantity"]').val() ?? 1;
        const specifications = $('.js-selectable-specification-item:checked');

        let path = href + '?quantity=' + quantity;

        if (specifications.length) {
            for (const specification of specifications) {
                const name = $(specification).attr('name');
                const value = $(specification).val();

                path += '&' + name + '=' + value;
            }
        }

        window.location.href = path;
    });


})(jQuery)
