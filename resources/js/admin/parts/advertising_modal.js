(function ($) {
    "use strict";

    function handleCountdownTimer() {
        const advertisingModalCountdown = $('#advertisingModalCountdown');

        if (advertisingModalCountdown.length) {
            const endtimeDate = advertisingModalCountdown.attr('data-day');
            const endtimeHours = advertisingModalCountdown.attr('data-hour');
            const endtimeMinutes = advertisingModalCountdown.attr('data-minute');
            const endtimeSeconds = advertisingModalCountdown.attr('data-second');

            advertisingModalCountdown.countdown100({
                endtimeYear: 0,
                endtimeMonth: 0,
                endtimeDate: endtimeDate,
                endtimeHours: endtimeHours,
                endtimeMinutes: endtimeMinutes,
                endtimeSeconds: endtimeSeconds,
                timeZone: ""
            });
        }
    }


    $('body').on('click', '.js-preview-modal', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-path')

        const html = `<div class="basic-modal-body">
                <div class="js-loading-card d-flex align-items-center justify-content-center py-40">
                    <img src="/assets/design_1/img/loading.svg" width="80" height="80">
                </div>
            </div>`;

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                container: 'custom-advertising-modal',
            },
            width: '32rem',
            onOpen: function () {
                const $body = $('.basic-modal-body');
                const $footer = $('.custom-modal-footer');

                const data = {};

                $.get(path, function (result) {
                    if (result.code === 200) {
                        $body.find('.js-loading-card').remove();
                        $body.html(result.html);

                        handleCountdownTimer();
                    }

                }).fail(err => {

                })
            }
        });

    });
})(jQuery);
