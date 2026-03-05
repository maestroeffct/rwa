(function ($) {
    "use strict";

    $('body').on('click', '.js-add-meeting-session', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-path');

        handleBasicModal(path, createASessionLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="size-40 rounded-circle bg-gray-100">
                        <img src="${result.student.full_avatar}" alt="" class="img-cover rounded-circle">
                    </div>
                    <div class="ml-8">
                        <h6 class="font-14">${result.student.full_name}</h6>
                        <p class="font-12 text-gray-500 mt-4">${result.reserve_date}</p>
                    </div>
                </div>

                <button type="button" class="js-submit-meeting-session btn btn-primary">${createSessionLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '514px')
    });

    $('body').on('click', '.js-submit-meeting-session', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-meeting-reserve-create-session-form');
        const path = $form.attr('data-action');

        handleSendRequestItemForm($form, $this, path)
    });

    $('body').on('change', '.js-meeting-reserve-create-session-form input[name="session_type"]', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $agora = $('.js-meeting-reserve-create-session-form .js-agora-session-fields');
        const $external = $('.js-meeting-reserve-create-session-form .js-external-session-fields');

        if ($this.val() === "agora") {
            $agora.removeClass('d-none');
            $external.addClass('d-none');
        } else {
            $agora.addClass('d-none');
            $external.removeClass('d-none');
        }
    })


    $('body').on('click', '.js-join-to-meeting-session', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-path');

        handleBasicModal(path, joinTheMeetingLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-between">
                <div class="d-flex flex-column">
                    <span class="font-weight-bold text-dark">${result.password}</span>
                    <span class="font-12 text-gray-500 mt-4">${passwordLang}</span>
                </div>

                <a href="${result.url}" class="btn btn-primary">${joinTheSessionLang}</a>
            </div>`;
            $footer.html(footerHtml);

        }, '', '514px')
    });

    /* ===========
    * Contact Info
    * ======== */

    $('body').on('click', '.js-meeting-contact-info', function (e) {
        e.preventDefault();
        const $this = $(this);
        const title = $this.attr('data-title');
        const path = $this.attr('data-path');

        handleBasicModal(path, title, function (result, $body, $footer) {
            $footer.remove();

        }, '', '514px')
    });


    /* ===========
    * Finish Meeting
    * ======== */
    $('body').on('click', '.js-finish-meeting-reserve', function (e) {
        e.preventDefault();
        const $this = $(this);
        const title = $this.attr('data-title');
        const path = $this.attr('data-path');

        handleBasicModal(path, title, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                <a href="${result.url}" class="js-submit-finish-meeting-reserve btn btn-primary">${finishLang}</a>
            </div>`;
            $footer.html(footerHtml);

        }, '', '514px')
    });

    $('body').on('click', '.js-submit-finish-meeting-reserve', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('.js-custom-modal').find('.js-finish-meeting-reserve-form');
        const path = $form.attr('action');

        handleSendRequestItemForm($form, $this, path)
    });

})(jQuery);
