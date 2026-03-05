(function ($) {
    "use strict"


    $('body').on('click', '.login-to-access', function (e) {
        e.preventDefault();

        if (notLoginToastTitleLang && notLoginToastMsgLang) {
            showToast('error', notLoginToastTitleLang, notLoginToastMsgLang)
        }
    });

    $('body').on('click', '.js-topic-bookmark', function (e) {
        e.preventDefault();
        const $this = $(this);
        const action = $this.attr('data-action');

        if ($this.hasClass('text-primary')) {
            $this.removeClass('text-primary').addClass('text-gray-500')
        } else {
            $this.removeClass('text-gray-500').addClass('text-primary')
        }

        $.post(action, {}, function (result) {
            if (result && result.code === 200) {
                showToast('success', result.title, result.msg)
            }
        }).fail(err => {
            showToast('error', oopsLang, somethingWentWrongLang)
        });
    });

    /* Like */
    $('body').on('click', '.js-topic-post-like', function (e) {
        e.preventDefault();

        const $this = $(this);
        const parent = $this.closest('.js-topic-post-like-btn-parent');
        let likeCount = parent.find('.js-like-count').text();
        const action = $this.attr('data-action');
        const isLiked = $this.hasClass('liked');

        const $emptyIcon = $this.find('.js-empty-like-icon');
        const $fullIcon = $this.find('.js-full-like-icon');

        if (isLiked) {
            $emptyIcon.removeClass('d-none');
            $fullIcon.addClass('d-none');
        } else {
            $emptyIcon.addClass('d-none');
            $fullIcon.removeClass('d-none');
        }

        let newLikeCount = (isLiked) ? likeCount - 1 : Number(likeCount) + 1;

        parent.find('.js-like-count').text(newLikeCount);

        $.post(action, {}, function (result) {
            if (result && result.code === 200) {
                parent.find('.js-like-count').text(result.likes);

                if (result.status) {
                    $this.addClass('liked');
                } else {
                    $this.removeClass('liked');
                }
            }
        }).fail(err => {

            parent.find('.js-like-count').text(likeCount);
        });
    });

    /**
     * report modal
     * */
    $('body').on('click', '.js-topic-post-report', function (e) {
        e.preventDefault();
        const $this = $(this);
        const itemId = $this.attr('data-id');
        const type = $this.attr('data-type');
        const path = $this.attr("data-path") + `?type=${type}&item=${itemId}`;

        handleBasicModal(path, reportLang, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-submit-report-btn btn btn-primary">${reportLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '40rem')
    });

    $('body').on('click', '.js-submit-report-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('.js-custom-modal').find('.js-topic-report-form')
        const path = $form.attr("data-action");

        handleSendRequestItemForm($form, $this, path)
    });


    /**
     * Pin & Unpin
     * */
    $('body').on('click', '.js-btn-post-un-pin, .js-btn-post-pin', function (e) {
        e.preventDefault();
        const $this = $(this);
        const action = $this.attr('data-action');

        if ($this.hasClass('text-warning')) {
            $this.removeClass('text-warning').addClass('text-gray-500')
        } else {
            $this.removeClass('text-gray-500').addClass('text-warning')
        }

        $.post(action, {}, function (result) {
            if (result.code === 200) {
                showToast('success', result.title, result.msg)
            }
        }).fail(err => {
            showToast('error', oopsLang, somethingWentWrongLang)
        });
    });

    /**
     * Save & Edit Post
     * */
    $('body').on('click', '.js-save-post-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('form')
        const path = $form.attr("action");

        handleSendRequestItemForm($form, $this, path, null, false)
    })

    $('body').on('click', '.js-post-edit', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr("data-action");

        handleBasicModal(path, editPostLang, function (result, $body, $footer) {

            // Make Editor
            makeSummernote($body.find('.js-edit-post-summernote'), 350)

            const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-submit-edit-post-btn btn btn-primary">${saveLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '50rem')
    });

    $('body').on('click', '.js-submit-edit-post-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $modal = $this.closest('.js-custom-modal');
        const $form = $modal.find('form')
        const path = $form.attr("action");

        handleSendRequestItemForm($form, $this, path, null, false)
    })

    /**
     * Replay Post
     * */
    $('body').on('click', '.js-reply-post-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const postId = $this.attr('data-id');
        const $card = $this.closest('.topic-post-card');

        const userAvatarPath = $card.find('.js-user-avatar').attr('src');
        const userFullName = $card.find('.js-user-name').text();
        let description = '';
        $card.find('.js-topic-post-description').contents().filter(function () {
            description += this.innerText;
        });

        const shortText = jQuery.trim(description).substring(0, 125)
            .split(" ").slice(0, -1).join(" ") + "...";

        const $replyCard = $('.js-topic-post-reply-card');
        $replyCard.removeClass('d-none');
        $replyCard.find('.js-reply-post-id').val(postId);
        $replyCard.find('.js-reply-post-user-avatar').attr('src', userAvatarPath);
        $replyCard.find('.js-reply-post-user-name b').text(userFullName);
        $replyCard.find('.js-reply-post-description').text(shortText);

        $('html, body').animate({
            scrollTop: $replyCard.offset().top - 100
        }, 500);
    })

    $('body').on('click', '.js-close-reply-post', function (e) {
        e.preventDefault();

        const $replyCard = $(this).closest('.js-topic-post-reply-card');

        $replyCard.addClass('d-none');
        $replyCard.find('.js-reply-post-id').val('');
    });

})(jQuery)

