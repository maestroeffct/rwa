(function ($) {
    "use strict"


    function handleAttachmentHtml(key) {
        return `<div class="js-attachment-item-card d-flex align-items-center gap-12 mt-28">
                <div class="form-group custom-input-file mb-0 flex-1">
                    <label class="form-group-label bg-white">${attachmentLang}</label>

                    <div class="custom-file bg-white">
                        <input type="file" name="attachments[${key}]" class="custom-file-input" id="attachments_${key}" >
                        <span class="custom-file-text text-gray-500"></span>
                        <label class="custom-file-label bg-transparent" for="attachments_${key}">
                            ${exportIcon}
                        </label>
                    </div>
                </div>

                <button type="button" class="js-remove-attachment d-flex-center bg-danger border-gray-300 size-48 rounded-12">
                    ${trashIcon}
                </button>
            </div>`;
    }


    $('body').on('click', '.js-add-attachment', function (e) {
        e.preventDefault();

        const $card = $('.js-attachments-items');
        const key = randomString();

        const html = handleAttachmentHtml(key);
        $card.append(html);
    })

    $('body').on('click', '.js-remove-attachment', function (e) {
        e.preventDefault();

        $(this).closest('.js-attachment-item-card').remove();
    })


    $('body').on('click', '.js-remove-topic-attachment', function (e) {
        e.preventDefault();

        const $this = $(this);
        const path = $this.attr('data-path');


    })

})(jQuery)
