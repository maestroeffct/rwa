(function ($) {
    "use strict"

    // =========
    // Media
    // ======
    $('body').on('change', '.js-create-media-just-image', function (event) {
        const $this = $(this);
        const imgSrc = URL.createObjectURL(event.target.files[0]);

        const parent = $this.closest('.create-media-just-image-card');

        parent.find('.create-media-just-image-card__label').addClass('d-none')
        parent.find('.create-media-just-image-card__img').removeClass('d-none')
        parent.find('.create-media-just-image-card__img img').attr('src', imgSrc)
    })

    $('body').on('click', '.create-media-just-image-card__delete-btn', function (e) {
        e.preventDefault();

        if (!$(this).hasClass('delete-action')) {
            const parent = $(this).closest('.create-media-just-image-card');

            parent.find("input").val("");
            parent.find('.create-media-just-image-card__label').removeClass('d-none')
            parent.find('.create-media-just-image-card__img').addClass('d-none')
            parent.find('.create-media-just-image-card__img img').attr('src', '')
        }
    })


    $('body').on('change', '.js-create-media', function (event) {
        const $this = $(this);
        const file = event.target.files[0];
        const isImage = file.type.match(/^image\//);
        let imgSrc = "";

        if (isImage) {
            imgSrc = URL.createObjectURL(file);
        }

        const page = $this.attr('data-page')
        const createMediaCardClass = $this.attr('data-create-media-card-class')
        const divCol = $this.attr('data-col')
        const inputName = $this.attr('name')
        const labelTitle = $this.attr('data-label')
        const accepts = $this.attr('accept')
        const parentId = $this.attr('data-parent-id')
        const parent = $this.closest('.create-media-card');

        let html = `<div class="create-media-card__img position-relative d-flex align-items-center justify-content-center w-100 h-100">`;

        if (isImage) {
            html += `<img src="${imgSrc}" alt="" class="img-cover rounded-15">`
        } else {
            html += `<div class="create-media-card__filename">${file.name}</div>`
        }

        html += ` <a href="#!" class="js-create-media-delete create-media-card__delete-btn d-flex align-items-center justify-content-center">
                        <span class="d-flex align-items-center justify-content-center p-1">
                            ${dangerCloseIcon}
                        </span>
                    </a>
                </div>`

        parent.find('.create-media-card__label').remove();
        parent.append(html);

        const random = randomString(5);

        const labelHtml = `<div class="${divCol} js-create-media-col">
                <div class="create-media-card position-relative p-1 ${createMediaCardClass ?? ''}">
                    <label for="createMedia_${random}" class="create-media-card__label w-100 h-100 rounded-15 flex-column align-items-center justify-content-center cursor-pointer">
                        <div class="create-media-card__circle d-flex align-items-center justify-content-center rounded-circle">
                            ${directSendIcon}
                        </div>

                        <div class="mt-8 font-12 text-primary">${labelTitle}</div>
                    </label>

                    <input type="file"
                        name="${inputName}"
                        id="createMedia_${random}"
                        class="js-create-media"
                        data-col="${divCol}"
                        data-parent-id="${parentId}"
                        data-label="${labelTitle}"
                        data-page="${page}"
                        data-create-media-card-class="${createMediaCardClass}"
                        accept="${accepts}"
                      >
                </div>
            </div>`;

        $('#' + parentId).append(labelHtml);

        if (page === "panel-conversation") {
            $('.panel-conversation').addClass('has-footer-attachments')
        }
    })

    $('body').on('click', '.js-create-media-delete', function (e) {
        e.preventDefault();

        const $parent = $(this).closest('.js-create-media-col');
        $parent.remove();
    })

})(jQuery)
