(function ($) {
    "use strict"

    var tabsLoadMore = {}

    $('body').on('click', '.js-topics-load-more-btn', function (e) {
        e.preventDefault();

        const $this = $(this)
        const elId = $this.attr('data-el');
        const path = $this.attr('data-path');
        const $row = $(`#${elId}`)

        if (typeof tabsLoadMore[elId] === "undefined") {
            tabsLoadMore[elId] = {
                page: 1
            }
        }

        const page = tabsLoadMore[elId].page + 1;

        const data = {
            page: page
        }

        $this.addClass('loadingbar').prop('disabled', true);

        $.get(path, data, function (result) {
            if (result) {
                tabsLoadMore[elId].page = page;

                if (result.data) {
                    $row.append(result.data)
                }

                if (!result.has_more_item) {
                    $this.parent().remove()
                }
            }
        }).always(function () {
            $this.removeClass('loadingbar').prop('disabled', false);
        })
    })

    /* Search */
    $('body').on('click', '.js-show-search-drawer', function (e) {
        e.preventDefault()

        const $drawer = $('.forum-search-container');
        $drawer.addClass('show');
    })

    $('body').on('click', '.forum-search-container__mask, .js-close-search-drawer', function (e) {
        e.preventDefault()

        const $drawer = $('.forum-search-container');
        $drawer.removeClass('show');
    })

})(jQuery)
