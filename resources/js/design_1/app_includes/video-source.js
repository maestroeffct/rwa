(function ($) {
    "use strict"

    /* Video Source */
    $('body').on('change', '.js-upload-source-input', function () {
        const value = $(this).val();
        const parent = $(this).closest('.js-inputs-with-source');

        if ($.inArray(value, ['youtube', 'vimeo', 'external', 'iframe']) !== -1) {
            parent.find('.js-online-upload').removeClass('d-none');
            parent.find('.js-local-upload').addClass('d-none');
        } else {
            parent.find('.js-online-upload').addClass('d-none');
            parent.find('.js-local-upload').removeClass('d-none');
        }
    })

})(jQuery)
