(function ($) {
    "use strict";

    function checkPackageHasInstallment(id) {
        const path = '/become-instructor/packages/' + id + '/checkHasInstallment';
        const $btn = $('.js-installment-btn');
        $btn.addClass('d-none');

        $.get(path, function (result) {
            if (result && result.has_installment) {
                $btn.removeClass('d-none');
                $btn.attr('href', '/become-instructor/packages/' + id + '/installments');
            }
        });
    }



    $('body').on('change', '.js-registration-package-input', function (e) {
        e.preventDefault();

        $('button#paymentSubmit').removeAttr('disabled');

        const packageId = $(this).val();

        checkPackageHasInstallment(packageId);
    });


})(jQuery);
