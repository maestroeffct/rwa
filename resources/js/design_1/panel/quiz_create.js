(function ($) {
    "use strict";

    // *******************
    // create
    // *****************


    $('body').on('click', '.add-answer-btn', function (e) {
        e.preventDefault();
        var mainRow = $('.add-answer-container .main-answer-box');

        var copy = mainRow.clone();
        copy.removeClass('main-answer-box');
        copy.find('.answer-remove').removeClass('d-none');

        const id = 'correctAnswerSwitch' + randomString();
        copy.find('label.js-switch').attr('for', id);
        copy.find('input.js-switch').attr('id', id);

        copy.find('input[type="checkbox"]').prop('checked', false);

        var copyHtml = copy.prop('innerHTML');
        const nameId = randomString();

        copyHtml = copyHtml.replaceAll('ans_tmp', nameId);
        copyHtml = copyHtml.replace(/\[\d+\]/g, '[' + nameId + ']');
        copy.html(copyHtml);
        copy.find('input[type="checkbox"]').prop('checked', false);
        copy.find('input[type="text"]').val('');
        mainRow.parent().append(copy);
    });

    $('body').on('click', '.answer-remove', function (e) {
        e.preventDefault();
        $(this).closest('.add-answer-card').remove();
    });

    function randomString() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    $('body').on('change', '.js-switch', function () {
        const $this = $(this);
        const parent = $this.closest('.js-switch-parent');

        if (this.checked) {
            $('.js-switch').each(function () {
                const switcher = $(this);
                const switcher_parent = switcher.closest('.js-switch-parent');
                const switcher_input = switcher_parent.find('input[type="checkbox"]');
                switcher_input.prop('checked', false);
            });

            $this.prop('checked', true);
        }
    });

    $('body').on('click', '.save-question', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.js-custom-modal').find('.quiz-questions-form');

        handleSendRequestItemForm(form, $this)
    });

    $('body').on('click', '.js-create-new-question', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-path');

        handleBasicModal(path, newQuestionLang, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end mt-25">
                <button type="button" class="save-question btn btn-sm btn-primary">${saveLang}</button>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-8">${closeLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '64rem')

    });

    // *******************
    // edit
    // *****************

    $('body').on('click', '.edit_question', function (e) {
        e.preventDefault();
        const $this = $(this);
        const question_id = $this.attr('data-question-id');
        const path = '/panel/quizzes-questions/' + question_id + '/edit';

        handleBasicModal(path, editQuestionLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-end mt-25">
                <button type="button" class="save-question btn btn-sm btn-primary">${saveLang}</button>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-8">${closeLang}</button>
            </div>`;
            $footer.html(footerHtml);

            $body.find('.main-answer-row').removeClass('main-answer-row').addClass('main-answer-box');

            const random_id = randomString();
            $body.find('.panel-file-manager').first().attr('data-input', random_id);
            $body.find('.lfm-input').first().attr('id', random_id);

            const id = 'correctAnswerSwitch' + randomString();
            $body.find('label.js-switch').first().attr('for', id);
            $body.find('input.js-switch').first().attr('id', id);

        }, '', '64rem')

    });

    $('body').on('click', '.js-submit-quiz-form', function (e) {
        e.preventDefault();
        const $this = $(this);

        let form = $this.closest('.quiz-form');
        let action = form.attr('data-action');

        handleSendRequestItemForm(form, $this, action)
    });

    $('body').on('click', '.js-submit-quiz-form-main-page', function (e) {
        e.preventDefault();
        const $this = $(this);

        const $parent = $this.closest('.js-quiz-main-page-form');
        let form = $parent.find('.quiz-form');
        let action = form.attr('data-action');

        handleSendRequestItemForm(form, $this, action)
    });

    $('body').on('change', '.js-ajax-webinar_id', function (e) {
        const $this = $(this);
        const webinarId = $this.val();

        const path = '/panel/chapters/getAllByWebinarId/' + webinarId;

        var $chapterSelection = $('.js-ajax-chapter_id');

        $chapterSelection.addClass('loadingbar');

        $.get(path, function (result) {
            if (result && result.chapters) {
                var html = '';

                Object.keys(result.chapters).forEach(key => {
                    const chapter = result.chapters[key];

                    html += '<option value="' + chapter.id + '">' + chapter.title + '</option>';
                });

                $chapterSelection.removeClass('loadingbar gray');

                $chapterSelection.html(html);
            }
        });
    });

    $('body').on('change', '.js-ajax-display_limited_questions', function () {
        const $input = $('.js-display-limited-questions-count-field');

        $input.find('input').val('');

        if (this.checked) {
            $input.removeClass('d-none');
        } else {
            $input.addClass('d-none');
        }
    })

})(jQuery);
