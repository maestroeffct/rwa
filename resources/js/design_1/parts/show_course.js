(function ($) {
    "use strict"

    $(document).ready(function () {

        var offerCountDown = $('#offerCountDown');

        if (offerCountDown.length) {
            var endtimeDate = offerCountDown.attr('data-day');
            var endtimeHours = offerCountDown.attr('data-hour');
            var endtimeMinutes = offerCountDown.attr('data-minute');
            var endtimeSeconds = offerCountDown.attr('data-second');

            offerCountDown.countdown100({
                endtimeYear: 0,
                endtimeMonth: 0,
                endtimeDate: endtimeDate,
                endtimeHours: endtimeHours,
                endtimeMinutes: endtimeMinutes,
                endtimeSeconds: endtimeSeconds,
                timeZone: ""
            });
        }


        const $bottomFixedCard = $('.course-bottom-fixed-card');
        const $bottomFixedProgress = $('.course-bottom-fixed-card__progress');

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


    $('body').on('click', '.js-bottom-fixed-enroll-on-course-btn', function (e) {
        e.preventDefault();

        $('html, body').animate({
            scrollTop: $('.js-enroll-actions-card').offset().top - 150
        }, 1000);
    })
    /**
     * webinar demo modal
     * */
    var courseDemoVideoPlayer;

    $('body').on('click', '#webinarDemoVideoBtn', function (e) {
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


        const body = makeModalHtml(webinarDemoLang, closeIcon, html, '&nbsp;')

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

    $('body').on('change', 'input[name="ticket_id"]', function (e) {
        e.preventDefault();

        const discountPrice = $(this).attr('data-discount-price');

        const realPrice = $('#realPrice');
        const priceWithDiscount = $('#priceWithDiscount');

        realPrice.removeClass('font-24 font-weight-bold').addClass('font-14 text-gray-500 text-decoration-line-through');

        if (priceWithDiscount.length) {
            priceWithDiscount.text(discountPrice);
        } else {

            const html = `<div class="js-discounted-price-span d-flex align-items-center text-center mr-16">
                <div id="priceWithDiscount" class="d-block font-24 font-weight-bold">${discountPrice}</div>
            </div>`

            $('.js-discounted-price-span').remove()

            $("#priceBox").prepend(html);
        }
    });

    /**
     * Favorite
     * */
    $('body').on('click', '#favoriteToggle', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const $this = $(this);
        const $icons = $this.find('.icons');
        const href = $this.attr('href');

        if ($this.hasClass('text-danger')) {
            $this.removeClass('text-danger').addClass('text-gray-500');
            $icons.removeClass('text-danger').addClass('text-gray-500');
        } else {
            $this.removeClass('text-gray-500').addClass('text-danger');
            $icons.removeClass('text-gray-500').addClass('text-danger');
        }

        $.get(href, function (result) {

        });
    });


    /**
     * Share Modal
     * */
    $('body').on('click', '.js-share-course', function (e) {
        e.preventDefault();

        const path = $(this).attr("data-path");

        handleBasicModal(path, shareLang, function (result, $body, $footer) {
            $footer.addClass('d-none')
        }, '', '40rem')
    });

    /**
     * Report Modal
     * */
    $('body').on('click', '.js-report-course', function (e) {
        e.preventDefault();

        const path = $(this).attr("data-path");

        handleBasicModal(path, reportCourseLang, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-submit-course-report btn btn-primary">${reportLang}</button>
            </div>`;
            $footer.html(footerHtml);
        }, '', '40rem')
    });

    $('body').on('click', '.js-submit-course-report', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('.js-custom-modal').find('.js-course-report-form')
        const path = $form.attr("action");

        handleSendRequestItemForm($form, $this, path)
    })


    /**
     * Section Passed Status
     * */
    $('body').on('change', '.js-passed-section-toggle', function (e) {
        const $this = $(this);
        const courseSlug = $this.attr("data-course-slug");
        const item = $this.attr("data-item-name");
        const itemId = $this.val();
        const status = this.checked;

        const path = `/course/${courseSlug}/learningStatus`;

        const data = {
            item: item,
            item_id: itemId,
            status: status
        };

        $.post(path, data, function (result) {
            showToast("success", result.title, result.msg);

            /*setTimeout(() => {
                window.location.reload();
            }, 500);*/
        }).fail(err => {
            $this.prop('checked', !status);
            showToast('error', oopsLang, somethingWentWrongLang);
        });
    });

    /********
     *  Join Waitlist
     * */
    $('body').on('click', '.js-join-waitlist-user', function (e) {
        e.preventDefault();

        const path = $(this).attr("data-path");

        handleBasicModal(path, joinCourseWaitlistLang, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-join-course-waitlist-btn btn btn-primary">${joinWaitlistLang}</button>
            </div>`;
            $footer.html(footerHtml);

            refreshCaptcha();
        }, '', '40rem')
    })

    $('body').on('click', '.js-join-course-waitlist-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('.js-custom-modal').find('.js-course-waitlist-form')
        const path = $form.attr("action");

        handleSendRequestItemForm($form, $this, path)
    })

    /********
     *  Add Cart
     * */
    $('body').on('click', '.js-course-add-to-cart-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('form');
        const path = $form.attr("action");

        handleSendRequestItemForm($form, $this, path)
    })

    /********
     *  Points
     * */
    $('body').on('click', '.js-buy-with-point', function (e) {
        e.preventDefault();

        const path = $(this).attr("data-path");

        handleBasicModal(path, purchaseWithPointsLang, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                <a href="${result.btn_url}" class="btn btn-primary">${result.btn_text}</a>
            </div>`;
            $footer.html(footerHtml);

            refreshCaptcha();
        }, '', '40rem')
    })

    $('body').on('click', '.js-join-course-waitlist-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('.js-custom-modal').find('.js-course-waitlist-form')
        const path = $form.attr("action");

        handleSendRequestItemForm($form, $this, path)
    })

    $('body').on('click', '.js-course-direct-payment', function (e) {
        const $this = $(this);
        $this.addClass('loadingbar').prop('disabled', true);

        const $form = $this.closest('form');
        $form.attr('action', '/course/direct-payment');

        $form.trigger('submit');
    });

    $('body').on('click', '.js-bundle-direct-payment', function (e) {
        const $this = $(this);
        $this.addClass('loadingbar').prop('disabled', true);

        const $form = $this.closest('form');
        $form.attr('action', '/bundles/direct-payment');

        $form.trigger('submit');
    });

    $('body').on('click', '.js-view-more-reviews', function (e) {
        e.preventDefault();

        const $toggleTab = $('#showCourseReviewsTab');

        $('html, body').animate({
            scrollTop: $toggleTab.offset().top - 150
        }, 1000, function () {
            $toggleTab.trigger('click')
        });
    })


    /********
     *  Upcoming Course
     * */
    $('body').on('click', '.js-follow-upcoming-course', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-path');

        $this.addClass('loadingbar primary').prop('disabled', true);

        $.get(path, function (result) {
            if (result.code === 200) {
                showToast('success', result.title, result.msg)

                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
    })

})(jQuery)
