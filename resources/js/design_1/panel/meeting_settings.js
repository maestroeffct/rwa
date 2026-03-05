(function ($) {
    "use strict";

    $('body').on('click', '.js-add-time', function (e) {
        e.preventDefault();
        const day = $(this).closest('tr').attr('data-day');
        const modalTitle = $(this).attr('data-modalTitle');
        const path = `/panel/meetings/get-meeting-time-modal`

        handleBasicModal(path, modalTitle, function (result, $body, $footer) {
            const footerHtml = `<div class="w-100 text-right">
                <button type="button" class="js-save-time btn btn-primary" data-day="${day}">${saveLang}</button>
            </div>`;
            $footer.html(footerHtml);

            const $timePicker = $body.find(".date-clock-picker");
            handleTimePicker($timePicker)

        }, '', '40rem')
    });

    $('body').on('click', '.js-save-time', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-meeting-time-form');
        const day = $this.attr('data-day');
        const data = $form.serializeObject();
        data['day'] = day;

        $this.addClass('loadingbar primary').prop('disabled', true);

        $.post('/panel/meetings/saveTime', data, function (result) {
            if (result && result.registration_package_limited) {
                handleLimitedAccountModal(result.registration_package_limited);
            } else if (result && result.code === 200) {
                Swal.fire({
                    title: deleteAlertSuccess,
                    html: `<div class="text-center pb-16">${successSavedTime}</div>`,
                    showConfirmButton: false,
                    icon: 'success',
                });

                setTimeout(() => {
                    window.location.reload();
                }, 1000)
            }
        }).fail((err) => {
            $this.removeClass('loadingbar primary').prop('disabled', false);
            var errors = err.responseJSON;

            if (errors && errors.errors) {
                Object.keys(errors.errors).forEach((key) => {
                    const error = errors.errors[key];
                    let element = $form.find('.js-ajax-' + key);

                    element.addClass('is-invalid');
                    element.parent().find('.invalid-feedback').text(error[0]);
                });
            }
        })
    });

    $('body').on('change', '#inPersonMeetingSwitch', function () {
        const inPersonMeetingAmount = $('#inPersonMeetingAmount');
        const inPersonGroupMeetingOptions = $('#inPersonGroupMeetingOptions');

        if (this.checked) {
            inPersonMeetingAmount.removeClass('d-none');

            if ($('#groupMeetingSwitch').is(':checked')) {
                inPersonGroupMeetingOptions.removeClass('d-none');
            }
        } else {
            inPersonMeetingAmount.addClass('d-none');
            inPersonGroupMeetingOptions.addClass('d-none');
        }
    });

    $('body').on('change', '#groupMeetingSwitch', function () {
        const onlineGroupMeetingOptions = $('#onlineGroupMeetingOptions');
        const inPersonGroupMeetingOptions = $('#inPersonGroupMeetingOptions');

        if (this.checked) {
            onlineGroupMeetingOptions.removeClass('d-none');

            if ($('#inPersonMeetingSwitch').is(':checked')) {
                inPersonGroupMeetingOptions.removeClass('d-none');
            }
        } else {
            onlineGroupMeetingOptions.addClass('d-none');
            inPersonGroupMeetingOptions.addClass('d-none');
        }
    });

    function deleteTimeModal(time_id) {
        var html = '<div class="px-16 pb-20">\n' +
            '    <p class="text-center">' + deleteAlertHint + '</p>\n' +
            '    <div class="mt-28 d-flex align-items-center justify-content-center">\n' +
            '        <button type="button" id="deleteTime" data-time-id="' + time_id + '" class="btn btn-sm btn-primary">' + deleteAlertConfirm + '</button>\n' +
            '        <button type="button" class="btn btn-sm btn-danger ml-12 close-swl">' + deleteAlertCancel + '</button>\n' +
            '    </div>\n' +
            '</div>';

        Swal.fire({
            title: deleteAlertTitle,
            html: html,
            icon: 'warning',
            showConfirmButton: false,
            showCancelButton: false,
            allowOutsideClick: () => !Swal.isLoading(),
        });
    }

    $('body').on('click', '.remove-time', function (e) {
        e.preventDefault();
        const $this = $(this);
        const time_id = $this.attr('data-time-id');

        deleteTimeModal(time_id);
    });

    $('body').on('click', '#deleteTime', function (e) {
        e.preventDefault();
        const $this = $(this);

        let time_id = $this.attr('data-time-id');
        time_id = time_id.split(',');

        handleRemoveTime(time_id);

        Swal.close();

        for (let id of time_id) {
            $('.remove-time[data-time-id="' + id + '"]').parent().remove();
        }
    });

    function handleRemoveTime(time_id) {
        const data = {
            time_id: time_id,
        };

        $.post('/panel/meetings/deleteTime', data, function (result) {
            showToast('success', deleteAlertSuccess, successDeleteTime)
        }).fail(() => {
            showToast('error', deleteAlertFail, errorDeleteTime)
        });
    }

    $('body').on('click', '.js-clear-all', function (e) {
        e.preventDefault();
        const parent = $(this).closest('tr');

        const timeIds = parent.find('.selected-time .remove-time').map(function () {
            return this.dataset.timeId;
        }).get();

        deleteTimeModal(timeIds.join(','));
    });


    $('body').on('click', '#meetingSettingFormSubmit', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('form');
        const action = $form.attr('action');
        let data = serializeObjectByTag($form);

        $this.addClass('loadingbar primary').prop('disabled', true);

        $.post(action, data, function (result) {
            if (result && result.code === 200) {
                Swal.fire({
                    icon: 'success',
                    html: `<div class="p-16 text-center"><p class="text-black">${saveMeetingSuccessLang}</p></div>`,
                    showConfirmButton: false,
                    width: '25rem',
                });

                setTimeout(() => {
                    window.location.reload();
                }, 500)
            }
        }).fail(err => {
            $this.removeClass('loadingbar primary').prop('disabled', false);

            var errors = err.responseJSON;
            if (errors && errors.errors) {
                Object.keys(errors.errors).forEach((key) => {
                    const error = errors.errors[key];
                    let element = $form.find('[name="' + key + '"]');
                    element.addClass('is-invalid');
                    element.parent().find('.invalid-feedback').text(error[0]);
                });
            }
        })
    })
})(jQuery);
