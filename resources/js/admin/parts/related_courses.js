(function ($) {
    "use strict";


    $('body').on('click', '#addRelatedCourse, .js-edit-related-course', function (e) {
        e.preventDefault();
        const path = $(this).attr('data-path')
        const title = $(this).attr('data-title')

        handleBasicModal(path, title, function (result, $body, $footer) {

            const $searchSelect2 = $('.related-course-select2');
            if ($searchSelect2.length) {
                handleSearchableSelect($searchSelect2)
            }

            handleSearchableSelect2('search-product-select2', adminPanelPrefix + '/store/products/search', 'title');

            const $footerHtml = `<div class="d-flex align-items-center justify-content-end gap-8">
                <button type="button" class="js-save-relate-course-btn btn btn-primary">${saveLang}</button>
                <button type="button" class="close-swl btn btn-danger">${closeLang}</button>
            </div>`;
            $footer.html($footerHtml)

        }, '', '36rem')
    });


    $('body').on('click', '.js-save-relate-course-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        let $form = $('.js-custom-modal').find('.js-related-course-form');
        const path = $form.attr('data-action');

        handleSendRequestItemForm($form, $this, path)
    });
})(jQuery);
