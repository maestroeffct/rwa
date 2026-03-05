(function ($) {
    "use strict"

    $('body').on('change', '.js-create-property-images', function (event) {
        const $this = $(this);
        const imgSrc = URL.createObjectURL(event.target.files[0]);
        const divCol = $this.attr('data-col')
        const inputName = $this.attr('name')
        const parentId = $this.attr('data-parent-id')
        const parent = $this.closest('.create-media-card');

        const html = `<div class="create-media-card__img d-flex align-items-center justify-content-center w-100 h-100">
                                <img src="${imgSrc}" alt="" class="img-cover rounded-15">

                                <a href="#!" class="js-product-images-delete create-media-card__delete-btn d-flex align-items-center justify-content-center">
                                    <span class="d-flex align-items-center justify-content-center p-4">
                                        ${dangerCloseIcon}
                                    </span>
                                </a>
                            </div>`;

        parent.find('.create-media-card__label').remove();
        parent.append(html);

        const random = randomString(5);

        const labelHtml = `<div class="${divCol} mt-12 js-product-image-col">
                <div class="create-media-card position-relative p-4">
                    <label for="images_${random}" class="create-media-card__label w-100 h-100 rounded-15 flex-column align-items-center justify-content-center cursor-pointer">
                        <div class="create-media-card__circle d-flex align-items-center justify-content-center rounded-circle">
                            ${directSendIcon}
                        </div>

                        <div class="mt-8 font-12 text-primary">${uploadAnImageLang}</div>
                    </label>

                    <input type="file" name="${inputName}" id="images_${random}" class="js-create-property-images" data-col="${divCol}" data-parent-id="${parentId}" accept="image/*">
                </div>
            </div>`;

        $('#' + parentId).append(labelHtml);
    })

    function handleSpecificationTags(elClassName) {
        if (jQuery().tagsinput) {
            var input_tags = $('.' + elClassName);
            input_tags.tagsinput({
                tagClass: 'badge badge-primary py-5',
                maxTags: (input_tags.data('max-tag') ? input_tags.data('max-tag') : 10),
            });
        }
    }


    $('body').on('change', '.js-product-content-locale', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $(this).closest('.js-content-form');
        const locale = $this.val();
        const productId = $this.attr('data-product-id');
        const item_id = $this.attr('data-id');
        const relation = $this.attr('data-relation');
        let fields = $this.attr('data-fields');
        fields = fields.split(',');


        $this.addClass('loadingbar gray');

        const path = '/panel/store/products/' + productId + '/getContentItemByLocale';
        const data = {
            item_id,
            locale,
            relation
        };

        $.post(path, data, function (result) {
            if (result && result.item) {
                const item = result.item;

                Object.keys(item).forEach(function (key) {
                    console.log(key)
                    const value = item[key];

                    if ($.inArray(key, fields) !== -1) {
                        let elKey = key;

                        if (relation === 'selectedSpecifications') {
                            elKey = 'tags';

                            if (item.type === 'textarea') {
                                elKey = 'summary';
                            }
                        }

                        let element = $form.find('.js-ajax-' + elKey);

                        if (elKey === 'tags') {
                            element.tagsinput('destroy');
                        }

                        element.val(value);

                        if (elKey === 'tags') {
                            const randomClass = 'tags-' + randomString();

                            element.addClass(randomClass)
                            handleSpecificationTags(randomClass);
                        }
                    }
                });

                $this.removeClass('loadingbar gray');
            }
        }).fail(err => {
            $this.removeClass('loadingbar gray');
        });
    });


    /*********
    * Specifications
    * */

    function handleSpecificationInputType($form, specificationId) {
        const $loadingCard = $form.find('.js-specification-extra-fields-loading');
        const $contentCard = $form.find('.js-specification-extra-fields');

        $loadingCard.removeClass("d-none");
        $contentCard.addClass("d-none");

        $.get('/panel/store/products/specifications/' + specificationId + '/get', function (result) {
            if (result) {
                $loadingCard.addClass("d-none");
                $contentCard.removeClass("d-none");

                const {specification, multiValues} = result;

                const multiValuesInput = $form.find('.js-multi-values-input');
                const summaryInput = $form.find('.js-summery-input');
                const allowSelectionInput = $form.find('.js-allow-selection-input');

                $form.find('.js-input-type').val(specification.input_type);

                allowSelectionInput.find('input').prop('checked', false);

                if (specification.input_type === 'multi_value') {
                    multiValuesInput.removeClass('d-none');
                    allowSelectionInput.removeClass('d-none');
                    summaryInput.addClass('d-none');

                    const $select = $form.find('.multi_values-select2');
                    let html='';
                    if (multiValues) {
                        for (const multiValue of multiValues) {
                            html += `<option value="${multiValue.id}">${multiValue.title}</option>`;
                        }
                    }

                    $select.html(html);
                    console.log($select)
                    handleSelect2($select);
                } else {
                    multiValuesInput.addClass('d-none');
                    allowSelectionInput.addClass('d-none');
                    summaryInput.removeClass('d-none');
                }

                allowSelectionInput.find('input').prop('checked', false);
            }
        });
    }


    function handleSpecificationSelect2(elClassName) {
        const el = $('.' + elClassName);

        if (el && el.length) {
            el.select2({
                placeholder: $(this).data('placeholder'),
                //minimumInputLength: 3,
                //allowClear: true,
                /*ajax: {
                    url: '/panel/store/products/specifications/search',
                    dataType: 'json',
                    type: "POST",
                    quietMillis: 50,
                    data: function (params) {
                        return {
                            term: params.term,
                            category_id: el.attr('data-category'),
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.title,
                                    id: item.id,
                                    input_type: item.input_type,
                                }
                            })
                        };
                    }
                }*/
            });

            el.on('change', function (e) {
                const specificationId = e.target.value;
                const $form = $(e.target).closest('.specification-form');

                handleSpecificationInputType($form, specificationId)
            });
        }
    }


    $(document).ready(function () {

        handleSelect2($('.select-multi-values-select2'));

        handleSpecificationSelect2('search-specification-select2');
    })

})(jQuery)
