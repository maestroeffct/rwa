@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("forum") }}">
@endpush


@section('content')

    <div class="position-relative mb-64">

        {{-- Hero --}}
        @include('design_1.web.forums.homepage.includes.hero')

        {{-- Stats --}}
        @include('design_1.web.forums.homepage.includes.stats')

        {{-- Revolver --}}
        @include('design_1.web.forums.homepage.includes.revolver')

        {{-- Featured Topics --}}
        @if(!empty($featuredTopics) and count($featuredTopics))
            @include('design_1.web.forums.homepage.includes.featured_topics')
        @endif

        {{-- Forums --}}
        @if(!empty($forums) and count($forums))
            @include('design_1.web.forums.homepage.includes.forums')
        @endif

        {{-- Recommended Topics --}}
        @if(!empty($recommendedTopics) and count($recommendedTopics))
            @include('design_1.web.forums.homepage.includes.recommended_topics')
        @endif

        {{--  --}}
        @include('design_1.web.forums.homepage.includes.cta_section')

    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>

@endpush
