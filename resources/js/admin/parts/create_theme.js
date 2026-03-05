(function ($) {
    "use strict"

    $('body').on('change', 'select[name="home_landing_id"]', function (e) {
        e.preventDefault();
        const landingId = $(this).val();

        if (landingId) {
            const path = `${adminPanelPrefix}/themes/get-home-landing-components`;
            const data = {
                landing_id: landingId
            }

            $('.js-home-landing-sections').removeClass('d-none');

            const loadingHtml = '<div class="d-flex align-items-center justify-content-center my-50 "><img src="/assets/default/img/loading.gif" width="48px" height="48px"></div>';

            const $componentsLists = $('.js-assigned-components-lists');
            $componentsLists.html(loadingHtml)

            $.post(path, data, function (result) {
                if (result.code === 200) {
                    $componentsLists.html(result.html)
                    $componentsLists.attr("data-path", result.sort_url)
                }
            })
        } else {
            $('.js-home-landing-sections').addClass('d-none');
        }
    })
})(jQuery)
