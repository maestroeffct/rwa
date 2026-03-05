(function ($) {
    "use strict"

    var advertisingModalSwal;


    $(document).ready(function () {
        if (typeof hasAdvertisingModal !== "undefined" && hasAdvertisingModal) {
            const delay = ((typeof openingDelayAdvertisingModal !== "undefined" && openingDelayAdvertisingModal) ? openingDelayAdvertisingModal : 0) * 1000;

            setTimeout(function () {
                getAdvertisingModal();
            }, delay)
        }
    });

    function getAdvertisingModal() {
        const path = "/get-advertising-modal";

        const html = `<div class="basic-modal-body">
                <div class="js-loading-card d-flex align-items-center justify-content-center py-40">
                    <img src="/assets/design_1/img/loading.svg" width="80" height="80">
                </div>
            </div>`;

        advertisingModalSwal = Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                container: 'custom-advertising-modal',
            },
            width: '32rem',
            didOpen: function () {
                const $body = $('.basic-modal-body');
                const $footer = $('.custom-modal-footer');

                const data = {};

                $.post(path, data, function (result) {
                    if (result.code === 200) {
                        $body.find('.js-loading-card').remove();
                        $body.html(result.html);

                        handleCountdownTimer();
                        handleAutocloseModal();
                    }

                }).fail(err => {

                })
            }
        });

    }

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

    function handleAutocloseModal() {
        const $el = $('.js-advertising-modal-autoclose-progress');

        if ($el.length) {
            const seconds = $el.attr("data-seconds");

            if (seconds > 0) {
                var passedSeconds = 1;

                let intervalCountdown = setInterval(function () {
                    let percent = (passedSeconds / seconds) * 100;

                    if (percent > 100) {
                        percent = 100;
                    }

                    $el.css('width', percent + '%');


                    if (passedSeconds >= seconds) {
                        clearInterval(intervalCountdown)

                        if (advertisingModalSwal) {
                            advertisingModalSwal.close();
                        } else  {
                            Swal.close();
                        }
                    } else {
                        passedSeconds += 1;
                    }

                }, 1000)
            }
        }
    }


})(jQuery)
