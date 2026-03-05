(function ($) {
    "use strict";

    $('body').on('click', '.js-language-dropdown-item', function () {
        const $this = $(this);
        const value = $this.attr('data-value');
        const title = $this.attr('data-title');
        const flag = $this.find('img').attr('src')
        const parent = $this.closest('.js-language-select');

        parent.find('input[name="locale"]').val(value);
        parent.find('.js-lang-title').text(title);
        parent.find('.language-toggle-flag img').attr('src', flag);

        if (!parent.hasClass('js-dont-submit')) {
            parent.find('form').trigger('submit')
        }
    });

    $('body').on('click', '.js-currency-dropdown-item', function () {
        const $this = $(this);
        const value = $this.attr('data-value');
        const title = $this.attr('data-title');
        const parent = $this.closest('.js-currency-select');

        parent.find('input[name="currency"]').val(value);
        parent.find('.js-lang-title').text(title);

        if (!parent.hasClass('js-dont-submit')) {
            parent.find('form').trigger('submit')
        }
    });

})(jQuery)
