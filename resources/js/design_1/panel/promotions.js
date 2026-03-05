(function ($) {
    "use strict";

    $('body').on('click', '.js-pay-promotion-modal', function (e) {
        e.preventDefault();
        const $this = $(this);
        const id = $this.attr("data-promotion-id");
        const path = `/panel/marketing/promotions/${id}/pay-form`;

        handleBasicModal(path, promotionLang, function (result, $body, $footer) {

            handleSelect2($body.find('.custom-select2'))

            const footerHtml = `<div class="d-flex align-items-center justify-content-end mt-25">
                <button type="button" class="close-swl btn btn-transparent">${cancelLang}</button>
                <button type="button" class="js-proceed-to-payment-promotion btn btn-primary ml-24">${proceedToPaymentLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '44rem')
    });

    $('body').on('click', '.js-proceed-to-payment-promotion', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('.js-custom-modal').find('.js-promotion-plan-pay-form');
        const $course = $form.find('.js-ajax-course_id');
        const courseId = $course.val();
        $course.removeClass('is-invalid');

        if (courseId) {
            $form.trigger('submit')
        } else {
            $course.addClass('is-invalid');
        }
    });

})(jQuery);
