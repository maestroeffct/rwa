(function ($) {
    "use strict"

    function toggleTheme(isDark) {
        const root = $(':root')[0];
        const mode = isDark ? 'dark' : 'light';

        if (typeof themeColorsMode !== "undefined" && typeof themeColorsMode[mode] !== "undefined") {
            const colors = themeColorsMode[mode];

            Object.keys(colors).forEach((key) => {
                const color = colors[key];
                const name = "--" + key.replaceAll("_", '-');

                $(root).css(name, color);
            })
        }
    }

    function storeUserThemeColor(isDark) {
        const path = "/set-theme-color-mode"
        const mode = isDark ? 'dark' : 'light';

        const data = {
            mode: mode
        }

        $.post(path, data, function (result) {

        })
    }

    $('body').on('click', '.js-theme-color-toggle', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $body = $('body');

        $this.toggleClass('dark-mode');

        let dark = $this.hasClass('dark-mode');

        if (dark) {
            $body.addClass('dark-mode');
        } else {
            $body.removeClass('dark-mode');
        }

        storeUserThemeColor(dark);

        toggleTheme(dark);
    });


    $(document).ready(function () {
        const $themeHeader = $('#themeHeaderSticky');

        $(window).on('scroll', function () {
            const scrollTop = $(window).scrollTop();
            if (scrollTop > 400) {
                $themeHeader.addClass('sticky');
            } else if(scrollTop < 200) {
                $themeHeader.removeClass('sticky');
            }
        });
    });

})(jQuery)
