(function ($) {
    "use strict";

    $(document).ready(function () {
        if (jQuery().summernote) {
            makeSummernote($('.summernote'))
        }
    })

    $('body').on('click', '.js-view-message', function (e) {
        const $this = $(this);

        const card = $this.closest('.noticeboard-item');
        const title = card.find('.js-noticeboard-title').text();
        const time = card.find('.js-noticeboard-time').text();
        const message = card.find('.js-noticeboard-message').val();

        const modal = $('#noticeboardMessageModal');
        modal.find('.modal-title').text(title);
        modal.find('.modal-time').text(time);
        modal.find('.modal-message').html(message);

        Swal.fire({
            html: modal.html(),
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '30rem',
        });
    });

    $('body').on('click', '.js-submit-noticeboard-form', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('form');
        const action = $form.attr('action');

        handleSendRequestItemForm($form, $this, action);
    });


    $('body').on('change', '.js-noticeboard-type-select', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const instructorCourses = $('.js-instructor-courses-fields');
        instructorCourses.addClass('d-none');

        if (value === 'course') {
            instructorCourses.removeClass('d-none');
        }
    });

})(jQuery);
