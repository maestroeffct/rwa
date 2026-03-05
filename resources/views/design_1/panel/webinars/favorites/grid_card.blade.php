@php
    $webinar = $favorite->webinar;
@endphp

<div class="panel-course-grid-card position-relative">
    <div class="panel-course-grid-card__image position-relative rounded-16 bg-gray-100">
        <img src="{{ $webinar->getImage() }}" alt="" class="img-cover rounded-16">

        <a href="/panel/courses/favorites/{{ $favorite->id }}/delete" class="delete-action is-live-course-icon d-flex-center size-64 rounded-circle">
            <x-iconsax-bol-heart class="icons text-danger" width="24px" height="24px"/>
        </a>
    </div>

    <div class="panel-course-grid-card__body position-relative px-16 pb-12">
        <div class="panel-course-grid-card__content is-favorites-card d-flex flex-column bg-white p-12 rounded-16">

            <a href="{{ $webinar->getUrl() }}" target="_blank">
                <h3 class="panel-course-grid-card__title font-14 text-dark">{{ $webinar->title }}</h3>
            </a>

            @include("design_1.web.components.rate", [
                    'rate' => round($webinar->getRate(),1),
                    'rateCount' => $webinar->reviews()->where('status', 'active')->count(),
                    'rateClassName' => 'mt-12',
                ])

            <div class="d-flex align-items-center my-16 ">
                <div class="size-32 rounded-circle bg-gray-100">
                    <img src="{{ $webinar->teacher->getAvatar(32) }}" alt="{{ $webinar->teacher->full_name }}" class="img-cover rounded-circle">
                </div>
                <div class="ml-8">
                    <h6 class="font-12 font-weight-bold">{{ $webinar->teacher->full_name }}</h6>
                    <p class="mt-2 font-12 text-gray-500">{{ $webinar->teacher->bio }}</p>
                </div>
            </div>


            <div class="d-flex align-items-center justify-content-between mt-auto border-top-gray-100 pt-12">

                <div class="d-flex align-items-center font-16 font-weight-bold text-success flex-1">
                    @if($webinar->price > 0)
                        @if($webinar->bestTicket() < $webinar->price)
                            <span class="">{{ handlePrice($webinar->bestTicket(), true, true, false, null, true) }}</span>
                            <span class="font-14 font-weight-400 text-gray-500 ml-8 text-decoration-line-through">{{ handlePrice($webinar->price, true, true, false, null, true) }}</span>
                        @else
                            <span class="">{{ handlePrice($webinar->price, true, true, false, null, true) }}</span>
                        @endif
                    @else
                        <span class="">{{ trans('public.free') }}</span>
                    @endif
                </div>

                <div class="d-flex align-items-center">
                    <x-iconsax-lin-clock-1 class="icons text-gray-500" width="16px" height="16px"/>
                    <span class="ml-2 font-12 text-gray-500">{{ convertMinutesToHourAndMinute($webinar->duration) }} {{ trans('home.hours') }}</span>
                </div>

            </div>
        </div>
    </div>

</div>
