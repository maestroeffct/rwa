(function ($) {
    "use strict";

    $('body').on('click', '.js-show-ai-content-drawer', function (e) {
        e.preventDefault();

        lockBodyScroll(true);

        $('.ai-content-generator-drawer').addClass('show');
    })


    $('body').on('click', '.js-right-drawer-close, .ai-content-generator-drawer-mask', function () {
        $('.ai-content-generator-drawer').removeClass('show')
        lockBodyScroll(false);
    })

    $('body').on('change', 'select[name="service_type"]', function () {
        const value = $(this).val();
        const $text = $('.js-text-templates-field');
        const $image = $('.js-image-templates-field');

        $text.addClass('d-none');
        $image.addClass('d-none');

        if (value === "text") {
            $text.removeClass('d-none');
        }

        if (value === "image") {
            $image.removeClass('d-none');
        }
    })

    $('body').on('change', '.js-text-service-templates', function () {
        const value = $(this).val();
        const $option = $(this).find('option:selected');
        const $lengthField = $('.js-service-length-field');
        const $forServiceField = $('.js-for-service-fields');
        const $questionField = $('.js-question-field');

        $questionField.addClass('d-none');
        $forServiceField.addClass('d-none');
        $lengthField.addClass('d-none');
        $lengthField.find('input').val('')

        if ($option.attr('data-enable-length') === "yes") {

            $lengthField.removeClass('d-none');

            const length = $option.attr('data-length');
            if (length && length > 0) {
                $lengthField.find('input').attr('max', length)
            }
        }

        if (value === "custom_text") {
            $questionField.removeClass('d-none');
        } else {
            $forServiceField.removeClass('d-none');
        }
    })


    $('body').on('change', '.js-image-service-templates', function () {
        const value = $(this).val();
        const $imageSizeField = $('.js-image-size-field');
        const $imageKeywordField = $('.js-image-keyword-field');
        const $imageQuestionField = $('.js-image-question-field');

        $imageSizeField.addClass('d-none');
        $imageSizeField.find('select').val('')
        $imageKeywordField.addClass('d-none');
        $imageKeywordField.find('input').val('')
        $imageQuestionField.addClass('d-none');
        $imageQuestionField.find('input').val('')

        if (value === "custom_image") {
            $imageSizeField.removeClass('d-none');
            $imageQuestionField.removeClass('d-none');
        } else {
            $imageKeywordField.removeClass('d-none');
        }
    })


    $('body').on('keyup', '.js-service-length-field input', function () {
        const $this = $(this);

        const value = $this.val();
        const max = $this.attr('max');
        const error = $this.attr('data-max-error');

        const $errorDiv = $this.closest('.form-group').find('.invalid-feedback')

        $this.removeClass('is-invalid');
        $errorDiv.text('');

        if (Number(value) > Number(max)) {
            $this.val(max)

            $this.addClass('is-invalid');
            $errorDiv.text(`${error} ${max}`);
        }
    })

    $('body').on('click', '.js-submit-ai-content-form', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('form');
        const action = $form.attr('action');
        const data = $form.serializeObject();

        $this.addClass('loadingbar primary').prop('disabled', true);

        const $textCard = $('#generatedTextContents');
        const $imagesCard = $('.js-image-generated');
        $imagesCard.addClass('d-none');
        $textCard.addClass('d-none');


        $.post(action, data, function (result) {

            if (result.code === 200) {
                if (result.data) {
                    if (result.data.contents && Object.keys(result.data.contents).length) {
                        $textCard.removeClass('d-none');
                        $textCard.html('')

                        for (const content of result.data.contents) {
                            const html = makeTextContentHtml(content);

                            $textCard.append(html);
                        }
                    }

                    if (result.data.images && Object.keys(result.data.images).length) {
                        $imagesCard.removeClass('d-none');
                        $imagesCard.html('');

                        let imageSize = "";
                        if (typeof data.image_size !== "undefined") {
                            imageSize = `${data.image_size}x${data.image_size}`;
                        }

                        for (const image of result.data.images) {
                            const html = makeImageContentHtml(image, imageSize);
                            $imagesCard.append(html);
                        }
                    }
                }
            }

            $this.removeClass('loadingbar primary').prop('disabled', false);
        }).fail(err => {
            $this.removeClass('loadingbar primary').prop('disabled', false);

            const errors = err.responseJSON;

            if (errors && errors.errors) {
                Object.keys(errors.errors).forEach((key) => {
                    const error = errors.errors[key];
                    let element = $form.find('.js-ajax-' + key);

                    element.addClass('is-invalid');
                    element.parent().find('.invalid-feedback').text(error[0]);
                });
            }
        })


    })


    function makeImageContentHtml(imageLink, imageSize = null) {
        return `<div class="d-flex align-items-center justify-content-between mt-16 rounded-16 p-12 border-gray-300 bg-white">
            <div class="d-flex align-items-center">
                <div class="size-80 rounded-8">
                    <img src="${imageLink}" alt="image" class="img-cover rounded-8">
                </div>

                <div class="ml-8">
                    <h5 class="font-14 font-weight-bold text-gray-500">${generatedImageLang}</h5>
                    ${imageSize ? `<p class="mt-8 font-12 text-gray-500">${imageSize}</p>` : ''}
                </div>
            </div>

            <a href="${imageLink}" target="_blank" class="d-flex-center size-40 rounded-circle bg-gray-100">
                ${downloadIcon}
            </a>
        </div>`
    }

    function makeTextContentHtml(text) {
        return `<div class="js-text-generated mt-16 p-16 rounded-16 bg-gray-100 border-gray-300">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="font-14 text-gray-500 font-weight-bold">${generatedContentLang}</h4>

                <div class="d-flex-center js-copy-content cursor-pointer" data-toggle="tooltip" data-placement="top" title="${copyLang}" data-copy-text="${copyLang}" data-done-text="${doneLang}">
                    ${copyIcon}
                </div>
            </div>

            <div class="mt-8 font-14 text-gray-500 js-content">${text}</div>
        </div>`
    }

    var copyTimeout;

    $('body').on('click', '.js-copy-content', function (e) {
        e.preventDefault();

        const $this = $(this);
        const copyText = $this.attr('data-copy-text');
        const doneText = $this.attr('data-done-text');
        const contentToCopy = $this.closest('.js-text-generated').find('.js-content').text();
        const tempTextarea = $('<textarea>').val(contentToCopy).appendTo('body').select();

        document.execCommand('copy');

        // Remove the temporary textarea
        tempTextarea.remove();

        $this.attr('data-original-title', doneText)
            .tooltip('show');
        $this.attr('data-original-title', copyText);
    });

    $('body').on('click', '.js-view-content', function (e) {
        e.preventDefault();

        const $parent = $(this).parent();

        const prompt = $parent.find('.js-prompt').val() ?? null;
        let result = $parent.find('.js-result').val();

        let html = '';


        if (prompt) {
            html += `<div class="js-prompt-card mb-16 pb-16 border-bottom-gray-200">
                        <h6 class="font-weight-bold font-14">${promptLang}</h6>
                        <p class="text-gray-500 font-12 mt-4">${prompt}</p>
                    </div>`
        }

        if (result) {
            result = JSON.parse(result);

            if (result.contents && Object.keys(result.contents).length) {

                for (const content of result.contents) {
                    html += makeTextContentHtml(content);
                }
            }

            if (result.images && Object.keys(result.images).length) {

                for (const image of result.images) {
                    html += makeImageContentHtml(image)
                }
            }
        }

        Swal.fire({
            html: makeModalHtml(generatedContentLang, closeIcon, html, '&nbsp;'),
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '48rem',
            didOpen: () => {
                const $body = $('.js-custom-modal');
                const $footer = $('.custom-modal-footer');

            }
        });
    })

})(jQuery)
