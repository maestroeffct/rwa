(function ($) {
    "use strict";

    window.handleHighlightWords = function (works, el) {
        const $el = $(`.${el}`);

        const typeSpeed = Number($el.attr('data-type-speed')) ?? 50;
        const backSpeed = Number($el.attr('data-back-speed')) ?? 25;
        const backDelay = Number($el.attr('data-delay')) ?? 1500;

        new Typed(`.${el}`, {
            strings: works,
            typeSpeed: typeSpeed,
            backSpeed: backSpeed,
            backDelay: backDelay,
            fadeOut: true,
            loop: true,
            showCursor: false,
        });
    }

})(jQuery)
