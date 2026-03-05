(function ($) {
    "use strict"

    $('body').on('click', '.password-input-visibility', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $eye = $this.find('.icons-eye')
        const $eyeSlash = $this.find('.icons-eye-slash')
        const $input = $this.closest('.form-group').find('input');

        const isHide = ($input.attr('type') === "password");

        if (isHide) {
            $input.attr("type", "text");
            $eye.addClass('d-none');
            $eyeSlash.removeClass('d-none')
        } else {
            $input.attr("type", "password");
            $eye.removeClass('d-none');
            $eyeSlash.addClass('d-none')
        }
    })


})(jQuery)
