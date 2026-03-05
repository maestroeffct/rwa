@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("courses_lists") }}">
@endpush

@section("content")
    <main class="pb-120">

        @php
            $pageHeroImage = !empty($category->cover_image) ? $category->cover_image : getThemePageBackgroundSettings('categories');
        @endphp

        <section class="courses-lists-hero position-relative">
            <div class="courses-lists-hero__mask"></div>
            <img src="{{ $pageHeroImage }}" class="img-cover" alt="{{ trans('update.search_categories') }}"/>
        </section>


        {{-- Header --}}
        <div class="container">
            <div class="courses-lists-header position-relative">
                <div class="courses-lists-header__mask"></div>
                <div class="position-relative d-flex align-items-start bg-white rounded-32 z-index-2">
                    <div class="d-flex flex-column p-32">
                        <div class="d-flex-center size-64 rounded-12 " style="background-color: {{ $category->icon2_box_color }}">
                            <img src="{{ $category->icon2 }}" alt="{{ $category->title }}" class="img-fluid" width="32px" height="32px">
                        </div>

                        <div class="d-flex align-items-center mt-16 text-gray-500">
                            <a href="/" class="text-gray-500">{{ getPlatformName() }}</a>
                            <x-iconsax-lin-arrow-right-1 class="mx-4" width="16px" height="16px"/>
                            <span class="">{{ trans('update.courses') }}</span>
                        </div>

                        <h1 class="font-24 font-weight-bold mt-12">{{ $category->title }}</h1>
                        <div class="font-12 text-gray-500 mt-8">{{ $category->subtitle }}</div>
                    </div>

                    <div class="courses-lists-header__overlay-img">
                        <img src="{{ $category->overlay_image }}" alt="{{ $category->title }}" class="img-cover">
                    </div>
                </div>
            </div>
        </div>

        {{-- Featured Courses --}}
        @include("design_1.web.courses.lists.includes.featured_courses")

        <form action="{{ $pageBasePath }}" class="js-get-view-data-by-timeout-change container mt-24" data-container-id="listsContainer">
            {{-- Top Filters --}}
            @include("design_1.web.courses.lists.includes.top_filters")

            <div class="row">
                {{-- Left Filters --}}
                <div class="col-12 col-lg-3 mt-28">
                    @include("design_1.web.courses.lists.includes.left_filters")
                </div>

                {{-- Courses Lists --}}
                <div class="col-12 col-lg-9 mt-4">
                    <div id="listsContainer" class="" data-body=".js-lists-body" data-view-data-path="{{ $pageBasePath }}">
                        <div class="js-lists-body row">
                            @if(request()->get('card') == "list")
                                @include('design_1.web.courses.components.cards.rows.index',['courses' => $courses, 'rowCardClassName' => "col-12 mt-24"])
                            @else
                                @include('design_1.web.courses.components.cards.grids.index',['courses' => $courses, 'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-24"])
                            @endif
                        </div>

                        {{-- Pagination --}}
                        <div id="pagination" class="js-ajax-pagination" data-container-id="listsContainer" data-container-items=".js-lists-body">
                            {!! $pagination !!}
                        </div>
                    </div>


                    {{-- Seo Content --}}
                    @if(!empty($category->bottom_seo_title) and !empty($category->bottom_seo_content))
                        <section class="bg-gray-100 p-16 rounded-24 border-gray-200 mt-48">
                            <h3 class="font-14">{{ $category->bottom_seo_title }}</h3>
                            <div class="mt-12 text-gray-500">{!! nl2br($category->bottom_seo_content) !!}</div>
                        </section>
                    @endif
                </div>
            </div>
        </form>
    </main>
@endsection


@push('scripts_bottom')
    <script src="/assets/vendors/wrunner-html-range-slider-with-2-handles/js/wrunner-jquery.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="{{ getDesign1ScriptPath("swiper_slider") }}"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="{{ getDesign1ScriptPath("range_slider_helpers") }}"></script>
    <script src="{{ getDesign1ScriptPath("courses_lists") }}"></script>
@endpush
