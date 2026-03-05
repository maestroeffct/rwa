(function ($) {
    "use strict"

    $(document).ready(function () {

        const eventCountdownTime = $('#eventCountdownTime');

        if (eventCountdownTime.length) {
            const endtimeDate = eventCountdownTime.attr('data-day');
            const endtimeHours = eventCountdownTime.attr('data-hour');
            const endtimeMinutes = eventCountdownTime.attr('data-minute');
            //var endtimeSeconds = eventCountdownTime.attr('data-second');

            eventCountdownTime.countdown100({
                endtimeYear: 0,
                endtimeMonth: 0,
                endtimeDate: endtimeDate,
                endtimeHours: endtimeHours,
                endtimeMinutes: endtimeMinutes,
                //endtimeSeconds: endtimeSeconds,
                timeZone: ""
            });
        }


        /* Bottom Fixed */
        const $bottomFixedCard = $('.event-bottom-fixed-card');
        const $bottomFixedProgress = $('.event-bottom-fixed-card__progress');

        $(document).scroll(function () {
            const scrollTop = $(this).scrollTop();

            if (scrollTop > 500) {
                $bottomFixedCard.addClass('show');
            } else {
                $bottomFixedCard.removeClass('show');
            }

            updateProgressBar()
        });


        function updateProgressBar() {
            const scrollTop = $(window).scrollTop();
            const scrollHeight = $(document).height();
            const clientHeight = $(window).height();

            // Calculate the scroll percentage
            const scrollPercentage = (scrollTop / (scrollHeight - clientHeight)) * 100;

            // Update the width of the progress bar
            $bottomFixedProgress.find('.progress-line').css('width', scrollPercentage + '%');
        }
    })

    /**
     * Bottom Actions
     * */
    $('body').on('click', '.js-scroll-to-event-tickets-btn', function (e) {
        e.preventDefault();

        const $toggleTab = $('#showEventAboutTab');
        $toggleTab.trigger('click')

        const $container = $('#eventTicketsContainer');

        $('html, body').animate({
            scrollTop: $container.offset().top - 150
        }, 500);
    })

    /**
     * Share Modal
     * */
    $('body').on('click', '.js-share-event', function (e) {
        e.preventDefault();

        const path = $(this).attr("data-path");

        handleBasicModal(path, shareLang, function (result, $body, $footer) {
            $footer.addClass('d-none')
        }, '', '40rem')
    });

    /**
     * Report Modal
     * */
    $('body').on('click', '.js-report-event', function (e) {
        e.preventDefault();

        const path = $(this).attr("data-path");

        handleBasicModal(path, reportEventLang, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-submit-event-report btn btn-primary">${reportLang}</button>
            </div>`;
            $footer.html(footerHtml);
        }, '', '40rem')
    });

    $('body').on('click', '.js-submit-event-report', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('.js-custom-modal').find('.js-event-report-form')
        const path = $form.attr("action");

        handleSendRequestItemForm($form, $this, path)
    })

    /*===========
    | Quantity
    * *********/
    function handleQuantityValue($btn, type) {
        const $card = $btn.closest('.js-event-quantity-card');
        const $input = $card.find('input[name="quantity"]');
        const eventAvailabilityCount = $card.find('.js-event-availability-count').val();

        let value = $input.val();

        if (type === 'minus' && value > 1) {
            value = Number(value) - 1;
        } else if (type === 'plus') {
            value = Number(value) + 1;
        }

        if (!isNaN(eventAvailabilityCount) && value > Number(eventAvailabilityCount)) {
            value = Number(eventAvailabilityCount);
        }

        $input.val(value);
    }

    $('body').on('click', '.js-event-quantity-card .minus', function (e) {
        e.preventDefault();
        const $this = $(this)

        handleQuantityValue($this, 'minus');
    });

    $('body').on('click', '.js-event-quantity-card .plus', function (e) {
        e.preventDefault();
        const $this = $(this)

        handleQuantityValue($this, 'plus');
    });

    /**/
    $('body').on('click', '.js-view-more-reviews', function (e) {
        e.preventDefault();

        const $toggleTab = $('#showEventReviewsTab');

        $('html, body').animate({
            scrollTop: $toggleTab.offset().top - 150
        }, 1000, function () {
            $toggleTab.trigger('click')
        });
    })

    /********
     *  Add To Cart
     * */
    $('body').on('click', '.js-event-ticket-add-to-cart', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('.js-event-ticket-add-to-cart-form');
        const path = "/cart/store";

        handleSendRequestItemForm($form, $this, path)
    })


    /********
     *  Free Ticket
     * */
    $('body').on('click', '.js-event-get-free-ticket-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('.js-event-ticket-add-to-cart-form');
        const path = $this.attr('data-path');

        handleSendRequestItemForm($form, $this, path)
    })

    /**
     * event demo modal
     * */
    var courseDemoVideoPlayer;

    $('body').on('click', '#eventDemoVideoBtn', function (e) {
        e.preventDefault();

        if (courseDemoVideoPlayer !== undefined) {
            courseDemoVideoPlayer.stop();
        }

        let path = $(this).attr('data-video-path');
        let source = $(this).attr('data-video-source');
        let thumbnail = $(this).attr('data-thumbnail');
        const height = $(window).width() > 991 ? 480 : 264;

        const videoTagId = 'demoVideoPlayer';
        const {html, options} = makeVideoPlayerHtml(path, source, height, videoTagId, thumbnail);

        /*const footer = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="close-swl btn btn-transparent mr-16">${closeLang}</button>
        </div>`;*/


        const body = makeModalHtml(eventDemoLang, closeIcon, html, '&nbsp;')

        Swal.fire({
            html: body,
            showCancelButton: false,
            showConfirmButton: false,
            width: '48rem',
            didOpen: function () {
                const $videoTagEl = $(`#${videoTagId}`);

                if ($videoTagEl.length) {
                    courseDemoVideoPlayer = new Plyr(`#${videoTagId}`, options);
                }
            },
        })
    });

})(jQuery)
