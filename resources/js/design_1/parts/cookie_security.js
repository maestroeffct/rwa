(function ($) {
    "use strict";

    $(document).ready(function () {

        $('.js-cookie-security-dialog-box').fadeIn(900);
        //$('.cu').fadeOut(900);
    })

    function handleStoreCookieSecurity($this, action, data) {
        $this.addClass('loadingbar').prop('disabled', true);

        $.post(action, data, function (result) {
            $this.removeClass('loadingbar primary').prop('disabled', false);

            if (result && result.code === 200) {
                showToast("success", result.title, result.msg)

                $('.js-cookie-security-dialog-box').remove();

                Swal.close();
            }
        }).fail(err => {
            $this.removeClass('loadingbar').prop('disabled', false);

            showToast('error', oopsLang, somethingWentWrongLang);
        });
    }

    $('body').on('click', '.js-accept-all-cookies', function (e) {
        e.preventDefault();

        const $this = $(this);
        const action = '/cookie-security/all';
        const data = {};

        handleStoreCookieSecurity($this, action, data);
    });

    $('body').on('click', '.js-store-customize-cookies', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $('.js-cookie-form-customize-inputs');
        const action = '/cookie-security/customize';
        const data = $form.serializeObject();

        handleStoreCookieSecurity($this, action, data);
    });

    $('body').on('click', '.js-show-cookie-customize-settings', function (e) {
        e.preventDefault();

        const path = '/cookie-security/customize-modal';

        handleBasicModal(path, cookieInformationLang, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end gap-12">
                    <button type="button" class="js-store-customize-cookies btn btn-transparent font-14 text-gray-500">${confirmMyChoicesLang}</button>
                    <button type="button" class="js-accept-all-cookies btn btn-primary font-14">${acceptAllLang}</button>
                </div>`;
            $footer.html(footerHtml);

            handleAccordionCollapse();

        }, '', '48rem')
    });

})(jQuery);
