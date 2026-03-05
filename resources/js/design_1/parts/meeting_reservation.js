(function ($) {
    "use strict";
    var studentCountRangeRunning = false;

    $('body').on('change', 'input[name="meeting_type"]', function () {
        const $meetingPopulation = $('.js-meeting-population-card');
        const $jsOnlineGroupAmount = $('.js-online-group-amount-hints');
        const $jsInPersonGroupAmount = $('.js-in-person-group-amount-hints');

        $('input[name="student_count"]').val(1);
        $('.wrunner').remove();

        if (studentCountRangeRunning) {
            handleStudentCountRange();
        }


        $meetingPopulation.addClass('d-none');
        $jsOnlineGroupAmount.addClass('d-none');
        $jsInPersonGroupAmount.addClass('d-none');

        const $this = $(this);
        const value = $this.val();

        if (this.checked) {
            $meetingPopulation.removeClass('d-none');

            if (value === 'in_person') {
                $jsInPersonGroupAmount.removeClass('d-none');
            }

            if (value === 'online') {
                $jsOnlineGroupAmount.removeClass('d-none');
            }

            getMeetingAmount();
        }
    })


    $('body').on('change', 'input[name="meeting_population"]', function () {
        const $participants = $('.js-group-participants-card');

        $participants.addClass('d-none')

        $('input[name="student_count"]').val(1);
        $('.wrunner').remove();

        if ($(this).val() === "online") {
            $participants.removeClass('d-none')

            handleStudentCountRange();
        }

        getMeetingAmount();
    })


    function handleStudentCountRange() {
        var $studentCountRange = $('#studentCountRange');

        if ($studentCountRange && jQuery().wRunner) {
            const meeting_type = $('input[name="meeting_type"]:checked').val();

            if (meeting_type) {
                const minLimit = $studentCountRange.attr('data-minLimit');
                const maxLimit = meeting_type === 'in_person' ? $('#in_person_group_max_student').val() : $('#online_group_max_student').val();

                const $studentCountInput = $studentCountRange.find('input[name="student_count"]');


                var wtime = $studentCountRange.wRunner({
                    type: 'single',
                    limits: {
                        minLimit,
                        maxLimit,
                    },
                    singleValue: minLimit,
                    step: 1,
                });

                wtime.onValueUpdate(function (res) {
                    $studentCountInput.val(res.value);

                    getMeetingAmount();
                });

                studentCountRangeRunning = true;
            }
        }
    }

    var getMeetingAmountRequest;

    function getMeetingAmount() {
        const $loading = `<img src="/assets/default/img/loading.svg" width="36px" height="36px">`;
        const $amountDiv = $('.js-meeting-amount');
        const $form = $('.js-meeting-book-form');

        if ($form.length) {
            $amountDiv.html($loading);

            const path = $form.attr('data-amount-path')
            const data = $form.serializeObject();

            if (getMeetingAmountRequest) {
                clearTimeout(getMeetingAmountRequest)
            }

            getMeetingAmountRequest = setTimeout(function () {
                $.post(path, data, function (result) {
                    if (result.code === 200) {
                        $amountDiv.html(result.amount);
                    }
                }).fail(function (err) {
                    $amountDiv.html('');
                    var errors = err.responseJSON;

                    // toast
                    if (errors.toast_alert) {
                        showToast('error', errors.toast_alert.title, errors.toast_alert.msg)
                    } else {
                        showToast('error', oopsLang, somethingWentWrongLang);
                    }

                })
            }, 500)
        }
    }

    $(document).ready(function () {
        getMeetingAmount();
    })

    $('body').on('click', '.js-checkout-meeting', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-meeting-book-form');
        const path = $form.attr('action')

        handleSendRequestItemForm($form, $this, path)
    })
})(jQuery)
