@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("meeting_packages_lists") }}">
@endpush

@section("content")
    <main class="pb-120">
        @php
            $pageHeroImage = getThemePageBackgroundSettings('meeting_packages_lists');
            $pageOverlayImage = getThemePageBackgroundSettings('meeting_packages_lists_overlay_image');
        @endphp

        <section class="meeting-packages-lists-hero position-relative">
            <div class="meeting-packages-lists-hero__mask"></div>
            <img src="{{ $pageHeroImage }}" class="img-cover" alt="{{ trans('update.meeting_packages') }}"/>
        </section>

        {{-- Header --}}
        <div class="container">
            <div class="meeting-packages-lists-header position-relative">
                <div class="meeting-packages-lists-header__mask"></div>
                <div class="position-relative d-flex align-items-start bg-white rounded-32 z-index-2">
                    <div class="d-flex flex-column p-32">
                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                            <x-iconsax-bul-video-play class="icons text-primary" width="32px" height="32px"/>
                        </div>

                        <div class="d-flex align-items-center mt-16 text-gray-500">
                            <a href="/" class="text-gray-500">{{ getPlatformName() }}</a>
                            <x-iconsax-lin-arrow-right-1 class="mx-4" width="16px" height="16px"/>
                            <span class="">{{ trans('update.events') }}</span>
                        </div>

                        <h1 class="font-24 font-weight-bold mt-12">{{ trans('update.events') }}</h1>
                        <div class="font-12 text-gray-500 mt-8">{{ trans('update.all_meeting_packages_list_hint') }}</div>
                    </div>

                    @if(!empty($pageOverlayImage))
                        <div class="meeting-packages-lists-header__overlay-img">
                            <img src="{{ $pageOverlayImage }}" alt="{{ $pageTitle }}" class="img-cover">
                        </div>
                    @endif
                </div>
            </div>
        </div>


        <form action="{{ $pageBasePath }}" class="js-get-view-data-by-timeout-change container mt-24" data-container-id="listsContainer">
            {{-- Top Filters --}}
            @include("design_1.web.meeting_packages.lists.includes.top_filters")

            <div class="row">
                {{-- Left Filters --}}
                <div class="col-12 col-lg-3 mt-28">
                    @include("design_1.web.meeting_packages.lists.includes.left_filters")
                </div>

                {{-- Courses Lists --}}
                <div class="col-12 col-lg-9 mt-4">
                    <div id="listsContainer" class="" data-body=".js-lists-body" data-view-data-path="{{ $pageBasePath }}">
                        <div class="js-lists-body row">
                            @include('design_1.web.meeting_packages.components.cards.grids.index',['meetingPackages' => $meetingPackages, 'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-24"])
                        </div>

                        {{-- Pagination --}}
                        <div id="pagination" class="js-ajax-pagination" data-container-id="listsContainer" data-container-items=".js-lists-body">
                            {!! $pagination !!}
                        </div>
                    </div>


                    {{-- Seo Content --}}
                </div>
            </div>
        </form>
    </main>
@endsection


@push('scripts_bottom')
    <script src="/assets/vendors/wrunner-html-range-slider-with-2-handles/js/wrunner-jquery.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="{{ getDesign1ScriptPath("range_slider_helpers") }}"></script>
    <script src="{{ getDesign1ScriptPath("meeting_packages_lists") }}"></script>
@endpush
