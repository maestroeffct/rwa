(function ($) {
    "use strict";

    $(document).ready(function () {

        const items = $('.js-pending-certificate-chart');

        if (items.length) {
            for (const item of items) {
                const $item = $(item);

                const id = $item.attr("data-id");
                const percent = Number($item.attr("data-percent"));
                const colors = percent === 100 ? [primaryColor, '#f7f7f7'] : [warningColor, '#f7f7f7'];
                const ctx = document.getElementById(`${id}`).getContext('2d');

                makeCircleChart(ctx, [percent, (100 - percent)], null, colors)
            }
        }

    })


    $('body').on('change', 'select[name="webinar_id"]', function (e) {
        e.preventDefault();

        const quizFilter = $('#quizFilter');
        $('#quizFilter option').each((key, element) => {
            if ($(element).val() !== 'all') {
                $(element).addClass('d-none');

                quizFilter.prop('disabled', true);

                if ($(element).attr('data-webinar-id') === $(this).val()) {
                    $(element).removeClass('d-none');
                    quizFilter.prop('disabled', false);
                }
            }
        })
    })
})(jQuery);
