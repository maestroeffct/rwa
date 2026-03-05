(function ($) {
    "use strict"

    function getLoadingHtml(size) {
        return `<div class="d-flex-center flex-column py-56">
                    <img src="/assets/default/img/loading.svg" alt="" class="img-fluid" width="${size}px" height="${size}px">
                </div>`
    }

    $('body').on('click', '.js-show-noticeboards', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-path')

        const $drawer = $('.js-noticeboards-drawer');
        const $drawerBody = $drawer.find('#noticeboardsDrawerBody');

        $drawer.addClass('show');
        $drawerBody.html(getLoadingHtml(64))

        $.get(path, function (result) {
            if (result.code === 200) {
                $drawerBody.html(result.html)
            }
        }).fail(function () {
            showToast('error', oopsLang, somethingWentWrongLang);
        })
    })

    $('body').on('click', '.js-noticeboards-drawer-close, .noticeboards-drawer-mask', function (e) {
        e.preventDefault();

        const $drawer = $('.js-noticeboards-drawer');
        $drawer.removeClass('show');
    })

})(jQuery)
