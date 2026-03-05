@php
    $advertisingModalSettings = getAdvertisingModalSettings();
@endphp

@if(!empty($advertisingModalSettings))
    @push('scripts_bottom')
        <link rel="stylesheet" href="{{ getDesign1StylePath("advertising_modals") }}">

        <script>
            var hasAdvertisingModal = true;
            var openingDelayAdvertisingModal = Number({{ !empty($advertisingModalSettings['opening_delay']) ? $advertisingModalSettings['opening_delay'] : 0 }});
        </script>

        <script src="/assets/design_1/js/parts/time-counter-down.min.js"></script>
        <script src="{{ getDesign1ScriptPath("advertising_modals") }}"></script>
    @endpush
@endif
