@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("trending_categories") }}">
    @endpush

    <div class="trending-categories-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>

        <div class="container position-relative">

            @if(!empty($contents['floating_background']))
                <div class="trending-categories-section__floating-background d-flex-center">
                    <img src="{{ $contents['floating_background'] }}" alt="floating_background" class="">
                </div>
            @endif


            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="mt-8 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                @endif
            </div>

            {{-- Trending Categories --}}
            @if(!empty($contents['trending_categories']) and is_array($contents['trending_categories']))
                <div class="row">
                    @foreach($contents['trending_categories'] as $trendingCategoryData)
                        @if(!empty($trendingCategoryData['category']))
                            @php
                                $trendingCategory = \App\Models\TrendCategory::query()->where('id', $trendingCategoryData['category'])->first();
                            @endphp

                            @if(!empty($trendingCategory))
                                <div class="col-6 col-md-4 col-lg-3 mt-24">
                                    <a href="{{ $trendingCategory->category->getUrl() }}" target="_blank">
                                        <div class="trending-categories-section__item-card position-relative">
                                            <div class="trending-categories-section__item-card-mask"></div>

                                            <div class="position-relative bg-white p-16 rounded-24 z-index-2">
                                                <div class="d-inline-flex-center size-48 rounded-circle " style="background-color: {{ $trendingCategory->color }}">
                                                    <img src="{{ $trendingCategory->icon }}" alt="{{ $trendingCategory->category->title }}" class="img-fluid" width="24px" height="24px">
                                                </div>

                                                <h4 class="mt-16 font-20 text-dark">{{ $trendingCategory->category->title }}</h4>
                                                <div class="mt-8 font-16 text-gray-500">{{ trans('update.count_courses', ['count' => $trendingCategory->category->getCoursesCount()]) }}</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif
