(function ($) {
    "use strict"


    $('body').on('click', '.video-and-image-slider-full-width-section__slider-item-video-btn', function (e) {
        e.preventDefault();

        const $this = $(this)
        const $parent = $this.closest('.video-and-image-slider-full-width-section__slider-item');

        let path = $this.attr('data-path');
        let source = $this.attr('data-source');
        let elId = $this.attr('data-id');
        const height = 800;

        const {html, options} = makeVideoPlayerHtml(path, source, height, elId);

        $parent.html(html)

        new Plyr(`#${elId}`, options);
    })
})(jQuery)
