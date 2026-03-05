@if((!empty($mapCenter) and is_array($mapCenter)))
    <div class="position-relative instructor-finder__map-container bg-gray-200" id="instructorFinderPageMap"
         data-latitude="{{ $mapCenter[0] }}"
         data-longitude="{{ $mapCenter[1] }}"
         data-zoom="{{ $mapZoom }}"
         data-dragging="true"
         data-zoomControl="false"
         data-scrollWheelZoom="true"
         data-zoomControlPosition="bottomleft"
    >
        {{--<img src="/assets/design_1/img/map/pin_large.svg" class="marker" width="40" height="40">--}}
    </div>
@endif


