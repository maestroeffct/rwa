(function ($) {
    "use strict";

    $('body').on('click', '.js-reply-comment', function (e) {
        e.preventDefault();
        var $this = $(this);
        var comment_id = $this.attr('data-comment-id');
        var comment = $('#commentDescription' + comment_id).val();

        var html = '<div class="p-16">\n' +
            '        <h3 class="section-title after-line">' + replyToCommentLang + '</h3>\n' +
            '        <div class="rounded-8 p-16 border-gray-200 mt-16 text-gray-500">' + comment + '</div>\n' +
            '        <form id="commentForm" action="/panel/courses/comments/' + comment_id + '/reply" method="post" class="mt-20">\n' +
            '            <div class="form-group">\n' +
            '                <label class="form-group-label bg-white">' + replyToCommentLang + '</label>\n' +
            '                <textarea name="comment" rows="6" class="form-control bg-white"></textarea>\n' +
            '            </div>\n' +
            '\n' +
            '            <div class="mt-24 d-flex align-items-center justify-content-end">\n' +
            '                <button type="button" class="btn btn-primary js-save-form">' + saveLang + '</button>\n' +
            '                <button type="button" class="btn btn-danger ml-8 close-swl">' + closeLang + '</button>\n' +
            '            </div>\n' +
            '        </form>\n' +
            '    </div>';

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '40rem',
        });
    });

    $('body').on('click', '.js-save-form', function (e) {
        const $this = $(this);
        $this.addClass('loadingbar primary').prop('disabled', true);

        const $form = $(this).closest('form');
        const action = $form.attr('action');
        const data = $form.serializeObject();

        $.post(action, data, function (result) {
            if (result && result.code === 200) {
                Swal.fire({
                    icon: 'success',
                    html: '<h3 class="font-20 text-center text-dark">' + result.msg + '</h3>',
                    showConfirmButton: true,
                });

                setTimeout(() => {
                    window.location.reload();
                }, 500)
            } else if (result && result.code === 401) {
                Swal.fire({
                    icon: 'error',
                    html: '<h3 class="font-20 text-center text-dark">' + failedLang + '</h3>',
                    showConfirmButton: false,
                });
            }
        }).fail(err => {
            $this.removeClass('loadingbar primary').prop('disabled', false);
            var errors = err.responseJSON;
            if (errors && errors.errors) {
                Object.keys(errors.errors).forEach((key) => {
                    const error = errors.errors[key];
                    let element = $form.find('[name="' + key + '"]');
                    element.addClass('is-invalid');
                    element.parent().find('.invalid-feedback').text(error[0]);
                });
            }
        })
    });

    $('body').on('click', '.js-view-comment', function (e) {
        e.preventDefault();
        var $this = $(this);
        var comment_id = $this.attr('data-comment-id');
        var comment = $('#commentDescription' + comment_id).val();


        var html = `<div class="p-16">
            <div class="section-title d-flex align-items-center">
                <h3 class="section-title__heading">${commentLang}</h3>
            </div>

            <p class="text-gray-500 mt-20">${comment}</p>
        </div>`;

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '40rem',
        });


    });

    $('body').on('click', '.js-edit-comment', function (e) {
        e.preventDefault();
        var $this = $(this);
        var comment_id = $this.attr('data-comment-id');
        var description = $('#commentDescription' + comment_id).val();


        var html = '<div class="p-16">\n' +
            '        <h3 class="section-title after-line">' + editCommentLang + '</h3>\n' +
            '        <form action="/panel/courses/comments/' + comment_id + '/update" method="post" class="mt-20">\n' +
            '            <input type="hidden" name="_token" value="' + csrfToken + '">\n' +
            '            <div class="form-group">\n' +
            '                <label class="form-group-label bg-white">' + replyToCommentLang + '</label>\n' +
            '                <textarea name="comment" rows="6" class="form-control bg-white">' + description + '</textarea>\n' +
            '            </div>\n' +
            '\n' +
            '            <div class="mt-24 d-flex align-items-center justify-content-end">\n' +
            '                <button type="button" class="btn btn-primary js-save-form">' + saveLang + '</button>\n' +
            '                <button type="button" class="btn btn-danger ml-8 close-swl">' + closeLang + '</button>\n' +
            '            </div>\n' +
            '        </form>\n' +
            '    </div>';

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '40rem',
        });

    });

    $('body').on('click', '.js-report-comment', function (e) {
        e.preventDefault();
        const comment_id = $(this).attr('data-comment-id');

        const html = '<div id="reportModal" class="p-16">\n' +
            '    <h3 class="section-title after-line font-20 text-dark mb-24">' + reportLang + '</h3>\n' +
            '\n' +
            '    <form action="/panel/courses/comments/' + comment_id + '/report" method="post">\n' +
            '        <div class="form-group">\n' +
            '            <label class="form-group-label bg-white">' + messageToReviewerLang + '</label>\n' +
            '            <textarea name="message" class="form-control bg-white" rows="6"></textarea>\n' +
            '            <div class="invalid-feedback"></div>\n' +
            '        </div>\n' +
            '\n' +
            '        <div class="mt-24 d-flex align-items-center justify-content-end">\n' +
            '            <button type="button" id="saveReport" class="btn btn-primary">' + saveLang + '</button>\n' +
            '            <button type="button" class="btn btn-danger ml-8 close-swl">' + closeLang + '</button>\n' +
            '        </div>\n' +
            '    </form>\n' +
            '</div>';

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '30rem',
        });
    });

    $('body').on('click', '#saveReport', function (e) {
        const $this = $(this);
        let form = $('#reportModal form');
        let data = form.serializeObject();
        let action = form.attr('action');

        $this.addClass('loadingbar primary').prop('disabled', true);
        form.find('input').removeClass('is-invalid');
        form.find('textarea').removeClass('is-invalid');

        $.post(action, data, function (result) {
            if (result && result.code === 200) {
                Swal.fire({
                    icon: 'success',
                    html: '<h3 class="font-20 text-center text-dark">' + reportSuccessLang + '</h3>',
                    showConfirmButton: false,
                });

                setTimeout(() => {
                    window.location.reload();
                }, 2000)
            }
        }).fail(err => {
            $this.removeClass('loadingbar primary').prop('disabled', false);
            var errors = err.responseJSON;
            if (errors && errors.errors) {
                Object.keys(errors.errors).forEach((key) => {
                    const error = errors.errors[key];
                    let element = form.find('[name="' + key + '"]');
                    element.addClass('is-invalid');
                    element.parent().find('.invalid-feedback').text(error[0]);
                });
            }
        })
    });

    $('body').on('change', '#newCommentsSwitch', function (e) {
        e.preventDefault();

        $(this).closest('form').trigger('submit');
    })


})(jQuery);
