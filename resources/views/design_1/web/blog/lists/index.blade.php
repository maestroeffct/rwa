@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("swiperjs") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("blog_lists") }}">
@endpush

@php
    if(!empty($selectedCategory) and !empty($selectedCategory->cover_image)) {
        $pageHeroImage = $selectedCategory->cover_image;
    } else {
        $pageHeroImage = getThemePageBackgroundSettings('blog_lists');
    }

        $pageOverlayImage = getThemePageBackgroundSettings('blog_lists_overlay_image');
@endphp

@section("content")
    <main class="pb-56">
        <section class="blog-lists-hero position-relative">
            <div class="blog-lists-hero__mask"></div>
            <img src="{{ $pageHeroImage }}" class="img-cover" alt="{{ trans('home.blog') }}"/>
        </section>

        {{-- Header --}}
        <div class="container">
            {{-- Header --}}
            @include('design_1.web.blog.lists.includes.header')

            {{-- Featured Categories --}}
            @include('design_1.web.blog.lists.includes.featured_categories')

            {{-- Featured Posts --}}
            @include('design_1.web.blog.lists.includes.featured_posts')


        </div>


        <form action="/blog" class="js-get-view-data-by-timeout-change container" data-container-id="listsContainer">

            <div class="row">
                {{-- Left Filters --}}
                <div class="col-12 col-lg-3 mt-48">
                    @include("design_1.web.blog.lists.includes.left_filters")
                </div>

                {{-- Blog Lists --}}
                <div class="col-12 col-lg-9 mt-12">
                    <div id="listsContainer" class="" data-body=".js-lists-body" data-view-data-path="/blog">
                        <div class="js-lists-body row">
                            @include('design_1.web.blog.components.cards.grids.index',['posts' => $posts, 'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-36"])
                        </div>

                        <!-- Pagination -->
                        <div id="pagination" class="js-ajax-pagination" data-container-id="listsContainer" data-container-items=".js-lists-body">
                            {!! $pagination !!}
                        </div>
                    </div>
                </div>

            </div>
        </form>

        {{-- Top Authors --}}
        <div class="container mt-104">
            @include('design_1.web.blog.lists.includes.top_authors')
        </div>

    </main>

@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/wrunner-html-range-slider-with-2-handles/js/wrunner-jquery.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="{{ getDesign1ScriptPath("swiper_slider") }}"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
    <script src="{{ getDesign1ScriptPath("range_slider_helpers") }}"></script>

    <script src="{{ getDesign1ScriptPath("blog_lists") }}"></script>
@endpush
