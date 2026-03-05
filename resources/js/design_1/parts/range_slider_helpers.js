(function ($) {
    "use strict"

    window.handleDoubleRange = function ($el, item, callback) {
        if ($el && $el.length) {
            const minLimit = $el.attr('data-minLimit');
            const maxLimit = $el.attr('data-maxLimit');
            const step = $el.attr('data-step') ?? 1;

            const minTimeEl = $el.find('input[name="min_' + item + '"]');
            const maxTimeEl = $el.find('input[name="max_' + item + '"]');

            let minValue = minTimeEl.val();
            let maxValue = maxTimeEl.val();

            if (!minValue) {
                minValue = Number(minLimit)
            }

            if (!maxValue) {
                maxValue = Number(maxLimit)
            }

            const range = $el.wRunner({
                type: 'range',
                limits: {
                    minLimit,
                    maxLimit,
                },
                rangeValue: {
                    minValue: minValue,
                    maxValue: maxValue,
                },
                step: Number(step),
            });

            callback(range, minTimeEl, maxTimeEl)
        }
    }

    var triggerTimeout;

    window.handleTriggerInputForAjax = function ($el) {
        if (triggerTimeout) {
            clearTimeout(triggerTimeout)
        }

        triggerTimeout = setTimeout(function () {
            $el.trigger('change');
        }, 500)
    }

})(jQuery)
