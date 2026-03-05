(function ($) {
    "use strict";

    function formatIcon(icon) {

        if (icon && icon.selected) {
            const $element = $(icon.element);

            icon.svg = $element.attr('data-svg')
        } else if (!icon || typeof icon.id === "undefined") {
            return ""
        } else if (icon.id === "" && icon.text) {
            return icon.text;
        }

        return $(`<div class="d-flex align-items-center gap-8">
                    ${icon.svg}
                    <span class="">${icon.text}</span>
                </div>`)
    }


    window.handleChooseIconSelect2 = function ($iconsSelect2) {

        if ($iconsSelect2.length) {
            const dropdownParent = $iconsSelect2.attr('data-dropdown-parent') ?? 'body'

            $iconsSelect2.select2({
                templateResult: formatIcon,
                templateSelection: formatIcon,
                allowClear: true,
                width: '100%',
                minimumInputLength: 3,
                dropdownParent: $(dropdownParent),
                ajax: {
                    url: "/iconsax/search",
                    dataType: 'json',
                    type: "POST",
                    quietMillis: 50,
                    data: function (params) {
                        return {
                            name: params.term,
                        };
                    },
                    processResults: data => ({
                        results: data.results,
                    })
                }
            });
        }
    }

})(jQuery)
