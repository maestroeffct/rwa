(function ($) {
    "use strict"

    $('.barrating-stars select').each(function (index, element) {
        var $element = $(element);
        $element.barrating({
            theme: 'css-stars',
            readonly: false,
            initialRating: $element.data('rate'),
        });
    });

    $('body').on('click', '.js-submit-review-btn, .js-submit-review-reply-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('form')
        const path = $form.attr('action');

        handleSendRequestItemForm($form, $this, path)
    })

    $('body').on('click', '.js-reply-review', function (e) {
        e.preventDefault();

        const $this = $(this);
        const reviewId = $this.attr("data-review");

        const $html = $(`.js-reply-to-review-html`).html()

        const $parent = $this.closest(".js-all-reviews-card")
        const $replyContainer = $parent.find(".js-review-reply-form");
        $replyContainer.html($html);

        $replyContainer.find('input[name="review_id"]').val(reviewId)

        $('html, body').animate({
            scrollTop: $replyContainer.offset().top - 150
        }, 1000);
    })

    $('body').on('click', '.js-close-review-reply-btn', function (e) {
        e.preventDefault();

        $(this).closest(".js-review-reply-form").html("")
    })

    var reviewsLoadMore = {
        page: 1,
    }

    $('body').on('click', '.js-review-load-more-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr("data-path");

        $this.addClass("loadingbar gray").prop('disabled', true);

        const $container = $('.js-course-reviews-container');

        const page = reviewsLoadMore.page + 1;

        reviewsLoadMore = {
            page: page
        }

        $.post(path, reviewsLoadMore, function (result) {
            if (result.code === 200) {
                $container.append(result.html);

                if (!result.has_more) {
                    $this.parent().remove()
                }
            }
        }).always(function () {
            $this.removeClass("loadingbar gray").prop('disabled', false);
        })
    })


})(jQuery)
