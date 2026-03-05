@if(!empty($featureWebinars) and !$featureWebinars->isEmpty())
    @php
        $featureWebinarsBackground = getThemePageBackgroundSettings("categories_courses_lists_featured_courses_background");
        $featureWebinarsOverlayImage = getThemePageBackgroundSettings("categories_courses_lists_featured_courses_overlay_image");
        $featureWebinarsItems = $featureWebinars->pluck("webinar");
    @endphp

    <div class="courses-lists-featured bg-primary mt-56" @if(!empty($featureWebinarsBackground)) style="background-image: url('{{ $featureWebinarsBackground }}')" @endif>
        <div class="container py-40 h-100">
            <div class="row w-100 h-100">
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="d-flex justify-content-center flex-column text-left w-100 h-100">
                        <div class="courses-lists-featured__arrow">
                            <img src="/assets/design_1/img/courses/featured_courses_arrow.svg" alt="featured_courses_arrow" class="">
                        </div>

                        <h3 class="font-24 font-weight-bold text-white">{{ trans('update.featured_courses') }}</h3>
                        <div class="mt-8 text-white">{{ trans('update.explore_hand_picked_and_popular_courses') }}</div>

                        @if(!empty($featureWebinarsOverlayImage))
                            <div class="d-flex align-items-center mt-32 size-200">
                                <img src="{{ $featureWebinarsOverlayImage }}" alt="{{ trans('update.overlay_image') }}" class="img-fluid">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-8 col-lg-9 mt-16 mt-md-0">
                    <div class="position-relative">
                        <div class="swiper-container js-make-swiper top-featured-courses pb-0"
                             data-item="top-featured-courses"
                             data-autoplay="true"
                             data-breakpoints="1440:3.2,769:2.2,320:1.2"
                        >
                            <div class="swiper-wrapper py-0  mx-16 mx-md-32">
                                @include('design_1.web.courses.components.cards.grids.index',['courses' => $featureWebinarsItems, 'gridCardClassName' => "swiper-slide"])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
