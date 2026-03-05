(function ($) {
    "use strict"

    function handleSelectedCategoriesHtml(id, text) {
        return `<div class="js-selected-filter-parent d-inline-flex-center bg-gray-100 p-8 rounded-8 text-gray-500">
                    <input type="hidden" name="skills[]" value="${id}">
                    <div class="js-remove-selected-filter d-flex-center size-16 rounded-4 bg-gray-400 cursor-pointer">${selectedCloseIcon}</div>
                    <span class="ml-8">${text}</span>
                </div>`
    }

    function checkCanSelectCategory(id) {
        const check = $(`input[value="${id}"]`);

        return (check.length < 1)
    }

    $('body').on('change', '.js-skills-select', function (e) {
        const $this = $(this);
        const $selectedOption = $this.find('option:selected');
        const $selectedCategories = $('.js-selected-category-filters');
        const text = $selectedOption.text();
        const id = $selectedOption.attr("value");

        if ($selectedCategories.length && text && id) {

            if (checkCanSelectCategory(id)) {
                $selectedCategories.append(handleSelectedCategoriesHtml(id, text));
            }

            $this.val("");
            $this.trigger("change")
        }
    })

    $('body').on('click', '.js-remove-selected-filter', function (e) {
        e.preventDefault();

        $(this).closest('.js-selected-filter-parent').remove();
    })

})(jQuery)
