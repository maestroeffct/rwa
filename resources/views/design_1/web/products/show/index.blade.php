@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("swiperjs") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("css_stars") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("installment_card") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("reviews_and_comments") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("product_show") }}">
@endpush


@section('content')
    <div class="container pb-80 mt-120">

        {{-- Installments --}}
        @if(!empty($installments) and count($installments) and getInstallmentsSettings('installment_plans_position') == 'top_of_page')
            @foreach($installments as $installmentRow)
                @include('design_1.web.installments.includes.card',[
                       'installment' => $installmentRow,
                       'itemPrice' => $product->getPrice(),
                       'itemId' => $product->id,
                       'itemType' => 'product',
                       'className' => $loop->first ? 'mt-48' : '',
                   ])
            @endforeach
        @endif


        <div class="row">
            {{-- Images --}}
            <div class="col-12 col-lg-6">
                @include('design_1.web.products.show.includes.images')
            </div>

            {{-- Main Info --}}
            <div class="col-12 col-lg-6 mt-32 mt-lg-0">
                @include('design_1.web.products.show.includes.main_info')
            </div>
        </div>

        {{-- Promotions Banners --}}
        @include("design_1.web.products.show.includes.promotions")

        @php
            $activePageTab = request()->get("tab", 'description');
        @endphp

        {{-- Tabs --}}
        <div class="custom-tabs mt-16">
            <div class="product-show-tabs-card position-relative">
                <div class="product-show-tabs-card__mask"></div>

                <div class="position-relative product-show-tabs-card__items d-flex align-items-center gap-20 gap-lg-40 bg-white px-20 rounded-12 z-index-2 w-100">
                    <div class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "description") ? 'active' : '' }}" data-tab-toggle data-tab-href="#productDescriptionTab">
                        <span class="">{{ trans('public.description') }}</span>
                    </div>

                    <div class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "specifications") ? 'active' : '' }}" data-tab-toggle data-tab-href="#productSpecificationsTab">
                        <span class="">{{ trans('update.specifications') }}</span>
                    </div>

                    <div class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "comments") ? 'active' : '' }}" data-tab-toggle data-tab-href="#productCommentsTab">
                        <span class="ml-4">{{ trans('panel.comments') }}</span>
                    </div>

                    <div class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "reviews") ? 'active' : '' }}" data-tab-toggle data-tab-href="#productReviewsTab">
                        <span class="ml-4">{{ trans('product.reviews') }}</span>
                    </div>

                    <div class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "seller") ? 'active' : '' }}" data-tab-toggle data-tab-href="#productSellerTab">
                        <span class="ml-4">{{ trans('update.seller') }}</span>
                    </div>

                    @if(!empty($product->files) and count($product->files) and $product->checkUserHasBought())
                        <div class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "files") ? 'active' : '' }}" data-tab-toggle data-tab-href="#productFilesTab">
                            <span class="ml-4">{{ trans('public.files') }}</span>
                        </div>
                    @endif

                </div>
            </div>

            <div class="custom-tabs-body mt-16">

                <div class="custom-tabs-content {{ ($activePageTab == "description") ? 'active' : '' }}" id="productDescriptionTab">
                    @include('design_1.web.products.show.tabs.about')
                </div>

                <div class="custom-tabs-content {{ ($activePageTab == "specifications") ? 'active' : '' }}" id="productSpecificationsTab">
                    @include('design_1.web.products.show.tabs.specifications')
                </div>

                <div class="custom-tabs-content {{ ($activePageTab == "comments") ? 'active' : '' }}" id="productCommentsTab">
                    @include('design_1.web.products.show.tabs.comments')
                </div>

                <div class="custom-tabs-content {{ ($activePageTab == "reviews") ? 'active' : '' }}" id="productReviewsTab">
                    @include('design_1.web.products.show.tabs.reviews')
                </div>

                <div class="custom-tabs-content {{ ($activePageTab == "seller") ? 'active' : '' }}" id="productSellerTab">
                    @include('design_1.web.products.show.tabs.seller')
                </div>

                @if(!empty($product->files) and count($product->files) and $product->checkUserHasBought())
                    <div class="custom-tabs-content {{ ($activePageTab == "files") ? 'active' : '' }}" id="productFilesTab">
                        @include('design_1.web.products.show.tabs.files')
                    </div>
                @endif
            </div>

        </div>


        @if(
           !empty(getFeaturesSettings("frontend_coupons_display_type")) and
           getFeaturesSettings("frontend_coupons_display_type") == "after_content" and
           !empty($instructorDiscounts) and
           count($instructorDiscounts)
        )
            <div class="mt-32">
                @include('design_1.web.instructor_discounts.cards', ['allDiscounts' => $instructorDiscounts, 'discountCardClassName2' => "mt-16"])
            </div>
        @endif


        {{-- Installments --}}
        @if(!empty($installments) and count($installments) and getInstallmentsSettings('installment_plans_position') == 'bottom_of_page')
            @foreach($installments as $installmentRow)
                @include('design_1.web.installments.includes.card',[
                       'installment' => $installmentRow,
                       'itemPrice' => $product->getPrice(),
                       'itemId' => $product->id,
                       'itemType' => 'product',
                       'className' => $loop->first ? 'mt-48' : '',
                   ])
            @endforeach
        @endif

        {{-- Related Products --}}
        @if(!empty($product->relatedProducts) and $product->relatedProducts->count() > 0)
            @php
                $relatedProducts = [];

                foreach($product->relatedProducts as $relatedProduct) {
                    if(!empty($relatedProduct->product)) {
                        $relatedProducts[] = $relatedProduct->product;
                    }
                }
            @endphp

            @if(count($relatedProducts))
                <div class="mt-48">
                    <div class="">
                        <h2 class="font-16 font-weight-bold">{{ trans('update.related_products') }}</h2>
                        <p class="mt-4 font-12 text-gray-500">{{ trans('update.explore_courses_we_published_currently_and_enjoy_updated_information') }}</p>
                    </div>

                    <div class="row">
                        @include('design_1.web.products.components.cards.grids.index',['products' => $relatedProducts, 'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-16"])
                    </div>
                </div>
            @endif
        @endif

        {{-- Related Courses --}}
        @if(!empty($product->relatedCourses) and $product->relatedCourses->count() > 0)
            @php
                $relatedCourses = [];

                foreach($product->relatedCourses as $relatedCourse) {
                    if(!empty($relatedCourse->course)) {
                        $relatedCourses[] = $relatedCourse->course;
                    }
                }
            @endphp

            @if(count($relatedCourses))
                <div class="mt-48">
                    <div class="">
                        <h2 class="font-16 font-weight-bold">{{ trans('update.related_courses') }}</h2>
                        <p class="mt-4 font-12 text-gray-500">{{ trans('update.explore_courses_we_published_currently_and_enjoy_updated_information') }}</p>
                    </div>

                    <div class="row">
                        @include('design_1.web.courses.components.cards.grids.index',['courses' => $relatedCourses, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-16"])
                    </div>
                </div>
            @endif
        @endif

        {{-- Ads Bannaer --}}
        @include('design_1.web.components.advertising_banners.page_banner')
        {{-- ./ Ads Bannaer --}}

    </div>
@endsection

@push('scripts_bottom')
    <script>
        var closeLang = '{{ trans('public.close') }}';
        var shareLang = '{{ trans('public.share') }}';
        var reportCommentLang = '{{ trans('update.report_comment') }}';
        var reportLang = '{{ trans('panel.report') }}';
    </script>

    <script src="/assets/default/vendors/barrating/jquery.barrating.min.js"></script>
    <script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/time-counter-down.min.js"></script>
    <script src="{{ getDesign1ScriptPath("swiper_slider") }}"></script>

    <script src="{{ getDesign1ScriptPath("reviews") }}"></script>
    <script src="{{ getDesign1ScriptPath("comments") }}"></script>
    <script src="{{ getDesign1ScriptPath("product_show") }}"></script>
@endpush
