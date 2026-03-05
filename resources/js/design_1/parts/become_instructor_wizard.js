(function ($) {
    "use strict"

    $('body').on('change', '.js-user-bank-input', function (e) {
        e.preventDefault();

        const $optionSelected = $(this).find("option:selected");
        const specifications = $optionSelected.attr('data-specifications')

        const $card = $('.js-bank-specifications-card');
        let html = '';

        if (specifications) {
            Object.entries(JSON.parse(specifications)).forEach(([index, item], key) => {

                html += '<div class="form-group">\n' +
                    '         <label class="form-group-label bg-white">' + item + '</label>\n' +
                    '         <input type="text" name="bank_specifications[' + index + ']" value="" class="form-control bg-white"/>\n' +
                    ' </div>'
            })
        }

        $card.html(html);
    });

    $('body').on('change', 'input[name="role"]', function () {
        const value = $(this).val();
        const type = (value === "teacher") ? "become_instructor" : "become_organization";

        $.post('/become-instructor/form-fields', {type: type}, function (result) {
            if (result) {
                $('.js-form-fields-card').html(result.html);

                formsDatetimepicker();
            }
        })
    })


})(jQuery)
