@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("organizations_lists") }}">
@endpush

@php
    $pageHeroImage = getThemePageBackgroundSettings('organizations_lists');
    $pageOverlayImage = getThemePageBackgroundSettings('organizations_header_overlay_image');
@endphp

@section("content")
    <main class="pb-56">
        <section class="organizations-lists-hero position-relative">
            <div class="organizations-lists-hero__mask"></div>
            <img src="{{ $pageHeroImage }}" class="img-cover" alt="{{ trans('home.organizations') }}"/>
        </section>

        {{-- Header --}}
        <div class="container">
            {{-- Header --}}
            @include('design_1.web.organizations.includes.header')

            {{-- Top organizations --}}
            @include('design_1.web.organizations.includes.top_organizations')
        </div>

        <form action="/organizations" class="js-get-view-data-by-timeout-change container" data-container-id="listsContainer">

            {{-- Top Filters --}}
            @include("design_1.web.organizations.includes.top_filters")

            <div class="row">
                {{-- Left Filters --}}
                <div class="col-12 col-lg-3 mt-28">
                    @include("design_1.web.organizations.includes.left_filters")
                </div>

                {{-- organizations Lists --}}
                <div class="col-12 col-lg-9 mt-12">
                    <div id="listsContainer" class="" data-body=".js-lists-body" data-view-data-path="/organizations">

                        <div class="js-lists-body d-grid grid-columns-auto grid-lg-columns-3 gap-24 mt-28">
                            @include('design_1.web.organizations.components.cards.grids.index', ['organizations' => $users, 'gridCardClassName' => ""])
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
