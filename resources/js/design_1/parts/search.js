(function ($) {
    "use strict";

    $('body').on('click', '.js-content-anchor', function (e) {
        e.preventDefault();

        const id = $(this).attr("data-anchor-id");
        const $section = $(`#${id}`);

        if ($section.length > 0) {
            $('html, body').animate({
                scrollTop: $section.offset().top - 30
            }, 1000);
        }
    })
})(jQuery)
