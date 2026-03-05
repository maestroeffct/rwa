<div class="course-prerequisite-card position-relative rounded-12 bg-gray-100">
    <div class="course-prerequisite-card__mask"></div>

    <div class="course-prerequisite-card__required align-items-center rounded-32 bg-accent" data-tippy-content="{{ trans('update.you_need_to_pass_this_prerequisite_to_purchase_this_course') }}">
        <x-iconsax-bul-teacher class="icons text-white" width="20px" height="20px"/>
        <span class="ml-4 text-white font-12">{{ trans('public.required') }}</span>
    </div>

    <img src="{{ $courseItem->getImage() }}" class="img-cover rounded-12" alt="{{ $courseItem->title }}">

    <div class="course-prerequisite-card__body d-flex flex-column p-12">
        <a href="{{ $courseItem->getUrl() }}">
            <h3 class="card-title font-14 font-weight-bold text-white">{{ clean($courseItem->title,'title') }} {{ $courseItem->id }}</h3>
        </a>

        <div class="d-flex align-items-center justify-content-between mt-12">
            @include('design_1.web.components.rate', ['rate' => $courseItem->getRate(), 'rateClassName' => ''])

            <div class="d-flex align-items-center font-16 font-weight-bold text-primary">
                @include("design_1.web.courses.components.price_horizontal", ['courseRow' => $courseItem])
            </div>
        </div>
    </div>
</div>
