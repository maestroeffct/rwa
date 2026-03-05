<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{trans('admin/main.total_reviews')}}</span>
                    <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                        <x-iconsax-bul-star class="icons text-primary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalReviews }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{trans('admin/main.published_reviews')}}</span>
                    <div class="d-flex-center size-48 bg-success-30 rounded-12">
                        <x-iconsax-bul-star class="icons text-success" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $publishedReviews }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{trans('admin/main.rates_average')}}</span>
                    <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                        <x-iconsax-bul-calculator class="icons text-secondary" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $ratesAverage }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card-statistic">
            <div class="card-statistic__mask"></div>
            <div class="card-statistic__wrap">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-gray-500 mt-8">{{trans('admin/main.classes_without_review')}}</span>
                    <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                        <x-iconsax-bul-video-play class="icons text-danger" width="24px" height="24px"/>
                    </div>
                </div>
                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $classesWithoutReview }}</h5>
            </div>
        </div>
    </div>
</div>
