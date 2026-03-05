(function () {
    "use strict"

    window.windowMapContainers = {};

    window.makeMapContainer = function (elId, storeDragMovement = false) {
        const mapContainer = $('#' + elId);
        const lat = mapContainer.attr('data-latitude');
        const lng = mapContainer.attr('data-longitude');
        const zoom = mapContainer.attr('data-zoom');
        const maxZoom = mapContainer.attr('data-max-zoom') ?? 18;
        const dragging = !(mapContainer.attr('data-dragging') === 'false'); // if you want true, dont use data-dragging on tag
        const zoomControl = !(mapContainer.attr('data-zoomControl') === 'false');
        const zoomControlPosition = mapContainer.attr('data-zoomControlPosition') ?? 'topright';
        const scrollWheelZoom = !(mapContainer.attr('data-scrollWheelZoom') === 'false');
        const attribution = !(mapContainer.attr('data-attribution') === 'false');

        const fullscreen = (mapContainer.attr('data-fullscreen') ?? false)
        const defaultMarker = (mapContainer.attr('data-default-marker') ?? false)

        const mapOption = {
            dragging: dragging,
            zoomControl: false,
            scrollWheelZoom: scrollWheelZoom,
        };

        var map = L.map(elId, mapOption).setView([lat, lng], zoom);

        L.tileLayer(leafletApiPath, {
            maxZoom: maxZoom,
            tileSize: 512,
            zoomOffset: -1,
            attribution: attribution ? '© <a target="_blank" rel="nofollow" href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>' : ''
        }).addTo(map);

        // add default marker
        if (defaultMarker) {
            const myIcon = L.icon({
                iconUrl: '/assets/default/images/location.png',
                iconAnchor: [lat, lng],
            });
            L.marker([lat, lng], {color: '#43d477', icon: myIcon}).addTo(map);
        }


        //add zoom control with your options
        if (zoomControl) {
            L.control.zoom({
                position: zoomControlPosition
            }).addTo(map);
        }

        // create a fullscreen button and add it to the map
        if (fullscreen) {
            L.control.fullscreen({
                position: 'topleft', // change the position of the button can be topleft, topright, bottomright or bottomleft, default topleft
                /*title: 'Show me the fullscreen !', // change the title of the button, default Full Screen
                titleCancel: 'Exit fullscreen mode', // change the title of the button when fullscreen is on, default Exit Full Screen
                content: null, // change the content of the button, can be HTML, default null
                forceSeparateButton: true, // force separate button to detach from zoom buttons, default false
                forcePseudoFullscreen: true, // force use of pseudo full screen even if full screen API is available, default false
                fullscreenElement: false // Dom element to render in full screen, false by default, fallback to map._container*/
            }).addTo(map);
        }

        if (storeDragMovement) {
            const $parent = mapContainer.parent();

            const LocationLatitude = $parent.find('#LocationLatitude');
            const LocationLongitude = $parent.find('#LocationLongitude');

            if (LocationLatitude.length && LocationLongitude.length) {
                map.on('moveend', function (e) {
                    const centerLocation = map.getCenter();

                    LocationLatitude.val(centerLocation.lat);
                    LocationLongitude.val(centerLocation.lng);
                });

                map.on('dragstart', function () {
                    $('.region-map .marker').addClass('dragging');
                });

                map.on('dragend', function () {
                    $('.region-map .marker').removeClass('dragging')
                });
            }
        }

        windowMapContainers[elId] = map

        return map;
    }

    function makeMapContainerForDrag($el) {
        const $parent = $el.parent();


    }

    $(document).ready(function () {
        function handleAddressMaps() {
            const mapCards = $('.region-map');

            if (mapCards.length > 0 && typeof makeMapContainer !== "undefined") {
                for (const mapCard of mapCards) {
                    if ($(mapCard).hasClass('with-default-initial')) {
                        makeMapContainer($(mapCard).attr('id'));
                    } else if ($(mapCard).hasClass('with-default-initial-drag')) {
                        makeMapContainer($(mapCard).attr('id'), true);
                    }
                }
            }
        }

        handleAddressMaps();
    })

    window.resetAddressMaps = function (mapCards) {

        if (mapCards.length > 0 && typeof makeMapContainer !== "undefined") {
            for (const mapCard of mapCards) {
                const elId = $(mapCard).attr('id');

                if (typeof windowMapContainers !== "undefined" && typeof windowMapContainers[elId] !== "undefined") {
                    windowMapContainers[elId].off();
                    windowMapContainers[elId].remove();
                }


                if ($(mapCard).hasClass('with-default-initial')) {
                    makeMapContainer(elId);
                } else if ($(mapCard).hasClass('with-default-initial-drag')) {
                    makeMapContainer(elId, true);
                }
            }
        }
    }
})(jQuery)
