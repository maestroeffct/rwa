(function ($) {
    "use strict"

    var canResendCode = "ok";

    function handleResendTimer() {
        const $timer = $('.js-resend-timer');

        if (jQuery().startTimer && $timer.length) {
            canResendCode = "wait";

            $timer.html("");

            const $resendDurationCard = $('.js-resend-duration-card');
            const $dontReceivedCard = $('.js-dont-received-card');
            const $resendBtn = $('.js-resend-code-btn');

            $resendBtn.removeClass('text-black')
                .addClass('text-gray-500');
            $resendDurationCard.addClass('d-flex').removeClass('d-none')
            $dontReceivedCard.addClass('d-none');


            $timer.startTimer({
                onComplete: function (element) {
                    $resendBtn.removeClass('text-gray-500')
                        .addClass('text-black');

                    $resendDurationCard.removeClass('d-flex').addClass('d-none')
                    $dontReceivedCard.removeClass('d-none');

                    canResendCode = "ok";
                }
            });
        }
    }

    $(document).ready(function () {
        const $countryCodeSelect2 = $('.country-code-select2');

        const dropdownParent = $countryCodeSelect2.attr('data-dropdown-parent') ?? 'body'

        $countryCodeSelect2.select2({
            dropdownCssClass: "country-code-select2-dropdown",
            dropdownParent: $(dropdownParent),
        })

        handleResendTimer()
    })

    $('body').on('change', 'input[name="type"]', function () {
        const val = $(this).val();
        const $email = $('.js-email-fields');
        const $mobile = $('.js-mobile-fields');

        $mobile.addClass('d-none')
        $email.addClass('d-none');

        if (val === "email") {
            $email.removeClass('d-none');
        } else {
            $mobile.removeClass('d-none')
        }
    })

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

    /*==================
    * Verification
    * **************/

    $('body').on('input', '.auth-verification-code-field', function (e) {
        const $this = $(this);
        const value = $this.val();

        if (value.length > 1) {
            $this.val(value.charAt(0)); // Trim to a single digit
        }

        if (value.length === 1) {
            $this.next('.auth-verification-code-field').focus();
        }
    })

    $('body').on('keydown', '.auth-verification-code-field', function (e) {
        const $this = $(this);

        if (e.key === "Backspace" && $this.val() === '') {
            $this.prev('.auth-verification-code-field').focus();
        }
    })


    $('body').on('click', '.js-resend-code-btn', function (e) {
        e.preventDefault();
        const $this = $(this);

        if (canResendCode === "ok") {
            const $submitBtn = $('.js-submit-verification-form-btn');
            $submitBtn.addClass('loadingbar').prop('disabled', true);
            $this.prop('disabled', true);

            const path = "/verification/resend";

            $.get(path, function (result) {
                if (result.code === 200) {
                    showToast('success', result.title, result.msg)

                    $('.auth-verification-code-field').val('');

                    handleResendTimer()
                }
            }).fail(err => {
                showToast('error', oopsLang, somethingWentWrongLang);
            }).always(function () {
                $submitBtn.removeClass('loadingbar').prop('disabled', false);
                $this.prop('disabled', false);
            })

        } else {
            showToast("error", waitLang, pleaseWaitUntilTimeOverLang)
        }
    })

    $('body').on('click', '.js-submit-verification-form-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('form');

        $this.addClass('loadingbar')
        $form.trigger('submit')
    })

    /*==================
    * Register
    * **************/

    $('body').on('change', 'input[name="account_type"]', function () {
        const value = $(this).val();

        $.post('/register/form-fields', {type: value}, function (result) {
            if (result) {
                $('.js-form-fields-card').html(result.html);

                formsDatetimepicker();
            }
        })
    })

})(jQuery)
