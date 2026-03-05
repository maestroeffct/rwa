(function ($) {
    "use strict"


    $(document).ready(function () {
        var counterupStarted = false;

        $('.statistics-section').each(function () {
            const $section = $(this);
            const $counters = $section.find('.js-statistic-value-counterup');

            $(window).on('scroll', function () {
                const sectionOffset = $section.offset().top;
                const windowHeight = $(window).scrollTop() + $(window).height();

                if (!counterupStarted && windowHeight > (sectionOffset + 70)) {
                    counterupStarted = true;

                    $counters.countTo();
                }
            });
        });


    });

})(jQuery)
