(function () {
    "use strict"

    $(document).ready(function () {
        if ($('.panel-search-user-select2').length) {
            panelSearchUserSelect2();
        }

        const $sidebarScroll = new SimpleBar(document.getElementById('landingBuilderSidebar'));
        const $sidebarActiveItem = $('.edit-component-sidebar__component-card.active');

        if ($sidebarScroll && $sidebarActiveItem.length) {
            $sidebarScroll.getScrollElement().scrollTo({
                top: $sidebarActiveItem.position().top,
                behavior: "smooth"
            });
        }
    });

    $('body').on('click', '.js-show-landing-builder-sidebar', function (e) {
        e.preventDefault();
        const $landingBuilderSidebar = $('#landingBuilderSidebar');
        $landingBuilderSidebar.toggleClass('show-sidebar')
    })


})(jQuery)
