(function ($) {
    "use strict";

    $('body').on('click', '.js-view-cart-drawer', function () {
        const loadingHtml = '<div class="d-flex align-items-center justify-content-center my-56 "><img src="/assets/design_1/img/loading.svg" width="80" height="80"></div>';

        const $sideCart = $('.cart-drawer');
        const $sideCartBody = $sideCart.find('.cart-drawer__body');
        const $sideCartFooter = $sideCart.find('.cart-drawer__footer');

        $sideCart.css('height', `${window.innerHeight}px`);

        lockBodyScroll(true);

        $sideCartBody.html(loadingHtml);
        $sideCart.addClass('show');
        $sideCartFooter.addClass('d-none')

        $.get('/cart/get-drawer-info', function (result) {
            if (result) {
                $sideCartBody.html(result.html)

                $sideCartFooter.find('.js-side-cart-subtotal').html(result.subtotal);

                if (!result.is_empty) {
                    $sideCartFooter.removeClass('d-none')
                    $sideCart.removeClass('no-footer')
                } else {
                    $sideCartFooter.addClass('d-none')
                    $sideCart.addClass('no-footer')
                }
            }
        })
    })

    $('body').on('click', '.cart-drawer-mask, .js-cart-drawer-close', function () {
        $('.cart-drawer').removeClass('show')

        lockBodyScroll(false);
    })


    /*
    * Cart Quantity
    * */
    var updateQuantityRequest = undefined;

    function handleUpdateQuantity(path, quantity, $priceDiv = null) {

        let $priceDivLoading;
        let $priceDivCard;

        if ($priceDiv && $priceDiv.length) {
            $priceDivLoading = $priceDiv.find('.js-cart-price-loading-card');
            $priceDivCard = $priceDiv.find('.js-cart-price-card');

            $priceDivLoading.removeClass('d-none');
            $priceDivCard.addClass('d-none')
        }


        const data = {
            quantity
        }

        if (updateQuantityRequest) {
            clearTimeout(updateQuantityRequest)
        }


        updateQuantityRequest = setTimeout(function () {
            $.post(path, data, function (result) {
                if (result) {
                    showToast('success', result.title, result.msg);

                    if ($priceDiv && $priceDiv.length && $priceDivCard) {
                        let html = "";

                        if (result.price_offed > 0) {
                            html = `<div class="d-flex flex-column">
                                <span class="font-16 font-weight-bold text-primary">${result.price}</span>
                                <span class="text-decoration-line-through font-12 text-gray-500 mt-4">${result.price_offed}</span>
                            </div>`
                        } else {
                            html = `<span class="font-16 font-weight-bold text-primary">${result.price}</span>`;
                        }

                        $priceDivCard.html(html)
                    }
                }

                if ($priceDivLoading && $priceDivCard) {
                    $priceDivLoading.addClass('d-none');
                    $priceDivCard.removeClass('d-none')
                }
            }).fail(err => {
                showToast('error', oopsLang, somethingWentWrongLang);

                if ($priceDivLoading && $priceDivCard) {
                    $priceDivLoading.addClass('d-none');
                    $priceDivCard.removeClass('d-none')
                }
            })
        }, 700)
    }

    function handleQuantityValue(type, $this) {
        const $parent = $this.closest('.js-cart-quantity');
        const input = $parent.find('input[name="quantity"]');

        let value = input.val();

        const productAvailabilityCount = $parent.find('.js-product-availability-count').val();

        if (type === 'minus' && value > 1) {
            value = Number(value) - 1;
        } else if (type === 'add') {
            value = Number(value) + 1;
        }

        if (!isNaN(productAvailabilityCount) && value > Number(productAvailabilityCount)) {
            value = Number(productAvailabilityCount);
        }

        //
        const updatePath = $parent.attr('data-path')

        if (updatePath && Number(input.val()) !== Number(value)) {
            const $priceDiv = $parent.find('.js-cart-item-price-div');

            handleUpdateQuantity(updatePath, Number(value), $priceDiv);
        }

        input.val(value);
    }

    $('body').on('click', '.js-cart-drawer-item-quantity-btn', function (e) {
        e.preventDefault();
        const $this = $(this)
        const operation = $this.attr('data-operation');

        handleQuantityValue(operation, $this);
    });


})(jQuery)
