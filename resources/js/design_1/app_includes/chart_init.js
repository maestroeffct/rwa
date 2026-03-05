(function ($) {
    "use strict";

    /**
     * @param ctx => like = const ctx = document.getElementById(`${id}`).getContext('2d');
     * @param labels
     * @param datasets
     * @param colors
     * @param showPercentInMiddle
     *
     * */
    window.makeCircleChart = function (ctx, datasets, labels = null, colors = null, showPercentInMiddle = true) {
        if (!colors) {
            colors = [primaryColor, '#f7f7f7'];
        }

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: '',
                    data: datasets,
                    borderWidth: 0,
                    borderColor: '',
                    backgroundColor: colors,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6777ef',
                    pointRadius: 2,
                    fill: true,
                }]
            },
            plugins: [{
                beforeDraw: function (chart) {
                    if (showPercentInMiddle) {
                        const width = chart.chart.width,
                            height = chart.chart.height,
                            ctx = chart.chart.ctx;

                        ctx.restore();
                        ctx.font = "1em main-font-family";
                        ctx.fillStyle = "#9b9b9b";
                        ctx.textBaseline = "middle";

                        const text = datasets[0] + "%",
                            textX = Math.round((width - ctx.measureText(text).width) / 2),
                            textY = height / 2;

                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    }
                }
            }],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                segmentShowStroke: false,
                legend: {
                    display: false
                },
                cutoutPercentage: 85,
                rotation: Math.PI / 2,
            }
        });
    }

})(jQuery)
