(function () {
    "use strict";

    function getSelection($this, el) {
        const regionParent = $this.attr('data-regions-parent') ?? '';

        if (regionParent) {
            return $this.closest('.' + regionParent).find(el)
        }

        return $(el);
    }

    function handleMapCenterAfterSelectChange($select, zoom) {
        const selectedOption = $select.find('option:checked');
        let mapCenter = selectedOption.attr('data-center');

        if (mapCenter) {
            mapCenter = mapCenter.split(',');

            const regionParent = $select.attr('data-regions-parent') ?? '';

            if (regionParent) {
                const $regionParent = $select.closest('.' + regionParent);
                const mapId = $regionParent.find('.region-map').attr('id');

                if (mapId) {
                    if (typeof windowMapContainers !== "undefined" && typeof windowMapContainers[mapId] !== "undefined" && mapCenter && mapCenter[0] && mapCenter[1]) {
                        windowMapContainers[mapId].setView([mapCenter[0], mapCenter[1]], zoom);
                    }
                }
            }
        }
    }

    $('body').on('change', '.js-country-selection', function () {
        const $this = $(this);

        const defaultVal = (typeof selectRegionDefaultVal !== "undefined" ? selectRegionDefaultVal : null);

        const stateSelection = getSelection($this, '.js-state-selection');
        const citySelection = getSelection($this, '.js-city-selection');
        const districtSelection = getSelection($this, '.js-district-selection');

        stateSelection.val(defaultVal).prop('disabled', true);
        citySelection.val(defaultVal).prop('disabled', true);
        districtSelection.val(defaultVal).prop('disabled', true);

        $('.js-districts-field').addClass('d-none');

        if ($this.val() && $this.val() !== 'all') {
            const mapZoom = $this.attr('data-map-zoom') ?? 5;
            handleMapCenterAfterSelectChange($this, mapZoom);

            $this.addClass('loadingbar gray').prop('disabled', true);

            $.get('/regions/provincesByCountry/' + $this.val(), function (result) {
                if (result && result.code === 200) {
                    let html = '<option value="' + defaultVal + '">' + selectStateLang + '</option>';

                    if (result.provinces && result.provinces.length) {
                        for (let state of result.provinces) {
                            html += '<option value="' + state.id + '" data-center="' + state.geo_center.join(',') + '">' + state.title + '</option>';
                        }
                    }

                    stateSelection.prop('disabled', false);
                    stateSelection.html(html);

                    $this.removeClass('loadingbar gray').prop('disabled', false);
                }
            });
        }
    });

    $('body').on('change', '.js-state-selection', function () {
        const $this = $(this);

        const defaultVal = (typeof selectRegionDefaultVal !== "undefined" ? selectRegionDefaultVal : null);

        const citySelection = getSelection($this, '.js-city-selection');
        const districtSelection = getSelection($this, '.js-district-selection');

        citySelection.val(defaultVal).prop('disabled', true);
        districtSelection.val(defaultVal).prop('disabled', true);

        $('.js-districts-field').addClass('d-none');

        if ($this.val() && $this.val() !== 'all') {
            const mapZoom = $this.attr('data-map-zoom') ?? 8;
            handleMapCenterAfterSelectChange($this, mapZoom);

            $this.addClass('loadingbar gray').prop('disabled', true);

            $.get('/regions/citiesByProvince/' + $this.val(), function (result) {
                if (result && result.code === 200) {

                    let html = '<option value="' + defaultVal + '">' + selectCityLang + '</option>';

                    if (result.cities && result.cities.length) {
                        for (let city of result.cities) {
                            html += '<option value="' + city.id + '" data-center="' + city.geo_center.join(',') + '">' + city.title + '</option>';
                        }
                    }

                    citySelection.prop('disabled', false);
                    citySelection.html(html);

                    $this.removeClass('loadingbar gray').prop('disabled', false);
                }
            });
        }
    });

    $('body').on('change', '.js-city-selection', function () {
        const $this = $(this);

        const defaultVal = (typeof selectRegionDefaultVal !== "undefined" ? selectRegionDefaultVal : null);

        const districtSelection = getSelection($this, '.js-district-selection');

        districtSelection.val(defaultVal).prop('disabled', true);

        if ($this.val() && $this.val() !== 'all') {
            const mapZoom = $this.attr('data-map-zoom') ?? 11;
            handleMapCenterAfterSelectChange($this, mapZoom);

            $this.addClass('loadingbar gray').prop('disabled', true);

            $.get('/regions/districtsByCity/' + $this.val(), function (result) {


                if (result && result.code === 200 && result.districts && result.districts.length) {
                    districtSelection.closest('.js-districts-field').removeClass('d-none')

                    let html = '<option value="' + defaultVal + '">' + selectDistrictLang + '</option>';

                    if (result.districts && result.districts.length) {
                        for (let district of result.districts) {
                            html += '<option value="' + district.id + '" data-center="' + district.geo_center.join(',') + '">' + district.title + '</option>';
                        }
                    }

                    districtSelection.prop('disabled', false);
                    districtSelection.html(html);
                } else {
                    districtSelection.closest('.js-districts-field').addClass('d-none')
                }

                $this.removeClass('loadingbar gray').prop('disabled', false);
            });
        }
    });

    $('body').on('change', '.js-district-selection', function () {
        const $this = $(this);

        if ($this.val() && $this.val() !== 'all') {
            const mapZoom = $this.attr('data-map-zoom') ?? 14;
            handleMapCenterAfterSelectChange($this, mapZoom);
        }
    });

})(jQuery);
