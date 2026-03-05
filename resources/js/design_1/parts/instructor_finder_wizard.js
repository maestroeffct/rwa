(function ($) {
    "use strict"

    $('body').on('change', 'input[name="meeting_type"]', function () {

        const regionCard = $('#regionCard');

        if ($(this).val() === 'online') {
            regionCard.addClass('d-none');
        } else {
            regionCard.removeClass('d-none');
        }
    });

    $('body').on('change', 'input[name="flexible_date"]', function () {
        if (this.checked) {
            $('#dateTimeCard').addClass('d-none');
        } else {
            $('#dateTimeCard').removeClass('d-none');
        }
    });

    $('body').on('click', '.js-next-btn', function (e) {
        e.preventDefault();
        $(this).addClass('loadingbar').prop('disabled', true);

        $('#instructorFinderWizardForm').trigger('submit');
    });

    $('body').on('click', '.js-previous-btn', function (e) {
        e.preventDefault();
        $(this).addClass('loadingbar').prop('disabled', true);

        const $form = $('#instructorFinderWizardForm');
        const $stepInput = $form.find('input[name="step"]');

        const nextStep = $stepInput.val();
        $stepInput.val(nextStep - 2);

        $form.trigger('submit');
    });

    $(document).ready(function () {

        var $timeRange = $('#timeRange');

        if ($timeRange && $timeRange.length && jQuery().wRunner) {
            const minLimit = $timeRange.attr('data-minLimit');
            const maxLimit = $timeRange.attr('data-maxLimit');

            const minTimeEl = $timeRange.find('input[name="min_time"]');
            const maxTimeEl = $timeRange.find('input[name="max_time"]');

            const minValue = minTimeEl.val();
            const maxValue = maxTimeEl.val();

            var wtime = $timeRange.wRunner({
                type: 'range',
                limits: {
                    minLimit,
                    maxLimit,
                },
                rangeValue: {
                    minValue,
                    maxValue,
                },
                step: 1,
            });

            wtime.onValueUpdate(function (values) {
                minTimeEl.val(values.minValue);
                maxTimeEl.val(values.maxValue);
            });
        }
    });


})(jQuery)
