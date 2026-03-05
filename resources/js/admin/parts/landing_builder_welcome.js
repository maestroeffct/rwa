(function () {
    "use strict"

    $(document).ready(function () {
        const $progress = $('.js-progress-card');

        if ($progress.length) {
            const url = $progress.attr('data-url')
            const seconds = parseInt($progress.attr('data-seconds')) || 30;
            const $bar = $progress.find('.progress-bar');

            $({ width: 0 }).animate(
                { width: 100 },
                {
                    duration: seconds * 1000,
                    easing: 'linear',
                    step: function (now) {
                        $bar.css('width', `${now}%`);
                    },
                    complete: function () {
                        if (url) {
                            window.location.href = url
                        }
                    }
                }
            );


        }
    });


})(jQuery)
