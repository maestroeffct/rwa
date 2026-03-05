(function ($) {
    "use strict"

    /*********
     * Options
     * */
    $('body').on('change', '#enableCountdownSwitch', function () {
        const $filed = $('.js-countdown-time-reference-field');

        if (this.checked) {
            $filed.removeClass('d-none')
        } else {
            $filed.addClass('d-none')
        }
    })

    /*********
     * Translates By Locale
     * */

    $('body').on('change', '.js-event-content-locale', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $(this).closest('.js-content-form');
        const locale = $this.val();
        const eventId = $this.attr('data-event-id');
        const item_id = $this.attr('data-id');
        const relation = $this.attr('data-relation');
        let fields = $this.attr('data-fields');
        fields = fields.split(',');


        $this.addClass('loadingbar gray');

        const path = '/panel/events/' + eventId + '/getContentItemByLocale';
        const data = {
            item_id,
            locale,
            relation
        };

        $.post(path, data, function (result) {
            if (result && result.item) {
                const item = result.item;

                Object.keys(item).forEach(function (key) {
                    const value = item[key];

                    if ($.inArray(key, fields) !== -1) {
                        let element = $form.find('.js-ajax-' + key);
                        element.val(value);
                    }
                });

                $this.removeClass('loadingbar gray');
            }
        }).fail(err => {
            $this.removeClass('loadingbar gray');
        });
    });


})(jQuery)
