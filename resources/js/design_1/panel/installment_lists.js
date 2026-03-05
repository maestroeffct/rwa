(function ($) {
    "use strict"

    $(document).ready(function () {

        const items = $('.js-installment-chart');

        if (items.length) {
            for (const item of items) {
                const $item = $(item);

                const id = $item.attr("data-id");
                const percent = Number($item.attr("data-percent"));
                const ctx = document.getElementById(`${id}`).getContext('2d');

                makeCircleChart(ctx, [percent, (100 - percent)], null, null, false)
            }
        }

    })


})(jQuery)
