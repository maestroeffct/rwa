@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("instructors_lists") }}">
@endpush

@php
    $pageHeroImage = getThemePageBackgroundSettings('instructors_lists');
    $pageOverlayImage = getThemePageBackgroundSettings('instructors_header_overlay_image');
@endphp

@section("content")
    <main class="pb-104">
        <section class="instructors-lists-hero position-relative">
            <div class="instructors-lists-hero__mask"></div>
            <img src="{{ $pageHeroImage }}" class="img-cover" alt="{{ trans('home.instructors') }}"/>
        </section>

        {{-- Header --}}
        <div class="container">
            {{-- Header --}}
            @include('design_1.web.instructors.includes.header')

            {{-- Top Instructors --}}
            @include('design_1.web.instructors.includes.top_instructors')
        </div>

        <form action="/instructors" class="js-get-view-data-by-timeout-change container" data-container-id="listsContainer">

            {{-- Top Filters --}}
            @include("design_1.web.instructors.includes.top_filters")

            <div class="row">
                {{-- Left Filters --}}
                <div class="col-12 col-lg-3 mt-28">
                    @include("design_1.web.instructors.includes.left_filters")
                </div>

                {{-- Instructors Lists --}}
                <div class="col-12 col-lg-9 mt-12">
                    <div id="listsContainer" class="" data-body=".js-lists-body" data-view-data-path="/instructors">

                        <div class="js-lists-body d-grid grid-columns-auto grid-lg-columns-2 gap-24 mt-28">
                            @include('design_1.web.instructors.components.cards.grids.index', ['instructors' => $users, 'gridCardClassName' => ""])
                        </div>

                        <!-- Pagination -->
                        <div id="pagination" class="js-ajax-pagination" data-container-id="listsContainer" data-container-items=".js-lists-body">
                            {!! $pagination !!}
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </main>
@endsection

@push('scripts_bottom')
    <script>
        var selectedCloseIcon = `<x-iconsax-lin-add class="icons close-icon" width="16px" height="16px"/>`;
    </script>

    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
    <script src="{{ getDesign1ScriptPath("range_slider_helpers") }}"></script>

    <script src="{{ getDesign1ScriptPath("instructors_lists") }}"></script>
@endpush
