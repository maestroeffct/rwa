(function ($) {
    "use strict";

    /* ===========
    * Contact Info
    * ======== */
    $('body').on('click', '.js-meeting-sold-package-student-detail', function (e) {
        e.preventDefault();
        const $this = $(this);
        const title = $this.attr('data-title');
        const path = $this.attr('href');

        handleBasicModal(path, title, function (result, $body, $footer) {
            $footer.remove();

        }, '', '514px')
    });

    /* ===========
    * Session Date/Time
    * ======== */
    $('body').on('click', '.js-meeting-package-session-set-time', function (e) {
        e.preventDefault();
        const $this = $(this);
        const title = $this.attr('data-title');
        const path = $this.attr('href');

        handleBasicModal(path, title, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-between ">
                <div class="">
                    <h6 class="font-14">${result.hours} ${hoursLang}</h6>
                    <p class="font-12 text-gray-500 mt-4">${sessionDurationLang}</p>
                </div>

                <button type="button" class="js-save-meeting-package-session-time btn btn-primary">${saveLang}</button>
            </div>`;

            $footer.html(footerHtml);

            const $datetimepicker = $body.find('.datetimepicker');
            makeDateTimepicker($datetimepicker)

        }, '', '514px')
    });

    $('body').on('click', '.js-save-meeting-package-session-time', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $modal = $this.closest('.js-custom-modal');
        const $form = $modal.find('.js-meeting-package-session-time-form');

        const path = $form.attr('action');

        handleSendRequestItemForm($form, $this, path)
    });


    /* ===========
    * Session API
    * ======== */
    $('body').on('change', '.js-meeting-package-create-session-form input[name="session_type"]', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $agora = $('.js-meeting-package-create-session-form .js-agora-session-fields');
        const $external = $('.js-meeting-package-create-session-form .js-external-session-fields');

        if ($this.val() === "agora") {
            $agora.removeClass('d-none');
            $external.addClass('d-none');
        } else {
            $agora.addClass('d-none');
            $external.removeClass('d-none');
        }
    })


    /* ===========
    * Create Session
    * ======== */

    $('body').on('click', '.js-meeting-package-create-session', function (e) {
        e.preventDefault();
        const $this = $(this);
        const title = $this.attr('data-title');
        const path = $this.attr('href');

        handleBasicModal(path, title, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-between ">
                <div class="d-flex align-center">
                    <div class="size-40 bg-gray-100 rounded-circle">
                        <img src="${result.student.avatar_path}" alt="" class="img-cover rounded-circle">
                    </div>
                    <div class="ml-8">
                        <h6 class="font-14">${result.student.full_name}</h6>
                        <p class="font-12 text-gray-500 mt-4">${result.student.date}</p>
                    </div>
                </div>

                <button type="button" class="js-save-meeting-package-session-api btn btn-primary">${createSessionLang}</button>
            </div>`;

            $footer.html(footerHtml);

            const $datetimepicker = $body.find('.datetimepicker');
            makeDateTimepicker($datetimepicker)

        }, '', '514px')
    });

    $('body').on('click', '.js-save-meeting-package-session-api', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $modal = $this.closest('.js-custom-modal');
        const $form = $modal.find('.js-meeting-package-create-session-form');

        const path = $form.attr('action');

        handleSendRequestItemForm($form, $this, path)
    });

})(jQuery);
