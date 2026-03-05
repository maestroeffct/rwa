(function ($) {
    "use strict";

    $('body').on('change', '#gateway_offline', function (e) {
        e.preventDefault();

        if (this.checked) {
            $('.js-offline-payment-input').removeClass('d-none');
        }
    });

    $('body').on('change', '.online-gateway', function (e) {
        e.preventDefault();

        if (this.checked) {
            $('.js-offline-payment-input').addClass('d-none');
        }
    });

    $('body').on('click', '#submitChargeAccountForm', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('form');

        $this.addClass('loadingbar primary').prop('disabled', true);

        $form.trigger('submit');
    });

    $('body').on('change','#attachmentFile',function () {
        var file = $('#attachmentFile')[0].files[0].name;
        $('#attachmentFileLabel .custom-upload-input').text(file);
    });
})(jQuery);
