(function ($) {
    "use strict"


    $('body').on('click', '.js-content-delete-action', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const $this = $(this);
        const href = $this.attr('href');
        const itemId = $this.attr('data-item');
        const itemType = $this.attr('data-item-type');


        const bodyHtml = `
                <div class="d-flex-center flex-column text-center pt-32">
                    <div class="d-flex-center size-72 rounded-16 bg-gray">
                        <div class="d-flex-center size-64 rounded-16 bg-danger">${bulDangerIcon}</div>
                    </div>

                    <h4 class="font-14 text-dark mt-8">${deleteRequestTitleLang}</h4>
                </div>

                <div class="mt-16 p-12 rounded-12 border-gray-200 font-12 text-gray-500">${deleteRequestDescriptionLang}</div>

                <div class="js-delete-content-form mt-24" data-action="/panel/content-delete-request">
                    <input type="hidden" name="item_id" value="${itemId}">
                    <input type="hidden" name="item_type" value="${itemType}">

                    <div class="form-group">
                        <label class="form-group-label bg-white">${requestDetailsLang}</label>
                        <textarea name="description" class="js-ajax-description form-control bg-white" rows="5"></textarea>
                        <div class="invalid-feedback font-14"></div>
                    </div>
                </div>`;

        const $footerHtml = `<div class="d-flex align-items-center gap-24 justify-content-end">
                <button type="button" class="close-swl btn btn-transparent">${deleteAlertCancel}</button>
                <button type="button" class="js-send-delete-request btn btn-danger">${sendRequestLang}</button>
            </div>`;

        const html = makeModalHtml(deleteRequestLang, closeIcon, bodyHtml, $footerHtml)

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '37rem',
        })
    });


    $('body').on('click', '.js-send-delete-request', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.js-custom-modal').find('.js-delete-content-form');
        let data = serializeObjectByTag(form);
        let action = form.attr('data-action');

        $this.addClass('loadingbar primary').prop('disabled', true);
        form.find('textarea').removeClass('is-invalid');

        $.post(action, data, function (result) {
            if (result && result.code === 200) {
                //window.location.reload();
                Swal.fire({
                    icon: 'success',
                    title: result.title,
                    html: '<p class="font-16 text-center text-gray">' + result.msg + '</p>',
                    showConfirmButton: false,
                    width: '25rem',
                });

                setTimeout(() => {
                    window.location.reload();
                }, 1500)
            }
        }).fail(err => {
            $this.removeClass('loadingbar primary').prop('disabled', false);
            var errors = err.responseJSON;

            if (errors && errors.errors) {
                Object.keys(errors.errors).forEach((key) => {
                    const error = errors.errors[key];
                    let element = form.find('.js-ajax-' + key);

                    element.addClass('is-invalid');
                    element.parent().find('.invalid-feedback').text(error[0]);
                });
            }
        })
    });


})(jQuery)
