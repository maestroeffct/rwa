@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("upcoming_courses_lists") }}">
@endpush

@php
    $pageHeroImage = getThemePageBackgroundSettings('upcoming_courses_lists');
    $pageOverlayImage = getThemePageBackgroundSettings('upcoming_courses_lists_overlay_image');
@endphp

@section("content")
    <main class="pb-120">
        <section class="upcoming-courses-lists-hero position-relative">
            <div class="upcoming-courses-lists-hero__mask"></div>
            <img src="{{ $pageHeroImage }}" class="img-cover" alt="{{ trans('update.upcoming_courses') }}"/>
        </section>

        {{-- Header --}}
        <div class="container">
            <div class="upcoming-courses-lists-header position-relative">
                <div class="upcoming-courses-lists-header__mask"></div>
                <div class="position-relative d-flex align-items-start bg-white rounded-32 z-index-2">
                    <div class="d-flex flex-column p-32">
                        <div class="d-flex-center size-64 rounded-12 bg-warning-30">
                            <x-iconsax-bul-calendar-2 class="icons text-warning" width="32px" height="32px"/>
                        </div>

                        <div class="d-flex align-items-center mt-16 text-gray-500">
                            <a href="/" class="text-gray-500">{{ getPlatformName() }}</a>
                            <x-iconsax-lin-arrow-right-1 class="mx-4" width="16px" height="16px"/>
                            <span class="">{{ trans('update.upcoming_courses') }}</span>
                        </div>

                        <h1 class="font-24 font-weight-bold mt-12">{{ trans('update.upcoming_courses') }}</h1>
                        <div class="font-12 text-gray-500 mt-8">{{ trans('update.check_upcoming_courses_that_will_be_published_soon') }}</div>
                    </div>

                    @if(!empty($pageOverlayImage))
                        <div class="upcoming-courses-lists-header__overlay-img">
                            <img src="{{ $pageOverlayImage }}" alt="{{ trans('update.overlay_image') }}" class="img-cover">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <form action="{{ $pageBasePath }}" class="js-get-view-data-by-timeout-change container mt-24" data-container-id="listsContainer">
            {{-- Top Filters --}}
            @include("design_1.web.upcoming_courses.lists.includes.top_filters")

            <div class="row">
                {{-- Left Filters --}}
                <div class="col-12 col-lg-3 mt-28">
                    @include("design_1.web.upcoming_courses.lists.includes.left_filters")
                </div>

                {{-- Courses Lists --}}
                <div class="col-12 col-lg-9 mt-4">
                    <div id="listsContainer" class="" data-body=".js-lists-body" data-view-data-path="{{ $pageBasePath }}">
                        <div class="js-lists-body row">
                            @include("design_1.web.upcoming_courses.components.cards.grids.index", ['upcomingCourses' => $upcomingCourses, 'gridCardClassName' => 'col-12 col-md-6 col-lg-4 mt-24'])
                        </div>

                        {{-- Pagination --}}
                        <div id="pagination" class="js-ajax-pagination" data-container-id="listsContainer" data-container-items=".js-lists-body">
                            {!! $pagination !!}
                        </div>
                    </div>


                    {{-- Seo Content --}}
                    @if(!empty($seoSettings['bottom_seo_title']) and !empty($seoSettings['bottom_seo_content']))
                        <section class="bg-gray-100 p-16 rounded-24 border-gray-200 mt-48">
                            <h3 class="font-14">{{ $seoSettings['bottom_seo_title'] }}</h3>
                            <div class="mt-12 text-gray-500">{!! nl2br($seoSettings['bottom_seo_content']) !!}</div>
                        </section>
                    @endif
                </div>

            </div>
        </form>

    </main>

@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/wrunner-html-range-slider-with-2-handles/js/wrunner-jquery.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
    <script src="{{ getDesign1ScriptPath("range_slider_helpers") }}"></script>

    <script src="{{ getDesign1ScriptPath("upcoming_courses_lists") }}"></script>
@endpush
