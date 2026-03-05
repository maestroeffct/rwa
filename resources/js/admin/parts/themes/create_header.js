(function ($) {
    "use strict";

    function setSortable(target) {
        if (target.length) {
            target.sortable({
                group: 'no-drop',
                handle: '.move-icon',
                axis: "y",
                update: function (e, ui) {
                    var sortData = target.sortable('toArray', {attribute: 'data-id'});
                    var table = e.target.getAttribute('data-order-table');
                }
            });
        }
    }

    $(document).ready(function () {
        const targets = $('.draggable-lists');

        for (const target of targets) {
            setSortable($(target));
        }
    })

    // Navbar Links
    $('body').on('click', '.js-add-navbar-link-btn', function (e) {
        e.preventDefault();
        var mainRow = $('#newNavbarLinks');

        var copy = mainRow.clone();
        copy.removeClass('main-row');
        copy.removeClass('d-none');
        var copyHtml = copy.prop('innerHTML');
        copyHtml = copyHtml.replace(/\[record\]/g, '[' + randomString() + ']');
        copy.html(copyHtml);
        $('.js-navbar-links-lists').append(copy);
    });

    $('body').on('click', '.js-remove-btn', function (e) {
        e.preventDefault();
        $(this).closest('.form-group').remove();
    });


    // Navbar Button
    $('body').on('click', '.js-add-navbar-button-btn', function (e) {
        e.preventDefault();
        var mainRow = $('#newNavbarButton');

        var copy = mainRow.clone();
        copy.removeClass('main-row');
        copy.removeClass('d-none');
        var copyHtml = copy.prop('innerHTML');
        copyHtml = copyHtml.replace(/\[record\]/g, '[' + randomString() + ']');
        copy.html(copyHtml);
        $('.js-navbar-buttons-lists').append(copy);
    });

})(jQuery);
