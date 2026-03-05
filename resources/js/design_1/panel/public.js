(function ($) {
    "use strict"

    $('body').on('click', '.js-show-panel-sidebar', function (e) {
        e.preventDefault();
        const $panelSidebar = $('#panelSidebar');
        $panelSidebar.toggleClass('show-sidebar')
    })
})(jQuery)
