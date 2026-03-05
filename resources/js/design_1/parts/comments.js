(function ($) {
    "use strict"

    $('body').on('click', '.js-submit-comment-btn, .js-submit-comment-reply-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('form')
        const path = $form.attr('action');

        handleSendRequestItemForm($form, $this, path)
    })

    $('body').on('click', '.js-reply-comment', function (e) {
        e.preventDefault();

        const $this = $(this);
        const itemId = $this.attr("data-item");
        const itemName = $this.attr("data-item-name");
        const commentId = $this.attr("data-comment");

        const $html = $(`.js-reply-to-comment-html`).html()

        const $parent = $this.closest(".js-comment-card")
        const $replyContainer = $parent.find(".js-comment-reply-form");
        $replyContainer.html($html);

        const formPath = `/comments/${commentId}/reply`;

        const $form = $replyContainer.find('form');
        $form.attr("action", formPath);
        $form.find('input[name="item_id"]').val(itemId);
        $form.find('input[name="item_name"]').val(itemName);

        $('html, body').animate({
            scrollTop: $replyContainer.offset().top - 150
        }, 1000);
    })

    $('body').on('click', '.js-close-comment-reply-btn', function (e) {
        e.preventDefault();

        $(this).closest(".js-comment-reply-form").html("")
    })

    var commentsLoadMore = {
        page: 1,
    }

    $('body').on('click', '.js-comments-load-more-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr("data-path");

        $this.addClass("loadingbar gray").prop('disabled', true);

        const $container = $('.js-course-comments-container');

        const page = commentsLoadMore.page + 1;

        commentsLoadMore = {
            page: page
        }

        $.post(path, commentsLoadMore, function (result) {
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

    /********
     * Report
     * */

    $('body').on('click', '.js-report-comment', function (e) {
        e.preventDefault();
        const $this = $(this);
        const itemId = $this.attr("data-item");
        const itemName = $this.attr("data-item-name");
        const commentId = $this.attr("data-comment");

        const path = `/comments/get-report-modal?comment=${commentId}&item=${itemId}&type=${itemName}`;

        handleBasicModal(path, reportCommentLang, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-submit-comment-report btn btn-primary">${reportLang}</button>
            </div>`;
            $footer.html(footerHtml);


        }, '', '40rem')
    })

    $('body').on('click', '.js-submit-comment-report', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('.js-custom-modal').find('.js-comment-report-form')
        const path = $form.attr("data-action");

        handleSendRequestItemForm($form, $this, path)
    })

})(jQuery)
