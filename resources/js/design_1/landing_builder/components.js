require('./../app_includes/icon_picker');

(function ($) {
    "use strict";

    $(document).ready(function () {
        const select2Els = $('.js-make-select2');

        if (select2Els && select2Els.length) {
            handleSelect2(select2Els)
        }


        const $iconsSelect2 = $('.js-icons-select2');
        if ($iconsSelect2.length) {
            handleChooseIconSelect2($iconsSelect2)
        }
    })


    function handleSearchableSelects($parent = null) {
        let searchableSelect;
        let select2Els;

        if ($parent) {
            searchableSelect = $parent.find('.js-searchable-select');
            select2Els = $parent.find('.js-make-select2-item');

            const $iconsSelect2 = $parent.find('.js-make-icons-select2');
            if ($iconsSelect2.length) {
                handleChooseIconSelect2($iconsSelect2)
            }
        } else {
            searchableSelect = $('.js-searchable-select');
        }

        if (select2Els && select2Els.length) {
            handleSelect2(select2Els)
        }

        if (searchableSelect && searchableSelect.length) {
            handleSearchableSelect(searchableSelect)
        }
    }


    $('body').on('click', '.js-add-component-to-landing', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $assignedComponents = $('.js-assigned-components-lists');
        const $assignedComponentsLoading = $('.js-assigned-components-lists-loading');
        const $assignedComponentsNoItems = $('.js-assigned-components-lists-no-items');

        $assignedComponentsLoading.removeClass('d-none')

        const componentId = $this.attr('data-item');
        const landingId = $this.attr('data-landing');

        const path = `${landingBuilderPrefixUrl}/${landingId}/components/add`;
        const data = {
            component_id: componentId,
        }

        $.post(path, data, function (result) {
            if (result.code === 200) {
                $assignedComponentsNoItems.remove();

                $assignedComponents.append(result.html)
            }

            $assignedComponentsLoading.addClass('d-none');

            showToast('success', result.title, result.msg);
        }).fail(function (err) {
            showToast('error', oopsLang, somethingWentWrongLang);

            $assignedComponentsLoading.addClass('d-none')
        })
    })

    // Addable Items
    $('body').on('click', '.js-addable-items-add-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $parent = $this.closest('.js-addable-items');
        const $itemsCard = $parent.find('.js-addable-items-lists');

        let name = $parent.attr('data-name');
        const label = $parent.attr('data-label');
        const placeholder = $parent.attr('data-placeholder');

        name = name.replaceAll('record', randomString())
        name = name.replaceAll('nabat', randomString());

        const clone = $parent.find('.js-addable-main-row').clone();
        let html = clone.prop('innerHTML');
        html = html.replaceAll('__l', label);
        html = html.replaceAll('__n', name);
        html = html.replaceAll('__p', placeholder);
        html = html.replaceAll('__i', closeIcon);

        const randomStr = randomString();
        $itemsCard.append(`<div class="js-addable-items-${randomStr}">${html}</div>`);

        const $searchableSelect = $(`.js-addable-items-${randomStr}`).find('.js-addable-item-make-select-2');

        if ($searchableSelect && $searchableSelect.length) {
            handleSearchableSelect($searchableSelect)
        }
    });


    $('body').on('click', '.js-addable-items-remove-btn', function (e) {
        e.preventDefault();
        $(this).closest('.js-addable-item-card').remove()
    });
    // End Addable Items

    $('body').on('change', '.js-select-change-for-action', function (e) {
        e.preventDefault();
        const $this = $(this);
        const value = $this.val();
        const elClass = $this.attr('data-change-action')
        const parentClass = $this.attr('data-change-parent')

        if (elClass) {
            let $parent = $('body');

            if (parentClass) {
                $parent = $this.closest(`.${parentClass}`);
            }

            const $fields = $parent.find(`.${elClass}`);
            const $field = $parent.find(`.js-select-change-for-action-field-${value}`);

            $fields.addClass('d-none');
            $fields.find('input').val('')

            $field.removeClass('d-none')
        }
    });

    // Addable Accordions
    $('body').on('click', '.js-addable-accordions-add-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        const mainRow = $this.attr('data-main-row');
        const $parent = $this.closest('.js-addable-accordions');
        const $itemsCard = $parent.find('.js-addable-accordions-lists');

        if (mainRow) {
            const randomStr = randomString();

            const $mainRow = $(`.${mainRow}`);
            const clone = $mainRow.clone();
            let html = clone.prop('innerHTML');
            html = html.replaceAll('record', randomStr);

            $itemsCard.append(`<div class="js-addable-accordion-${randomStr}">${html}</div>`);

            handleSearchableSelects($(`.js-addable-accordion-${randomStr}`));

            handleAccordionCollapse();
        }
    });

    $('body').on('click', '.js-addable-accordion-remove-btn', function (e) {
        e.preventDefault();
        $(this).closest('.accordion').remove()
    });

    // Video Content
    $('body').on('change', '.js-video-source-select', function (e) {
        e.preventDefault();
        const $this = $(this);
        const value = $this.val();
        const parent = $this.closest('.js-video-sources-parent');

        const $onlines = parent.find('.js-video-sources-online-field');
        const $uploads = parent.find('.js-video-sources-upload-field');

        if ($.inArray(value, ['youtube', 'vimeo', 'external', 'iframe']) !== -1) {
            $onlines.removeClass('d-none');
            $uploads.addClass('d-none');
        } else {
            $onlines.addClass('d-none');
            $uploads.removeClass('d-none');
        }
    });

    $('body').on('change', '.js-filter-landing-builder-components', function (e) {
        e.preventDefault();
        const $this = $(this);
        const category = $this.val();

        const $card = $('.js-general-components-lists');
        $card.find('.js-landing-builder-component-card').addClass('d-flex').removeClass('d-none')

        if (category) {
            $card.find('.js-landing-builder-component-card').removeClass('d-flex').addClass('d-none')
            $card.find(`.js-landing-builder-component-item_${category}`).addClass('d-flex').removeClass('d-none')
        }
    })

    $('body').on('click', '.js-view-landing-component', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-path');
        const title = $this.attr('data-title');

        handleBasicModal(path, title, function (result, $body, $footer) {
            $footer.remove();
        }, '', '84rem')
    })

})(jQuery)
