(function ($) {
    "use strict";

    $('body').on('click', '.js-mark-as-released', function (e) {
        e.preventDefault();
        const $this = $(this);
        const upcomingId = $this.attr('data-id');

        const path = `/panel/upcoming_courses/${upcomingId}/assign-course`;

        handleBasicModal(path, assignPublishedCourseLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="close-swl btn btn-sm btn-danger mr-8">${closeLang}</button>
                <button type="button" class="js-save-assign-course btn btn-sm btn-primary">${saveLang}</button>
            </div>`;
            $footer.html(footerHtml);


            $body.find(".js-select2").select2({
                width: '100%',
                dropdownParent: $('#upcomingAssignCourseModal'),
            });

        }, '', '40rem')
    });

    $('body').on('click', '.js-save-assign-course', function (e) {
        e.preventDefault();
        const $this = $(this);

        let form = $this.closest('.js-custom-modal').find('#upcomingAssignCourseModal');

        handleSendRequestItemForm(form, $this)
    });


})(jQuery);
