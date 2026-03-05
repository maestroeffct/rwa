(function ($) {
    "use strict";

    $('body').on('click', '.js-submit-certificate-validation-form-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const modalTitle = $this.attr("data-title");

        const $form = $this.closest('form');

        const action = $form.attr('action');
        const data = $form.serializeObject();

        $this.addClass('loadingbar').prop('disabled', true);
        $form.find('input').removeClass('is-invalid');

        $.post(action, data, function (result) {
            if (result) {
                Swal.fire({
                    html: makeModalHtml(modalTitle, closeIcon, result.html, null),
                    showCancelButton: false,
                    showConfirmButton: false,
                    customClass: {
                        content: 'p-0 text-left',
                    },
                    width: '37rem',
                    didOpen: () => {

                    }
                });
            }
        }).fail(err => {
            var errors = err.responseJSON;
            if (errors && errors.errors) {
                Object.keys(errors.errors).forEach((key) => {
                    const error = errors.errors[key];
                    let element = $form.find('[name="' + key + '"]');
                    element.addClass('is-invalid');
                    element.closest('.form-group').find('.invalid-feedback').text(error[0]);
                });
            }
        }).always(() => {
            refreshCaptcha();
            $this.removeClass('loadingbar').prop('disabled', false);
        })
    })
})(jQuery)
