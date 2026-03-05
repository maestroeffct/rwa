(function ($) {
    "use strict"

    $(document).ready(function () {

        const items = $('.js-course-chart');

        if (items.length) {
            for (const item of items) {
                const $item = $(item);

                const id = $item.attr("data-id");
                const percent = Number($item.attr("data-percent"));
                const ctx = document.getElementById(`${id}`).getContext('2d');

                makeCircleChart(ctx, [percent, (100 - percent)], null, null, false)
            }
        }

    })


    /*======
    * Session
    * */
    $('body').on('click', '.js-add-event-session', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-path');

        handleBasicModal(path, createASessionLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-submit-events-session btn btn-primary">${createSessionLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '514px')
    });

    $('body').on('click', '.js-submit-events-session', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-events-create-session-form');
        const path = $form.attr('data-action');

        handleSendRequestItemForm($form, $this, path)
    });

    $('body').on('change', '.js-events-create-session-form input[name="session_type"]', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $agora = $('.js-events-create-session-form .js-agora-session-fields');
        const $external = $('.js-events-create-session-form .js-external-session-fields');

        if ($this.val() === "agora") {
            $agora.removeClass('d-none');
            $external.addClass('d-none');
        } else {
            $agora.addClass('d-none');
            $external.removeClass('d-none');
        }
    })


    $('body').on('click', '.js-join-to-events-session', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-path');

        handleBasicModal(path, joinTheEventLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-between">
                <div class="d-flex flex-column">
                    <span class="font-weight-bold text-dark">${result.password}</span>
                    <span class="font-12 text-gray-500 mt-4">${passwordLang}</span>
                </div>

                <a href="${result.url}" target="_blank" class="btn btn-primary">${joinTheSessionLang}</a>
            </div>`;
            $footer.html(footerHtml);

        }, '', '514px')
    });

})(jQuery)
