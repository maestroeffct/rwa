(function ($) {
    "use strict";

    function makeAttachmentHtml() {
        const key = randomString(4);

        return `<div class="js-attachment-item-inputs d-flex align-items-center gap-16 position-relative mt-24">
                    <div class="form-group mb-0 flex-1">
                        <label class="form-group-label bg-white">${titleLang}</label>
                        <input type="text" name="attachments[${key}][title]" class="form-control bg-white">
                    </div>

                    <div class="form-group mb-0 flex-1">
                        <label class="form-group-label bg-white">${attachmentLang}</label>
                        <div class="d-flex align-items-center">
                            <div class="custom-file bg-white">
                                <input type="file" name="attachments[${key}][file]" class="custom-file-input bg-white js-ajax-upload-file-input" data-upload-name="attachments[${key}][file]" id="attachmentInput_${key}">
                                <span class="custom-file-text bg-white"></span>
                                <label class="custom-file-label bg-transparent" for="attachmentInput_${key}">
                                    ${uploadIcon}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="js-remove-attachment d-flex-center size-48 bg-danger rounded-12 cursor-pointer">
                        ${closeIcon}
                    </div>
                </div>`;
    }

    $('body').on('click', '.js-add-attachment', function (e) {
        e.preventDefault();

        const html = makeAttachmentHtml();

        const $parent = $(this).closest('form').find('.js-attachments-card');
        $parent.append(html);
    })

    $('body').on('click', '.js-remove-attachment', function (e) {
        e.preventDefault();

        $(this).closest('.js-attachment-item-inputs').remove()
    })

})(jQuery);
