(function ($) {
    "use strict";


    $(document).ready(function () {

        const $pieCharts = $('.js-pie-chart')

        if ($pieCharts.length > 0) {
            for (const pieChart of $pieCharts) {
                const $pieChart = $(pieChart);

                const series = JSON.parse($pieChart.attr('data-series'));
                const labels = JSON.parse($pieChart.attr('data-labels'));
                const elId = $pieChart.attr('data-id');
                const pieChartEl = document.querySelector("#" + elId);

                handleApexPieCharts(pieChartEl, series, labels)
            }
        }

        // learningActivityChart
        handleActivityChart()

        // Students Progress Chart
        handleStudentsProgressChart()

        // Monthly Sales Chart
        handleMonthlySalesChart()

        // Visitors Chart
        handleVisitorsChart()
    })

    function handleActivityChart() {
        const $el = $('.js-learning-activity-chart');

        const seriesData = learningActivityChartSeriesData;
        const labels = learningActivityChartLabels;
        const seriesName = $el.attr('data-series-name');
        const elId = $el.attr('data-id');
        const prefixLang = $el.attr('data-prefix');
        const areaChartEl = document.querySelector("#" + elId);

        const series = [
            {name: seriesName, data: seriesData},
        ]

        const colors = [
            "rgba(22, 93, 255, 0.3)",
        ];

        handleApexAreaCharts(areaChartEl, series, colors, labels, 354, ` ${prefixLang}`, "after")
    }

    function handleMonthlySalesChart() {
        const $el = $('.js-monthly-sales-chart');

        const seriesData = monthlySalesChartSeriesData;
        const labels = monthlySalesChartLabels;
        const seriesName = $el.attr('data-series-name');
        const elId = $el.attr('data-id');
        const prefixLang = $el.attr('data-prefix');
        const areaChartEl = document.querySelector("#" + elId);

        const series = [
            {name: seriesName, data: seriesData},
        ]

        const colors = [
            "rgba(22, 93, 255, 0.3)",
        ];

        handleApexAreaCharts(areaChartEl, series, colors, labels, 354, jsCurrentCurrency, "before")
    }

    function handleStudentsProgressChart() {
        const $el = $('.js-students-progress-chart');

        const seriesData = studentsProgressChartSeriesData;
        const labels = studentsProgressChartLabels;
        const seriesName = $el.attr('data-series-name');
        const elId = $el.attr('data-id');

        const chartEl = document.querySelector("#" + elId);

        const series = [
            {name: seriesName, data: seriesData},
        ]

        const colors = [
            "rgba(22, 93, 255, 0.3)",
        ];

        handleApexBarCharts(chartEl, series, colors, labels, 354)
    }

    function handleVisitorsChart() {
        const $el = $('.js-visitors-chart');

        const seriesData = visitorsChartSeriesData;
        const labels = visitorsChartLabels;
        const seriesName = $el.attr('data-series-name');
        const elId = $el.attr('data-id');

        const chartEl = document.querySelector("#" + elId);

        const series = [
            {name: seriesName, data: seriesData},
        ]

        const colors = [
            "rgba(22, 93, 255, 0.3)",
        ];

        handleApexBarCharts(chartEl, series, colors, labels, 354)
    }

    function handleApexPieCharts($elDiv, series, labels) {

        const colors = [
            "rgba(63, 205, 130, 1)",
            "rgba(1, 112, 255, 1)",
            "rgba(255, 162, 0, 1)",
        ];

        var options = {
            series: series,
            chart: {
                height: 266,
                type: 'donut',
            },
            legend: {
                position: 'bottom',
                offsetX: -16,
            },
            labels: labels,
            colors: colors,
        };

        var chart = new ApexCharts($elDiv, options);
        chart.render();
    }

    function handleApexAreaCharts($elDiv, series, colors, labels, height, prefix = null, prefixType = "after") {
        const options = {
            series: series,
            chart: {
                height: height,
                type: "area",
                toolbar: {
                    show: false
                },
                dropShadow: {
                    enabled: true,
                    top: 12,
                    left: 0,
                    bottom: 0,
                    right: 0,
                    blur: 2,
                    color: "rgba(132, 145, 183, 0.3)",
                    opacity: 0.35
                },
                zoom: {
                    enabled: false,
                    allowMouseWheelZoom: false
                }
            },
            /*annotations: {
                xaxis: [{x: 312, strokeDashArray: 4, borderWidth: 1, borderColor: ["var(--secondary)"]}],
                points: [
                    {
                        x: 312,
                        y: 52,
                        marker: {size: 6, fillColor: ["var(--primary)"], strokeColor: ["var(--card-bg)"], strokeWidth: 4, radius: 5},
                        label: {borderWidth: 1, offsetY: -110, text: "50k", style: {background: ["var(--primary)"], fontSize: "14px", fontWeight: "600"}},
                    },
                ],
            },*/
            colors: colors,
            dataLabels: {enabled: false},
            stroke: {show: true, curve: "smooth", width: [3, 3], dashArray: [0, 0], lineCap: "round"},
            labels: labels,
            yaxis: {
                labels: {
                    offsetX: -12,
                    offsetY: 0,
                    formatter: function (e) {
                        if (prefix) {
                            if (prefixType === "after") {
                                return e + prefix;
                            } else {
                                return prefix + e;
                            }
                        }

                        return e;
                    },
                },
            },
            grid: {strokeDashArray: 3, xaxis: {lines: {show: true}}, yaxis: {lines: {show: false}}},
            legend: {show: false},
            fill: {type: "gradient", gradient: {type: "vertical", shadeIntensity: 1, inverseColors: false, opacityFrom: 0.05, opacityTo: 0.05, stops: [45, 100]}},
        }

        const chart = new ApexCharts($elDiv, options);
        chart.render();

    }


    function handleApexBarCharts($elDiv, series, colors, labels, height) {

        const options = {
            series: series,
            chart: {
                height: height,
                type: "bar",
                toolbar: {
                    show: false
                }
            },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 1,
                    colorStops: [
                        {offset: 0, color: "rgba(1, 112, 255, 0.4)", opacity: 1},
                        {offset: 100, color: "rgba(1, 112, 255, 0.8)", opacity: 1},
                    ],
                },
            },
            plotOptions: {bar: {columnWidth: "55%", endingShape: "rounded", borderRadius: 5}},
            dataLabels: {enabled: false},
            legend: {show: false},
            yaxis: {labels: {show: false}},
            grid: {strokeDashArray: 3, xaxis: {lines: {show: false}}, yaxis: {lines: {show: false}}},
            xaxis: {
                type: "week",
                categories: labels,
                axisBorder: {show: false, color: "rgba(119, 119, 142, 0.05)", offsetX: 0, offsetY: 0},
                axisTicks: {show: false, borderType: "solid", color: "rgba(119, 119, 142, 0.05)", width: 6, offsetX: 0, offsetY: 0},
                labels: {rotate: -90, style: {colors: "rgb(107 ,114 ,128)", fontSize: "12px"}},
            },
        }

        const chart = new ApexCharts($elDiv, options);
        chart.render();
    }


})(jQuery);
