@extends('design_1.web.layouts.app')


@push('styles_top')
    {{-- Prerequisite Styles --}}
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/design_1/landing_builder/front.min.css">
@endpush


@section('content')

    @if(!empty($landingItem))
        @foreach($landingItem->components as $component)
            @includeIf("landingBuilder.front.components.{$component->landingBuilderComponent->name}.index", ['landingComponent' => $component])
        @endforeach
    @endif

@endsection

@push('scripts_bottom')
    {{-- Prerequisite Scripts --}}
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>
    <script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>

    {{-- Jquery --}}
    <script src="/assets/vendors/typed/typedjs.js"></script>

    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>
    <script src="/assets/design_1/js/parts/time-counter-down.min.js"></script>
    <script src="{{ getDesign1ScriptPath("video_player_helpers") }}"></script>
    <script src="/assets/design_1/landing_builder/js/front.min.js"></script>

@endpush
