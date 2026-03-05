(function ($) {
    "use strict";

    /*******
     * Calendar
     *  */

    function handleShowDay(unix) {
        var day = new persianDate(unix).day();
        var startDayTime = new persianDate(unix).startOf('day').unix();
        var endDayTime = new persianDate(unix).endOf('day').unix();

        var showThisDay = false;

        // availableDays is defined globally in blade

        for (var index2 in availableDays) {
            var disabled_day = Number(availableDays[index2]);
            if (disabled_day === day) {
                showThisDay = true;
            }
        }

        return showThisDay;
    }

    function handlePDatepicker() {

        $(".inline-reservation-calender").pDatepicker({
            inline: true,
            altField: '#inlineCalender',
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
            checkDate: function (unix) {
                return handleShowDay(unix);

            },
            timePicker: {
                enabled: false,
            },
            onSelect: function (unix) {
                const pDate = (new persianDate(unix));
                const timestamp = pDate.startOf('day').unix();
                const dayLabel = pDate.format('dddd');
                const date = pDate.format('YYYY-MM-DD');

                $('#selectedDay').val(date);
                handleShowReservationTimes(timestamp, dayLabel, date);
            }
        });
    }

    if ($(".inline-reservation-calender").length) {
        handlePDatepicker();
    }

    function handleShowReservationTimes(timestamp, dayLabel, date) {
        const $beforeSelect = $('.js-before-select-day-card');
        const $container = $('.js-times-container');
        const $body = $container.find('.js-times-body');
        const availableTimes = $('#availableTimes');
        const $loading = $container.find('.js-loading-img');
        const username = $container.attr('data-user');

        $beforeSelect.removeClass('d-flex').addClass('d-none')
        $container.removeClass('d-none').addClass('d-flex');


        $body.addClass('d-none').removeClass('d-flex');
        $loading.removeClass('d-none').addClass('d-flex');

        $container.find('.js-selected-date').text($('#inlineCalender').val());

        $('input[name="date"]').val(timestamp)

        const path = `/users/${username}/availableTimes`;

        const data = {
            timestamp: timestamp,
            day_label: dayLabel,
            date: date,
        };

        $.post(path, data, function (result) {
            let html = '';

            if (result && typeof result.times !== "undefined") {
                Object.keys(result.times).forEach(key => {
                    const item = result.times[key];

                    if (item.time) {
                        let times = item.time.split('-');

                        if (times.length === 2) {
                            html += `<div class="profile-available-times-item">
                                    <input type="radio" name="time" id="availableTime_${item.id}" value="${item.id}" ${(item.can_reserve ? '' : 'disabled')} data-type="${item.meeting_type}">
                                    <label for="availableTime_${item.id}" class="d-flex align-items-center p-12 rounded-12 font-14 font-weight-bold ${(item.can_reserve ? '' : 'unavailable')}">
                                        <span class="">${times[0]}</span>
                                        <div class="time-center-divider position-relative d-flex-center size-16 rounded-circle mx-24">
                                            <x-iconsax-bul-teacher class="icons" width="12px" height="12px"/>
                                        </div>
                                        <span class="">${times[1]}</span>
                                    </label>

                                    <input type="hidden" class="js-time-description" value="${item.description}"/>
                                </div>`
                        }
                    }
                });
            }

            availableTimes.html(html);
        }).always(() => {
            $body.removeClass('d-none').addClass('d-flex');
            $loading.removeClass('d-flex').addClass('d-none');
        });
    }


    $('body').on('change', 'input[name="time"]', function (e) {
        if (this.checked) {
            const $body = $('.js-times-body');
            const $this = $(this);
            const type = $this.attr('data-type');


            // Show Time Description
            const $timeDescription = $this.parent().find('.js-time-description');
            const $timeDescriptionCard = $('.js-time-description-card');

            if ($timeDescription && $timeDescription.val() && $timeDescription.val() !== 'null' && $timeDescription.val() !== 'undefined') {
                $timeDescriptionCard.removeClass('d-none');
                $timeDescriptionCard.text($timeDescription.val());
            } else {
                $timeDescriptionCard.addClass('d-none');
            }

            // Show Proceed Button
            $('.js-reserve-btn').removeClass('d-none');
        }
    })

    $('body').on('click', '.js-proceed-to-booking-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('form')
        const data = $form.serializeObject();

        let path = $form.attr("action");
        path += `?date=${data['date']}&time=${data['time']}`;

        $this.addClass('loadingbar').prop('disabled', true);

        window.location.href = path
    })

})(jQuery)
