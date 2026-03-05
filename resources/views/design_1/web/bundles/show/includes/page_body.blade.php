@php
    $activePageTab = request()->get("tab", 'information');
@endphp

<div class="custom-tabs mt-16">
    <div class="course-tabs-card position-relative">
        <div class="course-tabs-card__mask"></div>

        <div class="position-relative d-flex align-items-center gap-20 gap-lg-40 flex-wrap bg-white px-16 px-lg-20 rounded-12 z-index-2 w-100">
            <div class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "information") ? 'active' : '' }}" data-tab-toggle data-tab-href="#aboutBundleTab">
                <span class="">{{ trans('update.about_bundle') }}</span>
            </div>

            <div class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "courses") ? 'active' : '' }}" data-tab-toggle data-tab-href="#coursesTab">
                <span class="">{{ trans('update.courses') }}</span>
            </div>

            <div class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "comments") ? 'active' : '' }}" data-tab-toggle data-tab-href="#commentsTab">
                <span class="ml-4">{{ trans('panel.comments') }}</span>

                <span class="course-tab-counter d-flex-center p-4 rounded-8 ml-4 font-12">
                    {{ (!empty($bundleComments) and !empty($bundleComments['comments_count'])) ? $bundleComments['comments_count'] : 0 }}
                </span>
            </div>

            <div class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "reviews") ? 'active' : '' }}" data-tab-toggle data-tab-href="#reviewsTab">
                <span class="ml-4">{{ trans('product.reviews') }}</span>
            </div>
        </div>
    </div>

    <div class="custom-tabs-body mt-16">

        <div class="custom-tabs-content {{ ($activePageTab == "information") ? 'active' : '' }}" id="aboutBundleTab">
            @include('design_1.web.bundles.show.tabs.about')
        </div>

        <div class="custom-tabs-content {{ ($activePageTab == "courses") ? 'active' : '' }}" id="coursesTab">
            @include('design_1.web.bundles.show.tabs.courses')
        </div>

        <div class="custom-tabs-content {{ ($activePageTab == "comments") ? 'active' : '' }}" id="commentsTab">
            @include('design_1.web.bundles.show.tabs.comments')
        </div>

        <div class="custom-tabs-content {{ ($activePageTab == "reviews") ? 'active' : '' }}" id="reviewsTab">
            @include('design_1.web.bundles.show.tabs.reviews')
        </div>
    </div>

</div>
