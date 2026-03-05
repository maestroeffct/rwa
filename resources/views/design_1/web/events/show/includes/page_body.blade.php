@php
    $activePageTab = request()->get("tab", 'information');
@endphp

<div class="custom-tabs mt-16">
    <div class="event-tabs-card position-relative">
        <div class="event-tabs-card__mask"></div>

        <div class="position-relative d-flex align-items-center gap-20 gap-lg-40 flex-wrap bg-white px-16 px-lg-20 rounded-12 z-index-2 w-100">
            <div id="showEventAboutTab" class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "information") ? 'active' : '' }}" data-tab-toggle data-tab-href="#aboutEventTab">
                <span class="">{{ trans('update.about_event') }}</span>
            </div>

            <div class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "comments") ? 'active' : '' }}" data-tab-toggle data-tab-href="#commentsTab">
                <span class="ml-4">{{ trans('panel.comments') }}</span>

                <span class="event-tab-counter d-flex-center p-4 rounded-8 ml-4 font-12">
                    {{ (!empty($eventComments) and !empty($eventComments['comments_count'])) ? $eventComments['comments_count'] : 0 }}
                </span>
            </div>

            <div id="showEventReviewsTab" class="navbar-item d-flex-center cursor-pointer {{ ($activePageTab == "reviews") ? 'active' : '' }}" data-tab-toggle data-tab-href="#reviewsTab">
                <span class="ml-4">{{ trans('product.reviews') }}</span>

                <span class="event-tab-counter d-flex-center p-4 rounded-8 ml-4 font-12">
                    {{ (!empty($eventReviews) and !empty($eventReviews['reviews_count'])) ? $eventReviews['reviews_count'] : 0 }}
                </span>
            </div>
        </div>
    </div>

    <div class="custom-tabs-body mt-16">

        <div class="custom-tabs-content {{ ($activePageTab == "information") ? 'active' : '' }}" id="aboutEventTab">
            @include('design_1.web.events.show.tabs.about')
        </div>

        <div class="custom-tabs-content {{ ($activePageTab == "comments") ? 'active' : '' }}" id="commentsTab">
            @include('design_1.web.events.show.tabs.comments')
        </div>

        <div class="custom-tabs-content {{ ($activePageTab == "reviews") ? 'active' : '' }}" id="reviewsTab">
            @include('design_1.web.events.show.tabs.reviews')
        </div>
    </div>

</div>
