@if(!empty($contactSettings['latitude']) and !empty($contactSettings['longitude']))
    <div class="region-map contactus-map with-default-initial rounded-8 bg-gray-100" id="contactMap"
         data-latitude="{{ $contactSettings['latitude'] }}"
         data-longitude="{{ $contactSettings['longitude'] }}"
         data-zoom="{{ $contactSettings['map_zoom'] ?? 12 }}"
         data-dragging="false"
         data-zoomControl="true"
         data-scrollWheelZoom="false"
    >
        <img src="/assets/design_1/img/map/pin_large.svg" class="marker" width="40" height="40">
    </div>
@endif
