(function () {
    "use strict"



    $(document).ready(function () {
        const $players = $('.js-init-plyr-io');

        if ($players.length > 0) {
            const options = {
                clickToPlay: false,
                disableContextMenu: true,
                youtube: {
                    rel: 0,
                    modestbranding: 1,
                    iv_load_policy: 3,
                    fs: 0,
                    disablekb: 1,
                    playsinline: 1,
                    controls: 0
                }
            }

            for (const plyr of $players) {
                const player = new Plyr(plyr, options);
            }
        }
    })
})(jQuery)
