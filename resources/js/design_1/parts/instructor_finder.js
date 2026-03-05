(function ($) {
    "use strict"

    function makeUserMarker(user) {
        const userMarker = L.divIcon({
            html: "<div class='marker-pin rounded-circle'><img src='" + user.avatar + "' class='img-cover rounded-circle' alt='" + user.full_name + "'/></div>",
            iconAnchor: [24, 48],
            iconSize: [48, 48],
            className: 'rounded-circle bg-white border-0'
        });

        const marker = L.marker([user.location[0], user.location[1]], {icon: userMarker});

        marker.bindPopup(handleUserMapCardHtml(user), {
            className: 'map-instructor-card-popup'
        });

        return marker;
    }


    function handleUserRateHtml(rate) {
        let i = 5;

        let html = `<div class="stars-card d-flex align-items-center p-8 mt-8 rounded-16 bg-gray-100">`;

        while (--i >= 5 - rate) {
            html += `<span class="stars-card__item active">${starIcon}</span>`;
        }

        while (i-- >= 0) {
            html += `<span class="stars-card__item ">${starIcon}</span>`;
        }

        html += `<span class="ml-4 font-14 text-gray-500">(${rate})</span>`

        html += `</div>`;

        return html;
    }

    function handleUserMapCardHtml(user) {
        return `<div class="instructor-finder-map-popup position-relative bg-white p-16 rounded-16">
            <div class="instructor-finder-map-popup__mask"></div>

            <div class="position-relative d-flex-center flex-column z-index-2">
                <div class="instructor-finder-map-popup__user-avatar size-64 rounded-circle mt-16">
                    <img src="${user.avatar ?? ''}" class="img-cover rounded-circle" alt="${user.full_name ?? ''}">
                </div>

                <h4 class="font-14 font-weight-bold mt-12">${user.full_name ?? ''}</h4>

                ${handleUserRateHtml(user.rate)}

                ${
            user.price ?
                `
                        <div class="d-flex align-items-center mt-16">
                            <span class="text-primary font-14 font-weight-bold">${currency}${user.price ?? ''}</span>
                            <span class="font-12 text-gray-500 ml-4">/${hourLang}</span>
                        </div>
                        `
                :
                `
                        <div class="d-flex align-items-center mt-16">
                            <span class="text-primary font-14 font-weight-bold">${freeLang}</span>
                        </div>
                        `
        }


                <div class="d-flex align-items-center gap-12 mt-16">
                    <a href="${user.profileUrl}" class="d-flex-center size-36 rounded-circle bg-gray-200" target="_blank" data-toggle="tooltip" data-placement="top" title="${profileLang}">${frameIcon}</a>

                    <a href="${user.profileUrl}?tab=appointments" class="d-flex-center size-36 rounded-circle bg-primary" target="_blank" data-toggle="tooltip" data-placement="top" title="${bookAMeetingLang}">${calendarIcon}</a>
                </div>
            </div>
        </div>`
    }


    $(document).ready(function () {
        var instructorFinderPageMap = makeMapContainer("instructorFinderPageMap");

        if (mapUsers && Array.isArray(mapUsers)) {
            var myMarkersGroup = L.markerClusterGroup({
                showCoverageOnHover: false,
            });

            for (const mapUser of mapUsers) {
                const marker = makeUserMarker(mapUser);

                myMarkersGroup.addLayer(marker);
            }

            instructorFinderPageMap.addLayer(myMarkersGroup);
        }


        handleDoubleRange($('#priceRange'), 'price', function (range, minTimeEl, maxTimeEl) {
            range.onValueUpdate(function (values) {
                minTimeEl.val(values.minValue);
                maxTimeEl.val(values.maxValue);

                $('.js-filters-min-price').val(jsCurrentCurrency + values.minValue)
                $('.js-filters-max-price').val(jsCurrentCurrency + values.maxValue)
            });
        });

        handleDoubleRange($('#timeRange'), 'time', function (range, minTimeEl, maxTimeEl) {
            range.onValueUpdate(function (values) {
                minTimeEl.val(values.minValue);
                maxTimeEl.val(values.maxValue);

                $('.js-filters-min-time').val(values.minValue)
                $('.js-filters-max-time').val(values.maxValue)
            });
        });

    })

    function handleNotResultHtml() {
        return `<div class="no-result default-no-result mt-56 d-flex align-items-center justify-content-center flex-column">
                    <div class="no-result-logo">
                        <img src="/assets/default/img/no-results/support.png" alt="">
                    </div>

                    <div class="d-flex align-items-center flex-column mt-16 text-center">
                        <h2 class="font-16 font-weight-bold">${noResultTitle}</h2>

                        <p class="mt-4 font-12 text-center text-gray-500">${noResultHint}</p>
                    </div>
                </div>`
    }

    // Load More And filters
    var loadMoreInstructors = {
        page: 1,
        has_more: true,
    };

    function getInstructors(url, page, isLoadMore) {
        const $form = $('#filtersForm');
        let data = $form.serializeObject();
        data['page'] = page;

        const $loadMoreInstructors = $('#loadMoreInstructors');
        const $instructorsList = $('#instructorsList');

        $.get(url, data, function (result) {
            if (result) {

                if (isLoadMore) {
                    $instructorsList.append(result.html);
                } else {
                    if (result.html) {
                        $instructorsList.html(result.html);
                    } else {
                        $instructorsList.html(handleNotResultHtml());
                    }
                }

                const hasMore = (page < result.last_page);
                loadMoreInstructors = {
                    page: page,
                    has_more: hasMore,
                };

                if (!hasMore) {
                    $loadMoreInstructors.addClass('d-none');
                } else {
                    $loadMoreInstructors.removeClass('d-none');
                }
            }

            $loadMoreInstructors.removeClass('loadingbar').prop('disabled', false);
        }).fail(err => {
            $loadMoreInstructors.removeClass('loadingbar').prop('disabled', false);
        })
    }

    $('body').on('click', '#loadMoreInstructors', function (e) {
        e.preventDefault();

        const $this = $(this);
        const url = $this.attr('data-url');

        $this.addClass('loadingbar').prop('disabled', true);

        if (loadMoreInstructors.has_more) {
            getInstructors(url, loadMoreInstructors.page + 1, true);
        }
    })

    var requestTimeOut;

    $('body').on('change', '#filtersForm input, #filtersForm select', function (e) {
        e.preventDefault();

        if (requestTimeOut !== undefined) {
            clearTimeout(requestTimeOut);
        }

        const $instructorsList = $('#instructorsList');
        const $form = $('#filtersForm');
        const url = $form.attr('action');

        $('html, body').animate({
            scrollTop: $instructorsList.offset().top - 100
        }, 1000);

        $instructorsList.html(`<div class="d-flex align-items-center justify-content-center w-100 my-56">
                            <img src="/assets/design_1/img/loading.svg" width="80px" height="80px">
                        </div>`);

        loadMoreInstructors = {
            page: 1,
            has_more: true,
        };

        getInstructors(url, 1, false);
    });

})(jQuery)
