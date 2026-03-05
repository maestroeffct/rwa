(function ($) {
    "use strict";


    window.makePieChart = function (elId, labels, percent) {
        let bodyEl = document.getElementById(elId).getContext('2d');

        const notComplete = 100 - percent;
        const colors = percent === 100 ? [primaryColor, '#f7f7f7'] : [warningColor, '#f7f7f7'];

        makeCircleChart(bodyEl, [percent, notComplete], labels, colors);
    };
})(jQuery);
