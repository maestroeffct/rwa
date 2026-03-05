@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
        $bestRateWebinars = $frontComponentsDataMixins->getBestRatedCoursesData();
    @endphp

    @if($bestRateWebinars->isNotEmpty())

        @push('styles_top')
            <link rel="stylesheet" href="{{ getLandingComponentStylePath("best_rated_courses") }}">
        @endpush

        <div class="container">
            <div class="best-rated-courses-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>

                @if(!empty($contents['main_content']['icon']))
                    <div class="best-rated-courses-section__floating-icon d-flex-center">
                        <img src="{{ $contents['main_content']['icon'] }}" alt="icon">
                    </div>
                @endif


                <div class="row h-100">
                    <div class="col-12 col-md-6 col-lg-3 position-relative h-100 pt-0 pt-lg-48">
                        @if(!empty($contents['main_content']))
                            @if(!empty($contents['main_content']['title']))
                                <h2 class="font-32 text-white mr-8">{{ $contents['main_content']['title'] }}</h2>
                            @endif

                            @if(!empty($contents['main_content']['subtitle']))
                                <p class="mt-20 text-white opacity-70 font-16 mr-8">{!! nl2br($contents['main_content']['subtitle']) !!}</p>
                            @endif

                            <a href="/classes?sort=best_rates" target="_blank" class="btn-flip-effect btn-flip-effect__no-side d-inline-flex font-16 align-items-center gap-8 font-weight-bold text-white mt-16" data-text="{{ trans('update.view_more') }}">
                                <span class="btn-flip-effect__text">{{ trans('update.view_more') }}</span>
                                <x-iconsax-lin-arrow-right class="icons text-white" width="24px" height="24px"/>
                            </a>
                        @endif
                    </div>

                    {{-- Newest Courses --}}
                    @include('design_1.web.courses.components.cards.grids.index',['courses' => $bestRateWebinars, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-24"])
                </div>

            </div>
        </div>
    @endif
@endif
