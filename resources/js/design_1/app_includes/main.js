(function ($) {
    "use strict"

    /* dropdown */
    // **
    // **
    $('.dropdown-toggle').dropdown();

    /**
     * close swl
     * */
    $('body').on('click', '.close-swl', function (e) {
        e.preventDefault();
        Swal.close();
    });


    /**
     * Tooltip
     * */
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    window.tippyTooltip = function () {
        tippy.setDefaultProps({
            delay: 50,
            animation: 'shift-away',
        });

        tippy('[data-tippy-content]');
    }

    $(document).ready(function () {
        if (typeof tippy !== "undefined") {
            tippyTooltip()
        }
    })

    window.isBodyRtl = function () {
        return ($('body').hasClass('rtl'))
    }

    /**
     * select
     * */

    /* on tag
    * data-allow-clear="false"
    * data-placeholder=""
    * multiple
    * data-minimum-results-for-search="Infinity" => disable search input
    * */
    window.handleSelect2 = ($element) => {
        const placeholder = $element.attr('data-placeholder')
        const dropdownParent = $element.attr('data-dropdown-parent') ?? 'body'
        const maximumSelectionLength = $element.attr('data-maximumSelectionLength') ?? 0; // default 0 means not limitation

        return $element.select2({
            placeholder: placeholder,
            width: '100%',
            dropdownParent: $(dropdownParent),
            maximumSelectionLength: maximumSelectionLength,
            dir: isBodyRtl() ? 'rtl' : 'ltr',
        });
    }

    window.handleSearchableSelect = ($element) => {
        const column = $element.attr('data-item-column-name')
        const placeholder = $element.attr('data-placeholder')
        const apiPath = $element.attr('data-api-path')
        const option = $element.attr('data-option')
        const webinarId = $element.attr('data-webinar-id')
        const itemId = $element.attr('data-item-id')
        const dropdownParent = $element.attr('data-dropdown-parent') ?? 'body'
        const maximumSelectionLength = $element.attr('data-maximumSelectionLength') ?? 0; // default 0 means not limitation

        $element.select2({
            placeholder: placeholder,
            minimumInputLength: 3,
            allowClear: true,
            width: '100%',
            dir: isBodyRtl() ? 'rtl' : 'ltr',
            dropdownParent: $(dropdownParent),
            maximumSelectionLength: maximumSelectionLength,
            ajax: {
                url: apiPath,
                dataType: 'json',
                type: "POST",
                quietMillis: 50,
                data: function (params) {
                    return {
                        term: params.term,
                        option: option,
                        webinar_id: webinarId,
                        item_id: itemId,
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item[column] ?? '',
                                id: item['id'] ?? null
                            }
                        })
                    };
                }
            }
        });
    }


    $(document).ready(function () {
        const searchableSelect = $('.searchable-select');
        const select2 = $('.select2');

        if (searchableSelect && searchableSelect.length) {
            handleSearchableSelect(searchableSelect)
        }

        if (select2 && select2.length) {
            for (const select2El of select2) {
                handleSelect2($(select2El))
            }
        }

        const $iconsSelect2 = $('.js-icons-select2');
        if ($iconsSelect2.length) {
            handleChooseIconSelect2($iconsSelect2)
        }
    })

    /**
     * select
     * */


    /*
    * loading Swl
    * */
    window.loadingSwl = () => {
        const loadingHtml = '<div class="d-flex align-items-center justify-content-center py-56 "><img src="/assets/design_1/img/loading.svg" width="80" height="80"></div>';
        Swal.fire({
            html: loadingHtml,
            showCancelButton: false,
            showConfirmButton: false,
            width: '24rem',
        });
    };

    //
    // delete sweet alert
    $('body').on('click', '.delete-action', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const href = $(this).attr('href');

        const modalTitle = $(this).attr('data-modal-title') ?? deleteRequestLang;
        const title = $(this).attr('data-title') ?? deleteAlertTitle;
        const msg = $(this).attr('data-msg') ?? deleteAlertHint;
        const confirm = $(this).attr('data-confirm') ?? deleteAlertConfirm;

        const bodyHtml = `<div class="d-flex-center flex-column text-center p-32">
                <div class="d-flex-center size-72 rounded-16 bg-gray">
                    <div class="d-flex-center size-64 rounded-16 bg-danger">${bulDangerIcon}</div>
                </div>

                <h4 class="font-14 text-dark mt-8">${title}</h4>
                <p class="text-gray-500 mt-8">${msg}</p>
            </div>`;

        const $footerHtml = `<div class="d-flex align-items-center gap-24 justify-content-end">
            <button type="button" class="close-swl btn btn-transparent">${deleteAlertCancel}</button>
            <button type="button" id="swlDelete" data-href="${href}" class="btn btn-danger">${confirm}</button>
        </div>`;

        const html = makeModalHtml(modalTitle, closeIcon, bodyHtml, $footerHtml)

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            width: '36rem',
            allowOutsideClick: () => !Swal.isLoading(),
            didOpen: function () {
            },
        })

    });

    $('body').on('click', '#swlDelete', function (e) {
        e.preventDefault();
        var $this = $(this);
        const href = $this.attr('data-href');

        $this.addClass('loadingbar primary').prop('disabled', true);

        $.get(href, function (result) {
            if (result && result.code === 200) {
                const title = result.title ?? deleteAlertSuccess;
                const msg = result.msg ?? deleteAlertSuccessHint;

                Swal.fire({
                    title: title,
                    html: `<div class="text-center mt-8 mb-12">${msg}</div>`,
                    showConfirmButton: false,
                    icon: 'success',
                });
                setTimeout(() => {

                    if (typeof result.redirect_to !== "undefined" && result.redirect_to !== undefined && result.redirect_to !== null && result.redirect_to !== '') {
                        window.location.href = result.redirect_to;
                    } else {
                        window.location.reload();
                    }
                }, 1000)
            } else {
                const title = result.title ?? deleteAlertFail;
                const msg = result.msg ?? deleteAlertFailHint;

                Swal.fire({
                    title: title,
                    html: `<div class="text-center mt-8 mb-12">${msg}</div>`,
                    icon: 'error',
                })

                $this.removeClass('loadingbar primary').prop('disabled', false);
            }
        }).fail(err => {
            Swal.fire({
                title: deleteAlertFail,
                html: `<div class="text-center mt-8 mb-12">${deleteAlertFailHint}</div>`,
                icon: 'error',
            })

            $this.removeClass('loadingbar primary').prop('disabled', false);
        })
    })


    // ********************************************
    // ********************************************
    // form serialize to Object
    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    window.serializeObjectByTag = (tagId) => {
        var o = {};
        var a = tagId.find('input, textarea, select').serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };


    /*
    * Custom Toast
    * */

    function handleCustomToastHide($el) {
        $el.removeClass('show');

        setTimeout(function () {
            $el.remove();
        }, 600)
    }

    window.customToast = function (html, hideAfter = 5000) {
        const randomId = 'id-' + randomString(6);

        $('body').append(`<div class="custom-toast-alert" id="${randomId}">${html}</div>`)

        const $toastEl = $('#' + randomId);

        setTimeout(function () {
            $toastEl.addClass('show');
        }, 100)

        // Hide Toast
        setTimeout(function () {
            handleCustomToastHide($toastEl)
        }, hideAfter)
    }

    $('body').on('click', '.js-close-toast-alert', function (e) {
        e.preventDefault();

        handleCustomToastHide($(this).closest('.custom-toast-alert'))
    })

    /*
    * Handle ajax FORBIDDEN requests
    * */
    $(document).on('ajaxError', function (event, xhr) {
        if (xhr.status === 401 || xhr.status === 403) {
            showToast('error', forbiddenRequestToastTitleLang, forbiddenRequestToastMsgLang)
        }
    });


    window.randomString = function (count = 5) {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        for (var i = 0; i < count; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    };

    $('body').on('click', '#goToTop', function (e) {
        e.preventDefault();

        $('html, body').animate({
            scrollTop: 0
        }, 500);
    })

    $('body').on('change', 'input[type="file"]', function () {
        const value = this.value;

        if (value) {
            const splited = value.split('\\');

            if (splited.length) {
                $(this).closest('.custom-file').find('.custom-file-text').text(splited[splited.length - 1])
            }
        }
    })


    $('body').on('click', '.js-language-dropdown-item', function () {
        const $this = $(this);
        const value = $this.attr('data-value');
        const title = $this.attr('data-title');
        const flag = $this.find('img').attr('src')
        const parent = $this.closest('.js-language-select');

        parent.find('input[name="locale"]').val(value);
        parent.find('.js-lang-title').text(title);
        parent.find('.language-toggle-flag img').attr('src', flag);

        if (!parent.hasClass('js-dont-submit')) {
            parent.find('form').trigger('submit')
        }
    });

    $('body').on('click', '.js-currency-dropdown-item', function () {
        const $this = $(this);
        const value = $this.attr('data-value');
        const title = $this.attr('data-title');
        const parent = $this.closest('.js-currency-select');

        parent.find('input[name="currency"]').val(value);
        parent.find('.js-lang-title').text(title);

        if (!parent.hasClass('js-dont-submit')) {
            parent.find('form').trigger('submit')
        }
    });


    function handleAccordionArrow($accordion, show, target) {
        const $arrow = $accordion.find('.collapse-arrow-icon[href="' + target + '"]')

        if ($arrow.hasClass('arrow-right')) {
            if (show) {
                $arrow.addClass('arrow-down')
            } else {
                $arrow.removeClass('arrow-down')
            }
        } else {
            if (show) {
                $arrow.removeClass('arrow-down')
            } else {
                $arrow.addClass('arrow-down')
            }
        }
    }

    window.handleAccordionCollapse = function () {
        $("[data-toggle='collapse']").each(function () {
            const $this = $(this);
            const target = $this.attr('href');
            const $accordion = $this.closest('.accordion');
            const $target = $(target);

            if ($target.hasClass('show')) {
                $target.slideDown();
                handleAccordionArrow($accordion, true, target);
            } else {
                $target.slideUp();
                handleAccordionArrow($accordion, false, target);
            }

            // $this.off('click') => Prevent multiple click events from being recorded
            // Resolving the issue of accordions opening and closing multiple times

            $this.off('click').on('click', function () {

                const parent = $this.attr('data-parent')
                const $parent = $(`${parent}`);
                const collapseJustOne = ($this.attr("data-collapse") === "one")

                if ($parent.length && collapseJustOne) {
                    $parent.find('.accordion__collapse.show').each(function () {
                        const $openTarget = $(this);
                        const $openTargetAccordion = $openTarget.closest('.accordion')

                        $openTarget.slideUp().removeClass('show');
                        handleAccordionArrow($openTargetAccordion, false, target);
                    });
                }


                if ($target.hasClass('show')) {
                    $target.slideUp().removeClass('show');
                    handleAccordionArrow($accordion, false, target);
                } else {
                    $target.addClass('show').slideDown();
                    handleAccordionArrow($accordion, true, target);
                }

                return false;
            });
        });
    };
    handleAccordionCollapse();

    $('body').on('click', '.cancel-accordion', function () {
        $(this).closest('.accordion').remove();
    })

    window.makeModalHtml = function (title, cIcon, html, footer = null, subtitle = null) {
        return `<div class="js-custom-modal rounded-top-20 soft-shadow-5 pt-24">
            <div class="d-flex align-items-center justify-content-between px-16">
                <div class="">
                    <h3 class="font-16 text-black">${title}</h3>

                    ${subtitle ? '<p class="mt-8 font-14 text-gray-500">' + subtitle + '</p>' : ''}
                </div>

                <button class="modal-close-btn close-swl">${cIcon}</button>
            </div>

            <div class="position-relative py-8 custom-swl-modal-body has-footer px-16">
                ${html}
            </div>

            ${footer ? `<div class="custom-modal-footer bg-gray-100 p-16 w-100 rounded-bottom-20">${footer}</div>` : ''}
        </div>`;
    }

    $('body').on('click', '.js-login-toast', function (e) {
        e.preventDefault();

        if (notLoginToastTitleLang && notLoginToastMsgLang) {
            showToast('error', notLoginToastTitleLang, notLoginToastMsgLang);
        }
    });

    window.handleTranslations = function (translations, defaultLocale, column, justTranslateByLocale = false) {
        let text = null;

        if (Object.keys(translations).length) {
            Object.keys(translations).forEach(key => {
                const translation = translations[key];

                if (translation.locale === defaultLocale) {
                    text = translation[column]
                }
            })

            if (!text && !justTranslateByLocale) {
                text = translations[0][column]
            }
        }

        return text;
    }

    $('body').on('click', '.js-copy-input', function (e) {
        e.preventDefault();

        const $this = $(this);
        const copyText = $this.attr('data-copy-text');
        const doneText = $this.attr('data-done-text');
        const $input = $this.closest('.form-group').find('input');

        $input.removeAttr('disabled');
        $input.focus();
        $input.select();
        document.execCommand("copy");
        navigator.clipboard.writeText($input.val());

        $this.attr('data-original-title', doneText)
            .tooltip('show');
        $this.attr('data-original-title', copyText);
    })

    window.lockBodyScroll = function (lock) {
        const root = document.getElementsByTagName('html')[0];

        if (lock) {
            root.classList.add('close-body-scroll');
        } else {
            root.classList.remove('close-body-scroll');
        }
    }

    /* input-step */
    $('body').on('click', '.input-step .plus', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $input = $this.closest('.input-step').find('input');

        const val = $input.val() ? Number($input.val()) : 0;

        $input.val(val + 1)
    })

    $('body').on('click', '.input-step .minus', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $input = $this.closest('.input-step').find('input');

        const val = $input.val() ? Number($input.val()) : 0;

        if ((val - 1) >= 0)
            $input.val(val - 1)
    })

    /*****
     * Event shown.cs.tab => when sho
     * ****/
    $('body').on('click', '[data-tab-toggle]', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $parent = $this.closest('.custom-tabs');
        const href = $this.attr('data-tab-href');

        $parent.find('[data-tab-toggle]').removeClass('active');
        $parent.find('.custom-tabs-content').removeClass('active');

        $this.addClass('active');

        const $target = $(href);
        $target.addClass('active');

        // Trigger custom event
        const customEvent = $.Event('shown.cs.tab', {bubbles: true});
        $target.trigger(customEvent);
    })


    // ********************************************
    // ********************************************
    // date & time piker

    window.makeDateRangePicker = function ($el, drops = 'down') {
        const format1 = $el.attr('data-format') ?? 'YYYY-MM-DD';
        const timepicker1 = !!$el.attr('data-timepicker');
        const elParent = $el.attr('data-el-parent') ?? 'body'

        $el.daterangepicker({
            locale: {
                format: format1,
                cancelLabel: clearLang
            },
            drops: drops,
            autoUpdateInput: false,
            timePicker: timepicker1,
            timePicker24Hour: true,
            opens: 'right',
            parentEl: elParent,
        });
        $el.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format(format1) + ' - ' + picker.endDate.format(format1));
        });

        $el.on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    }

    window.makeDateTimepicker = function ($el, drops = 'down') {
        const format2 = $el.attr('data-format') ?? 'YYYY-MM-DD HH:mm';
        const showDropdowns = !!($el.attr('data-show-drops'));
        const elParent = $el.attr('data-el-parent') ?? 'body'

        $el.daterangepicker({
            locale: {
                format: format2,
                cancelLabel: clearLang
            },
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            autoUpdateInput: false,
            showDropdowns: showDropdowns,
            drops: drops,
            parentEl: elParent,
            period: 'day' | 'month' | 'year'
        });
        $el.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm'));
        });

        $el.on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    }

    window.makeSingleDatePicker = function ($el, drops = 'down') {
        const format3 = $el.attr('data-format') ?? 'YYYY-MM-DD';
        const showDropdowns = !!($el.attr('data-show-drops'));
        const elParent = $el.attr('data-el-parent') ?? 'body'

        $el.daterangepicker({
            locale: {
                format: format3,
                cancelLabel: clearLang
            },
            singleDatePicker: true,
            timePicker: false,
            autoUpdateInput: false,
            showDropdowns: showDropdowns,
            drops: drops,
            parentEl: elParent,
        });
        $el.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
        });

        $el.on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    }

    window.resetDatePickers = (drops = 'down') => {

        /*
        * drops => down | up
        * */

        if (jQuery().daterangepicker) {
            const $dateRangePicker = $('.date-range-picker');
            makeDateRangePicker($dateRangePicker, drops)

            const $datetimepicker = $('.datetimepicker');
            makeDateTimepicker($datetimepicker, drops)

            const $datepicker = $('.datepicker');
            makeSingleDatePicker($datepicker, drops)

        }
    };


    // Timepicker
    window.handleClockPicker = function ($el) {
        if (jQuery().timepicker) {
            $el.timepicker({
                icons: {
                    up: 'chevron-up-icon',
                    down: 'chevron-down-icon'
                },
                showMeridian: false,
            });
        }
    }

    window.handleTimePicker = function ($timePicker) {
        $timePicker.persianDatepicker({
            format: 'HH:mm',
            calendarType: 'gregorian',
            calendar: {
                persian: {
                    locale: 'en'
                }
            },
            onlyTimePicker: true,
            timePicker: {
                minute: {
                    step: 5
                },
                second: {
                    enabled: false
                }
            }
        });
    }

    /*
    * Select locale change
    * */
    $('body').on('change', '.js-edit-content-locale, .js-reload-when-selected', function (e) {
        e.preventDefault();

        const value = $(this).val();
        if (!value) return;

        const url = new URL(window.location.href);
        url.searchParams.set('locale', value);

        window.location.href = url.toString();
    })

    /*
    * lists draggable sort
    * */
    $(document).ready(function () {

        const $defaultInitDatePickers = $('.js-default-init-date-picker');

        if ($defaultInitDatePickers.length) {
            const drops = $defaultInitDatePickers.attr("data-drops") ?? 'down';
            resetDatePickers(drops);
        }


        function updateToDatabase(path, table, idString) {
            const data = {
                table: table,
                items: idString,
            };

            $.post(path, data, function (result) {
                if (result && result.title && result.msg) {
                    showToast('success', result.title, result.msg)
                }
            });
        }

        function setSortable(target) {
            if (target.length) {
                target.sortable({
                    group: 'no-drop',
                    handle: '.move-icon',
                    axis: "y",
                    update: function (e, ui) {
                        const sortData = target.sortable('toArray', {attribute: 'data-id'});
                        const path = e.target.getAttribute('data-path');
                        const table = e.target.getAttribute('data-order-table');

                        updateToDatabase(path, table, sortData.join(','))
                    }
                });
            }
        }

        const items = [];

        var draggableContentLists = $('.draggable-content-lists');
        if (draggableContentLists.length) {
            for (let item of draggableContentLists) {
                items.push($(item).attr('data-drag-class'))
            }
        }

        if (items.length) {
            for (let item of items) {
                const tag = $('.' + item);

                if (tag.length) {
                    setSortable(tag);
                }
            }
        }
    });

    /*
    *
    * */
    window.handleSendRequestItemForm = function ($form, $this, path = null, formActionAttr = "data-action", scrollToError = true) {
        let action = path ? path : $form.attr(formActionAttr);

        $this.addClass('loadingbar').prop('disabled', true);
        $form.find('input').removeClass('is-invalid');
        $form.find('textarea').removeClass('is-invalid');
        $form.find('.invalid-feedback').text('');

        const $customAlertEl = $form.find(".js-form-custom-alert");
        if ($customAlertEl.length > 0) {
            $customAlertEl.addClass("d-none").removeClass("d-flex")
        }

        let formData = new FormData();

        const items = $form.find('input, textarea, select').serializeArray();
        $.each(items, function () {
            formData.append(this.name, this.value);
        });

        const uploadFiles = $form.find('.js-ajax-upload-file-input');

        if (uploadFiles.length) {
            for (const uploadFileEl of uploadFiles) {
                const uploadFile = $(uploadFileEl);

                if (uploadFile && uploadFile.prop('files') && uploadFile.prop('files')[0]) {
                    const name = uploadFile.attr('data-upload-name') ? uploadFile.attr('data-upload-name') : 'upload_file';

                    formData.append(name, uploadFile.prop('files')[0]);
                }
            }
        }


        const images = $form.find('.js-create-property-images');
        for (const image of images) {
            const $image = $(image);

            if ($image && $image.prop('files') && $image.prop('files')[0]) {
                formData.append('images[]', $image.prop('files')[0]);
            }
        }

        $.ajax({
            url: action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function (result) {
                if (result && result.code === 200) {
                    //window.location.reload();
                    const dontReloadPage = (typeof result.dont_reload !== "undefined" && result.dont_reload);

                    const title = result.title ?? requestSuccessLang;
                    const msg = result.msg ?? saveSuccessLang;

                    showToast('success', title, msg);

                    const timeout = (result.redirect_timeout) ? Number(result.redirect_timeout) : 500;

                    if (dontReloadPage) {
                        if (typeof Swal !== "undefined") {
                            Swal.close();
                        }
                    } else {
                        setTimeout(() => {
                            if (result.redirect_to && result.redirect_to !== 'null') {
                                window.location.href = result.redirect_to;
                            } else {
                                window.location.reload();
                            }
                        }, timeout)
                    }
                }
            },
            error: function (err) {
                $this.removeClass('loadingbar').prop('disabled', false);
                var errors = err.responseJSON;

                if (errors && errors.errors) {
                    Object.keys(errors.errors).forEach((key) => {
                        const error = errors.errors[key];
                        const ky = key.replaceAll('.', '-');

                        let element = $form.find('.js-ajax-' + ky);

                        element.addClass('is-invalid');
                        element.closest('.form-group').find('.invalid-feedback').text(error[0]);
                    });

                    if (scrollToError) {
                        const $elScroll = $form.find('.is-invalid');

                        if ($elScroll.length) {
                            const $swlModalBody = $('.custom-swl-modal-body');

                            if ($swlModalBody.length) {
                                $swlModalBody.animate({
                                    scrollTop: $elScroll.offset().top
                                }, 1000);
                            } else {
                                $('html, body').animate({
                                    scrollTop: $elScroll.offset().top
                                }, 1000);
                            }
                        }
                    }
                }

                // Custom Alert
                if (errors.custom_alert && $customAlertEl.length > 0) {
                    $customAlertEl.removeClass("d-none").addClass("d-flex")
                    $customAlertEl.find("span").text(errors.custom_alert)
                }

                // toast
                if (errors.toast_alert) {
                    showToast('error', errors.toast_alert.title, errors.toast_alert.msg)
                }

                // Refresh Captcha
                if ($form.find(".js-ajax-captcha").length) {
                    refreshCaptcha();
                }
            }
        });
    }


    window.validatePrice = function (input) {
        const $input = $(input);
        const value = $input.val();
        const $error = $input.closest('.form-group').find('.invalid-feedback');

        $error.text('');

        if (/^\d*\.?\d*$/.test(value)) {
            $input.removeClass('is-invalid');
        } else {
            $input.addClass('is-invalid');
            $error.text(priceInvalidHintLang ?? 'Price Invalid');
        }
    }

    // =========
    // Basic Modal
    // ======
    window.handleBasicModal = function (path, title, callback, subtitle = null, modalSize = '34rem') {

        const html = `<div class="basic-modal-body">
                <div class="js-loading-card d-flex align-items-center justify-content-center py-40">
                    <img src="/assets/design_1/img/loading.svg" width="80" height="80">
                </div>
            </div>`;

        Swal.fire({
            html: makeModalHtml(title, closeIcon, html, '&nbsp;', subtitle),
            showCancelButton: false,
            showConfirmButton: false,
            width: modalSize,
            didOpen: function () {
                const $body = $('.basic-modal-body');
                const $footer = $('.custom-modal-footer');

                $.get(path, function (result) {
                    $body.find('.js-loading-card').remove();
                    $body.html(result.html);

                    if (typeof callback !== "undefined") {
                        callback(result, $body, $footer);
                    }
                }).fail(err => {
                    console.log(err)
                })
            }
        });
    }


    // **************************
    // file manager conf

    $('body').on('click', '.panel-file-manager', function (e) {
        e.preventDefault();
        $(this).filemanager('file', {prefix: '/admin/lfm'})
    });

    /*
    * // handle limited account modal
    * */
    window.handleFireSwalModal = function (html, size = 30) {
        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            width: size + 'rem',
        });
    };


    /*****
     * Submit Form
     * ****/
    $('body').on('click', '.js-submit-form-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('form');

        $this.addClass('loadingbar').prop('disabled', true);

        $form.trigger('submit');
    })

    window.handleBootstrapTags = function ($el) {
        if (jQuery().tagsinput) {
            $el.tagsinput({
                tagClass: 'bg-primary px-8 py-4 rounded-5',
                maxTags: ($el.data('max-tag') ? $el.data('max-tag') : 10),
            });
        }
    }

    $(document).ready(function () {
        $('img.js-avatar-img').on('error', function () {
            if (defaultAvatarPath) {
                $(this).attr('src', defaultAvatarPath);
            }
        });

        /// Tags
        var $inputTags = $('.inputtags');
        handleBootstrapTags($inputTags)
    });

    window.updateQueryParamAndReload = function (key, value) {
        let url = window.location.href;
        let separator = (url.indexOf("?") === -1) ? "?" : "&";
        let newParam = key + "=" + value;

        if (url.indexOf(key + "=") >= 0) {
            let regex = new RegExp(key + "=[^&]*");
            url = url.replace(regex, newParam);
        } else {
            url += separator + newParam;
        }

        window.location.href = url;
    }


    $('body').on('click', '.js-custom-file-clear', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $parent = $this.closest('.custom-file');

        const text = $this.attr('data-text') ?? '';

        $parent.find('input').val('');
        $parent.find('.custom-file-text').text(text);

        $this.remove()
    })

    /* Back to top */
    $('body').on('click', '.js-back-to-top-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const speed = $this.attr('data-speed') ?? 5000;


        $('html, body').animate({
            scrollTop: 0
        }, Number(speed));
    })

})(jQuery)
