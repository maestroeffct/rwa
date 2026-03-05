(function ($) {
    "use strict"


    $('body').on('click', '.multi-tab-image-video-section__content-video-btn', function (e) {
        e.preventDefault();

        const $this = $(this)
        const $parent = $this.closest('.multi-tab-image-video-section__content-box');

        let path = $this.attr('data-path');
        let source = $this.attr('data-source');
        let elId = $this.attr('data-id');
        const height = 700;

        const {html, options} = makeVideoPlayerHtml(path, source, height, elId);

        $parent.html(html)

        new Plyr(`#${elId}`, options);
    })

    function handleContentHtmlByType($el) {
        const title = $el.attr("data-title");
        const type = $el.attr("data-type");
        const image = $el.attr("data-image");
        const url = $el.attr("data-url");
        const video = $el.attr("data-video");
        const cover = $el.attr("data-cover");

        let html = "";

        if (type === "image" && image) {
            if (url) {
                html = `<a href="${url}" target="_blank" class="">
                        <img src="${image}" alt="${title}" class="img-cover">
                    </a>`;
            } else {
                html = `<img src="${image}" alt="${title}" class="img-cover">`;
            }
        } else if (type === "video" && video) {
            html = "";

            if (cover) {
                html += `<img src="${cover}" alt="${title}" class="img-cover">`;
            }

            const randStr = randomString(6);

            html += `<div class="multi-tab-image-video-section__content-video-btn d-flex-center rounded-circle size-92 cursor-pointer"
                 data-path="${video}"
                 data-source="upload"
                 data-id="${randStr}"
            >
                ${bolPlayIcon}
            </div>`;
        }

        return html;
    }

    $('body').on('click', '.multi-tab-image-video-section__content-tabs-item', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $parent = $this.closest('.js-multi-tab-image-video-content-container');
        const $box = $parent.find('.multi-tab-image-video-section__content-box');

        $parent.find('.multi-tab-image-video-section__content-tabs-item').removeClass('active')
        $this.addClass('active');

        const html = handleContentHtmlByType($this)
        $box.html(html)
    })
})(jQuery)
