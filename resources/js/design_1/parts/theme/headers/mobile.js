(function ($) {
    "use strict"

    $('body').on('click', '.js-mobile-header-show-specific-drawer', function (e) {
        e.preventDefault();
        const $this = $(this);
        const item = $this.attr('data-drawer');

        const $drawer = $(`.mobile-${item}-drawer`);

        if ($drawer.length) {
            $drawer.addClass('show')
        }
    })

    $('body').on('click', '.theme-header-mobile__drawer-back-drop, .js-close-header-drawer', function (e) {
        e.preventDefault();
        const $this = $(this);

        const $drawer = $this.closest('.theme-header-mobile__drawer');

        if ($drawer.length) {
            const $drawerBody = $drawer.find('.theme-header-mobile__drawer-body');
            $drawerBody.addClass('slide-down');

            setTimeout(function () {
                $drawer.removeClass('show');
                $drawerBody.removeClass('slide-down');
            }, 250)
        }
    })

    $('body').on('click', '.js-close-header-main-menu-drawer', function (e) {
        e.preventDefault();
        const $this = $(this);

        const $drawer = $this.closest('.mobile-main-menu-drawer');

        if ($drawer.length) {
            $drawer.removeClass('show');
        }
    })
})(jQuery)
