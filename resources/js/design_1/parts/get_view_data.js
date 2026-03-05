(function ($) {
    "use strict";

    function handleTableIsLoading() {
        return `<div class="d-flex align-items-center js-view-data-loading">
                <img src="/assets/design_1/img/loading.svg" alt="loading" class="size-40">
                <span class="ml-4 text-gray-500">${loadingDataPleaseWaitLang}</span>
            </div>`
    }

    window.getViewData = function ($container, data, $btn = null, callback = undefined) {
        const $tbody = $container.attr('data-body') ? $container.find($container.attr('data-body')) : $container.find('table tbody');
        const $pagination = $container.find('#pagination');

        let scrollTo = true;

        if ($btn && $btn.hasClass('view-data-without-scroll')) {
            scrollTo = false;
        }

        /* Scroll to top of container In Panel*/
        if (scrollTo) {
            const $panelScroller = $('#panelContentScrollable .simplebar-content-wrapper');
            if ($panelScroller.length) {
                $panelScroller.animate({
                    scrollTop: $container.offset().top - 200
                }, 500);
            } else {
                $('html, body').animate({
                    scrollTop: $container.offset().top - 200
                }, 500);
            }
        }

        /* Add Loading */
        $container.prepend(handleTableIsLoading())
        if ($btn) $btn.addClass('loadingbar primary').prop('disabled', true);

        const path = $container.attr('data-view-data-path');

        const noSearchResult = $container.find('.js-no-search-result');

        $.get(path, data, function (result) {
            if (result) {
                /* Remove Loading */
                $container.find('.js-view-data-loading').remove();
                if ($btn) $btn.removeClass('loadingbar primary').prop('disabled', false);

                $tbody.html(result.data);

                $pagination.html(result.pagination);

                if (noSearchResult.length) {
                    noSearchResult.addClass('d-none');

                    if (result.no_result) {
                        noSearchResult.removeClass('d-none')
                    }
                }

                if (result.specific_content && result.specific_content.el) {
                    const $specificContentEl = $(result.specific_content.el);

                    if ($specificContentEl.length) {
                        $specificContentEl.removeClass('d-none')
                        $specificContentEl.html(result.specific_content.html ?? '')
                    }
                }

                if (typeof callback === "function") {
                    callback()
                }
            }
        }).fail(err => {
            /* Remove Loading */
            $container.find('.js-view-data-loading').remove();
            if ($btn) $btn.removeClass('loadingbar primary').prop('disabled', false);

            showToast('error', oopsLang, somethingWentWrongLang);
        })
    }

    function handleSpecificThShow(value) {
        $('.js-specific-th').addClass('d-none')
        $(`.js-${value}-th`).removeClass('d-none');

        //
        $('.js-not-specific-th').removeClass('d-none');
        $(`.js-not-${value}-th`).addClass('d-none');
    }

    function handleRequestByFormInputOrBtn($this, $container) {

        const $form = $this.closest('form');

        const data = $form.serializeObject();

        let tabValue = null;
        const $tab = $('.js-get-view-data-by-tab.active');
        if ($tab.length) {
            const name = $tab.attr('data-filter-name');
            tabValue = $tab.attr('data-filter-value');
            data[name] = tabValue
        }

        getViewData($container, data, $this, function () {
            if (tabValue) {
                handleSpecificThShow(tabValue)
            }
        })
    }

    $('body').on('click', '.js-get-view-data-by-form', function () {
        const $this = $(this);
        const $container = $('#' + $this.attr('data-container-id'));

        handleRequestByFormInputOrBtn($this, $container)
    })

    function changePanelTabActiveClass($tab) {
        let $parent = $tab.closest('.js-get-view-data-by-timeout-change');

        if (!$parent.length) {
            $parent = $tab.parent();
        }

        if ($tab.hasClass('navbar-item')) {
            $parent.find('.navbar-item').removeClass('active');

            $tab.addClass('active');
        } else {
            $parent.find('.js-get-view-data-by-tab').removeClass('text-primary');

            $tab.addClass('text-primary');
        }
    }

    var authChangeTimeout = undefined;
    const autoChangeEls = `.js-get-view-data-by-timeout-change input, .js-get-view-data-by-timeout-change textarea, .js-get-view-data-by-timeout-change select`;

    $('body').on('change', autoChangeEls, function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest("form");
        const $container = $('#' + $form.attr('data-container-id'));

        if ($this.hasClass('js-just-reload-page')) {
            const name = $this.attr('name');
            const value = $this.val();

            var url = new URL(window.location.href);
            url.searchParams.set(name, value);

            window.location.href = url.href;
        } else {

            if (authChangeTimeout !== undefined) {
                clearTimeout(authChangeTimeout);
            }

            authChangeTimeout = setTimeout(function () {
                handleRequestByFormInputOrBtn($this, $container)
            }, 1500);
        }
    })

    $('body').on('click', '.js-get-view-data-by-tab', function (e) {
        e.preventDefault();

        const $this = $(this)
        const name = $this.attr('data-filter-name');
        const value = $this.attr('data-filter-value');
        const $container = $('#' + $this.attr('data-container-id'));
        const $form = $this.closest('form');

        let data = $form.serializeObject();
        data[name] = value

        changePanelTabActiveClass($this);

        getViewData($container, data, null, function () {
            handleSpecificThShow(value)
        })
    })

    $(document).ready(function () {
        $('.js-get-view-data-default-init').each(function (i, item) {
            const $container = $(item);
            getViewData($container, {})
        })
    })

    $('body').on('click', '.js-ajax-pagination a.page-link', function (e) {
        e.preventDefault();

        const $this = $(this)
        const path = $this.attr('href');
        const $pagination = $this.closest('.js-ajax-pagination');
        const $container = $('#' + $pagination.attr('data-container-id'));
        const noscroll = $pagination.attr('data-noscroll')

        let $containerItems = $container;

        if ($pagination.attr('data-container-items')) {
            $containerItems = $($pagination.attr('data-container-items'));
        }


        /* Scroll to top of container In Panel*/
        if (!noscroll || noscroll !== "true") {
            const $panelScroller = $('#panelContentScrollable .simplebar-content-wrapper');
            if ($panelScroller.length) {
                $panelScroller.animate({
                    scrollTop: $container.offset().top - 200
                }, 500);
            } else {
                $('html, body').animate({
                    scrollTop: $container.offset().top - 200
                }, 500);
            }
        }

        /* Add Loading */
        $container.prepend(handleTableIsLoading())

        $.get(path, function (result) {
            if (result) {
                /* Remove Loading */
                $container.find('.js-view-data-loading').remove();

                $containerItems.html(result.data);

                if (result.pagination) {
                    $pagination.html(result.pagination);
                }
            }
        })
    })
})(jQuery)
