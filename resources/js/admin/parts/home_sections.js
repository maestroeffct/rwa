(function ($) {
    "use strict";
    const style = getComputedStyle(document.body);
    const primaryColor = style.getPropertyValue('--primary');

    function updateToDatabase(table, idString) {
        $.post(adminPanelPrefix + '/settings/personalization/home_sections/sort', {table: table, items: idString}, function (result) {
            showToast('success', result.title, result.msg)
        }).fail(err => {
            showToast('error', 'Error', '')
        });
    }

    function setSortable(target) {
        if (target.length) {
            target.sortable({
                group: 'no-drop',
                handle: '.move-icon',
                axis: "y",
                update: function (e, ui) {
                    var sortData = target.sortable('toArray', {attribute: 'data-id'});
                    var table = e.target.getAttribute('data-order-table');

                    updateToDatabase(table, sortData.join(','));
                }
            });
        }
    }

    var target = $('.draggable-lists');
    setSortable(target);
})(jQuery);
