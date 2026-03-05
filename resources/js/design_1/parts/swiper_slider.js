(function () {
    $(document).ready(function () {

        function makeSwiper(className) {
            const $el = $('.' + className);

            if ($el && $el.length) {
                const slidesPerView = $el.attr('data-slides-per-view') ?? 1;
                const spaceBetween = $el.attr('data-space-between') ?? 15;
                const loop = ($el.attr('data-loop') && $el.attr('data-loop') === 'true');
                const autoplay = ($el.attr('data-autoplay') && $el.attr('data-autoplay') === 'true');
                const centeredSlides = ($el.attr('data-centered-slides') && $el.attr('data-centered-slides') === 'true');
                const reverseDirection = ($el.attr('data-reverse-direction') && $el.attr('data-reverse-direction') === 'true');
                const navigation = ($el.attr('data-navigation') && $el.attr('data-navigation') === 'true');
                const freeMode = ($el.attr('data-freeMode') && $el.attr('data-freeMode') === 'true');
                const allowTouchMove = !($el.attr('data-disable-touch-move') && $el.attr('data-disable-touch-move') === 'true');

                const speed = $el.attr('data-speed') ?? 1000;
                const autoplayDelay = $el.attr('data-autoplay-delay') ?? 5000;
                const paginationEl = $el.attr('data-pagination') ?? null;
                const breakpoints = $el.attr('data-breakpoints') ?? null;

                const options = {
                    slidesPerView: (slidesPerView === "auto") ? "auto" : slidesPerView,
                    spaceBetween: Number(spaceBetween),
                    loop: loop,
                    speed: Number(speed),
                    centeredSlides: centeredSlides,
                    autoplay: false,
                    navigation: false,
                    pagination: false,
                    reverseDirection: reverseDirection,
                    freeMode: freeMode,
                    allowTouchMove: allowTouchMove
                }

                if (autoplay) {
                    options['autoplay'] = {
                        delay: Number(autoplayDelay),
                        disableOnInteraction: false,
                        reverseDirection: reverseDirection
                    }
                }

                if (navigation) {
                    options['navigation'] = {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    }
                }

                if (paginationEl) {
                    options['pagination'] = {
                        el: `.${paginationEl}`,
                        clickable: true,
                        type: 'bullets',
                    }
                }

                if (breakpoints) {
                    options['breakpoints'] = {};

                    const breakpointsArray = breakpoints.split(',');

                    if (breakpointsArray.length) {
                        for (const breakpointItem of breakpointsArray) {
                            const breakpointItemArray = breakpointItem.split(':');

                            if (breakpointItemArray.length === 2) {
                                options['breakpoints'][Number(breakpointItemArray[0])] = {
                                    slidesPerView: Number(breakpointItemArray[1]),
                                };
                            }
                        }
                    }
                }

                new Swiper(`.${className}`, options)
            }
        }

        const items = $(".js-make-swiper");

        for (const item of items) {
            const className = $(item).attr('data-item')

            if (className) {
                makeSwiper(className)
            }
        }

        /*new Swiper('.featured-products-half-card-swiper', {
            slidesPerView: 1,
            spaceBetween: 15,
            loop: false,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: `.${paginationEl}`,
                clickable: true,
            },
            breakpoints: {
                991: {
                    slidesPerView: 2,
                },

                660: {
                    slidesPerView: 1,
                },
            }
        });*/
    })
})(jQuery)
