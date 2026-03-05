(function ($) {
    "use strict";


    $(document).ready(function () {
        const $dashboardEventsCalendar = $('#dashboardEventsCalendar');

        if ($dashboardEventsCalendar.length > 0) {
            handleEventsCalendar($dashboardEventsCalendar)
        }
    })

    function handleEventsCalendar($el) {
        $el.pDatepicker({
            inline: true,
            altField: '#inlineEventsCalender',
            initialValue: true,
            calendarType: (typeof appLocale !== "undefined" && (appLocale === "fa" || appLocale === "FA")) ? 'persian' : 'gregorian',
            initialValueType: true,
            autoClose: true,
            altFormat: 'DD MMMM YY',
            calendar: {
                gregorian: {
                    locale: 'en'
                },
                persian: {
                    locale: 'fa'
                }
            },
            toolbox: {
                calendarSwitch: {
                    enabled: false
                }
            },
            navigator: {
                scroll: {
                    enabled: false
                },
                text: {
                    btnNextText: '<',
                    btnPrevText: ">"
                }
            },
            minDate: new persianDate().subtract('day', 0).valueOf(),

            timePicker: {
                enabled: false,
            },

            onSelect: getEventsListsByDate,

            checkDate: function (unix) {
                setTimeout(function () {
                    checkDayHasEvents(unix)
                }, 10)

                return true;
            },
        });
    }

    function getEventsListsByDate(unix) {
        const $listParent = $('.js-day-events-card');

        if ($listParent.length) {
            const loadingHtml = `<div class="d-flex-center bg-white rounded-16 py-120 px-32 w-100">
                    <img src="/assets/design_1/img/loading.svg" width="56" height="56">
                </div>`;
            $listParent.html(loadingHtml)

            const pDate = new persianDate(unix);
            const timestamp = pDate.startOf('day').unix();
            const dayLabel = pDate.format('dddd');
            const date = pDate.format('YYYY-MM-DD');

            const data = {
                timestamp: unix / 1000,
            }
            const path = "/panel/events-calender/get-by-day";

            $.post(path, data, function (result) {
                if (result.code === 200) {
                    $listParent.html(result.html)
                }
            })
        }
    }

    function checkDayHasEvents(unix) {
        // $eventsWithTimestamp is defined globally in blade
        if (typeof $eventsWithTimestamp !== "undefined") {
            const unixTimestamp = unix / 1000;

            Object.keys($eventsWithTimestamp).forEach(function (key) {
                const event = $eventsWithTimestamp[key]

                if (event && event.start_day && event.end_day && event.start_day <= unixTimestamp && event.end_day >= unixTimestamp) {
                    const $cell = $(`td[data-unix="${unix}"]`);

                    if ($cell.length) {
                        $cell.addClass('has-event');
                    }
                }
            })
        }
    }

    $('body').on('click','.js-upcoming-event-card',function (e) {
        e.preventDefault();
        const $this = $(this);
        const timestamp = $this.attr('data-day');

        getEventsListsByDate(timestamp * 1000)
    })


})(jQuery);
