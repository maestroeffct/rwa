<div class="event-bottom-fixed-card bg-white">
    <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between h-100">
        <div class="d-flex align-items-center mb-16 mb-lg-0">
            <div class="event-bottom-fixed-card__event-img rounded-8">
                <img src="{{ $event->thumbnail }}" class="img-cover rounded-8" alt="{{ $event->title }}">
            </div>
            <div class="ml-8">
                <div class="font-12 text-gray-500">{{ trans('update.you_are_viewing') }}</div>
                <div class="mt-4 font-14 font-weight-bold">{{ $event->title }}</div>
            </div>
        </div>

        @if($event->checkUserHasBought())
            <a href="/panel/events/my-purchases" target="_blank" class="btn btn-primary btn-lg">{{ trans('update.view_tickets') }}</a>
        @else
            <button type="button" class="js-scroll-to-event-tickets-btn btn btn-primary btn-lg">{{ trans('update.attend_event') }}</button>
        @endif
    </div>
</div>

<div class="event-bottom-fixed-card__progress">
    <div class="progress-line"></div>
</div>
