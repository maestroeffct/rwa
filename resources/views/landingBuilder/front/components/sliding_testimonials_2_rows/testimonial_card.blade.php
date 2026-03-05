<div class="sliding-testimonial-card position-relative">
    <div class="sliding-testimonial-card-mask"></div>
    <div class="position-relative z-index-2 d-flex flex-column bg-white p-12 rounded-24 w-100 h-100">
        <div class="sliding-testimonial-card__comment font-16 p-12 rounded-16 border-gray-100 text-gray-500 mb-12">{!! nl2br(truncate($testimonial->comment, 424)) !!}</div>

        <div class="d-flex align-items-center justify-content-between p-4 mt-auto">
            <div class="d-flex align-items-center">
                <div class="size-48 rounded-circle bg-gray-100">
                    <img src="{{ $testimonial->user_avatar }}" alt="{{ $testimonial->user_name }}" class="img-cover rounded-circle">
                </div>
                <div class="ml-8">
                    <h5 class="font-16 text-dark">{{ $testimonial->user_name }}</h5>
                    <p class="mt-2 font-14 text-gray-500">{{ $testimonial->user_bio }}</p>
                </div>
            </div>

            @include('design_1.web.components.rate', ['rate' => $testimonial->rate, 'showRateStars' => true, 'rateClassName' => ''])
        </div>
    </div>
</div>
