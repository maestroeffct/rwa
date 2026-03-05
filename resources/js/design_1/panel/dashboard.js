(function ($) {
    "use strict";


    $(document).ready(function () {

        if (typeof learningActivityChartLabels !== "undefined" && typeof learningActivityChartData !== "undefined") {
            handleActivityChart(learningActivityChartLabels, learningActivityChartData)
        }

        if (typeof instructorSalesOverviewChartLabels !== "undefined") {
            handleInstructorSalesOverviewChart()
        }

        if (typeof instructorVisitorsChartLabels !== "undefined" && typeof instructorVisitorsChartData !== "undefined") {
            handleVisitorsStatisticsChart(instructorVisitorsChartLabels, instructorVisitorsChartData)
        }
    })

    function handleInstructorSalesOverviewChart() {
        const series = [
            {name: courseSalesLang, data: instructorSalesOverviewChartCourseSales},
            {name: meetingSalesLang, data: instructorSalesOverviewChartMeetingSales},
            {name: productSalesLang, data: instructorSalesOverviewChartProductSales},
        ]

        const colors = [
            "rgba(22, 93, 255, 0.3)",
            "rgba(205, 213, 226, 0.3)",
            "rgba(63, 205, 130, 0.3)",
        ];

        const labels = instructorSalesOverviewChartLabels;

        const $elDiv = document.querySelector("#instructorSalesOverviewChart");

        handleApexAreaCharts($elDiv, series, colors, labels, 354, jsCurrentCurrency, "before")
    }

    function handleActivityChart(labels, data) {
        const series = [
            {name: learningActivityLang, data: data},
        ]

        const colors = [
            "rgba(22, 93, 255, 0.3)",
        ];

        const $elDiv = document.querySelector("#learningActivityChart");

        handleApexAreaCharts($elDiv, series, colors, labels, 354, ` ${minsLang}`, "after")
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


    function handleVisitorsStatisticsChart(labels, data) {
        const series = [
            {name: visitorsLang, data: data}
        ];

        const colors = [
            "rgba(1, 112, 255, 0.4)",
        ];

        const $elDiv = document.querySelector("#visitorsStatisticsChart");

        handleApexBarCharts($elDiv, series, colors, labels, 244)
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

    /*==========
    * Noticeboards
    ********** */
    $('body').on('click', '.js-show-noticeboard-info', function (e) {
        e.preventDefault();

        const $this = $(this);
        const noticeboardId = $this.attr('data-id');
        const $parent = $this.closest('.js-noticeboard-card');
        const $senderDiv = $parent.find('.js-noticeboard-sender-info');
        const message = $this.parent().find('input').val();

        const html = `
            <div class="d-flex align-items-center bg-gray-100 rounded-16 p-16">${$senderDiv.html()}</div>
            <div class="bg-white border-gray-100 rounded-12 p-16 mt-32 mb-16 text-black white-space-pre-wrap">${message}</div>`;

        Swal.fire({
            html: makeModalHtml(noticeLang, closeIcon, html, '&nbsp;'),
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '36rem',
            didOpen: () => {
                const $body = $('.js-custom-modal');
                const $footer = $('.custom-modal-footer');

                $footer.remove();
            }
        });

        if (!$this.hasClass('seen-at')) {
            $.get('/panel/noticeboard/' + noticeboardId + '/saveStatus', function () {
            })
        }
    })

})(jQuery);
