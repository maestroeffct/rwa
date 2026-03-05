(function ($) {
    "use strict";

    $('body').on('click', '.js-show-message', function (e) {
        e.preventDefault();

        const $this = $(this);
        const notification_id = $this.attr('data-id');

        const title = $this.find('.js-notification-title').text();
        const time = $this.find('.js-notification-time').text();
        const message = $this.find('.js-notification-message').val();

        const html = `<div class="text-center">
            <h3 class="font-16 font-weight-bold">${title}</h3>
            <span class="d-block font-12 text-gray-500 mt-8">${time}</span>

            <div class="text-gray-500 mt-20">${message}</div>
        </div>`

        const footer = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="close-swl btn btn-sm btn-danger">${closeLang}</button>
        </div>`;

        const body = makeModalHtml(viewNotificationLang, closeIcon, html, footer)

        Swal.fire({
            html: body,
            showCancelButton: false,
            showConfirmButton: false,
            width: '36rem',
            didOpen: function () {
            },
        })

        if (!$this.hasClass('js-seen-at')) {
            $.get('/panel/notifications/' + notification_id + '/saveStatus', function () {
                $this.addClass('seen-at');
                $this.find('.notification-badge').remove();
            })
        }
    });
})(jQuery);
