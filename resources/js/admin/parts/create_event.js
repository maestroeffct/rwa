(function ($) {
    "use strict"

    $(document).ready(function () {

        if (jQuery().summernote) {
            makeSummernote($('#summernote'), 400)
        }
    })

    /*
    * Actions
    * */

    $('body').on('click', '.js-form-action-btn', function (e) {
        e.preventDefault();
        const $this = $(this)
        const $form = $this.closest('form');

        const status = $this.attr("data-status") ?? 'draft';

        $form.find('input[name="status"]').val(status)

        $form.trigger('submit');
    });


    // Video Demo
    $('body').on('change', '.js-video-demo-source', function (e) {
        e.preventDefault();

        const value = $(this).val();

        const $otherSources = $('.js-video-demo-other-inputs');
        const $fileInput = $('.js-video-demo-file-input');

        if (value === "secure_host" || value === "s3") {
            $otherSources.addClass('d-none');
            $fileInput.removeClass('d-none');

        } else {
            $otherSources.removeClass('d-none');
            $fileInput.addClass('d-none');

            const $filePathUploadButton = $('.js-video-demo-path-input .js-video-demo-path-upload');
            const $filePathLinkButton = $('.js-video-demo-path-input .js-video-demo-path-links');
            const $filePathInput = $('.js-video-demo-path-input input');

            $filePathUploadButton.addClass('d-none');
            $filePathLinkButton.addClass('d-none');

            if (value === 'upload') {
                $filePathUploadButton.removeClass('d-none');
            } else {
                $filePathLinkButton.removeClass('d-none');
            }

            if (videoDemoPathPlaceHolderBySource) {
                $filePathInput.attr('placeholder', videoDemoPathPlaceHolderBySource[value]);
            }
        }
    });


    /*********
     * Categories
     * */
    $('body').on('change', '#categories', function (e) {
        e.preventDefault();
        let category_id = this.value;
        $.get(adminPanelPrefix + '/filters/get-by-category-id/' + category_id, function (result) {

            if (result && typeof result.filters !== "undefined" && result.filters.length) {
                let html = '';
                Object.keys(result.filters).forEach(key => {
                    let filter = result.filters[key];
                    let options = [];

                    if (filter.options.length) {
                        options = filter.options;
                    }

                    html += '<div class="col-12 col-md-3">\n' +
                        '<div class="webinar-category-filters">\n' +
                        '<strong class="category-filter-title d-block mb-16">' + filter.title + '</strong>\n';

                    if (options.length) {
                        Object.keys(options).forEach(index => {
                            let option = options[index];

                            html += '<div class="form-group mt-8 mb-0 d-flex align-items-center justify-content-between">\n' +
                                '<label class="text-gray-500 font-14 mb-0" for="filterOption' + option.id + '">' + option.title + '</label>\n' +
                                '<div class="custom-control custom-checkbox">\n' +
                                '<input type="checkbox" name="filters[]" value="' + option.id + '" class="custom-control-input" id="filterOption' + option.id + '">\n' +
                                '<label class="custom-control-label" for="filterOption' + option.id + '"></label>\n' +
                                '</div>\n' +
                                '</div>\n';
                        })
                    }

                    html += '</div></div>';
                });

                $('#categoriesFiltersContainer').removeClass('d-none');
                $('#categoriesFiltersCard').html(html);
            } else {
                $('#categoriesFiltersContainer').addClass('d-none');
                $('#categoriesFiltersCard').html('');
            }
        })
    });

    /*********
     * Options
     * */
    $('body').on('change', '#enableCountdownSwitch', function () {
        const $filed = $('.js-countdown-time-reference-field');

        if (this.checked) {
            $filed.removeClass('d-none')
        } else {
            $filed.addClass('d-none')
        }
    })


    /*********
     * Contents
     * */
    function handleContentsModal(path, title) {
        handleBasicModal(path, title, function (result, $body, $footer) {

            const $iconsSelect2 = $body.find('.js-make-icons-select2');
            if ($iconsSelect2.length) {
                handleChooseIconSelect2($iconsSelect2)
            }

            const $elSelect2 = $body.find('.js-make-item-select2');
            if ($elSelect2.length) {
                handleSelect2($elSelect2)
            }

            const $searchSelect2 = $body.find('.js-make-search-select2');
            if ($searchSelect2.length) {
                handleSearchableSelect($searchSelect2)
            }

            /// Tags
            var $inputTags = $body.find('.inputtags');
            if ($inputTags.length) {
                handleBootstrapTags($inputTags)
            }

            const $dateRangePicker = $('.date-range-picker');
            if ($dateRangePicker.length) {
                makeDateRangePicker($dateRangePicker, "up")
            }

            const $footerHtml = `<div class="d-flex align-items-center justify-content-end gap-8">
                <button type="button" class="js-save-event-content-btn btn btn-primary">${saveLang}</button>
                <button type="button" class="close-swl btn btn-danger">${closeLang}</button>
            </div>`;
            $footer.html($footerHtml)

        }, '', '54rem')
    }

    $('body').on('click', '.js-event-contents-action-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const title = $this.attr('data-title');
        const path = $this.attr('data-path');


        handleContentsModal(path, title)
    })

    $('body').on('change', '.js-event-content-locale', function () {
        const $this = $(this);
        const title = $this.attr('data-title');

        const $form = $this.closest('form');
        let path = $form.attr('action')
        path = path.replaceAll('update', 'edit')
        path = path + "?locale=" + $(this).val()

        handleContentsModal(path, title)
    })

    $('body').on('click', '.js-save-event-content-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-custom-modal').find('form');
        const path = $form.attr('action');

        handleSendRequestItemForm($form, $this, path)
    })

    $('body').on('click', '.js-get-faq-description', function (e) {
        e.preventDefault();
        const $this = $(this);
        const answer = $this.parent().find('input').val();

        var html = '<div class="p-16">' + answer + '</div>';

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '30rem',
        });
    });


})(jQuery)
