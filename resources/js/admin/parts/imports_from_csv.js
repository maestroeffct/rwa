(function () {
    "use strict";

    $('body').on('change', 'select[name="type"]', function () {
        const $coursesInstructions = $('.js-courses-instructions');
        const $categoriesInstructions = $('.js-categories-instructions');
        const $usersInstructions = $('.js-users-instructions');
        const $productsInstructions = $('.js-products-instructions');

        const value = $(this).val();

        $coursesInstructions.addClass('d-none');
        $categoriesInstructions.addClass('d-none');
        $usersInstructions.addClass('d-none');
        $productsInstructions.addClass('d-none');

        // Show
        $(`.js-${value}-instructions`).removeClass('d-none')
    })


})(jQuery)
