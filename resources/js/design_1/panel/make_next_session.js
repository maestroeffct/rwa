(function ($) {
    "use strict";

    $('body').on('click', '.js-next-session-info', function (e) {
        e.preventDefault();

        const $this = $(this);
        const webinarId = $this.attr('data-webinar-id');
        const path = `/panel/courses/${webinarId}/getNextSessionInfo`

        handleBasicModal(path, liveSessionInfoLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-end mt-25">
                <a href="${result.join_url}" target="_blank" class="btn btn-sm btn-primary">${joinTheSessionLang}</a>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-8">${closeLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '40rem')


    });

})(jQuery);
