(function ($) {
    "use strict";

    $('body').on('click', '.js-show-note', function (e) {
        e.preventDefault();
        const $this = $(this);
        const note = $this.parent().find('input').val();

        const html = `<div class="p-16">
            <div class="section-title d-flex align-items-center">
                <h3 class="section-title__heading">${noteLang}</h3>
            </div>

            <p class="text-gray-500 mt-20">${note}</p>
        </div>`;

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '40rem',
        });


    });

})(jQuery);
