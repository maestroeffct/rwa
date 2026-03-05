<a href="{{ $eventSpeaker->link }}" target="_blank" class="">
    <div class="event-speaker-card position-relative">
        <img src="{{ $eventSpeaker->image }}" alt="{{ $eventSpeaker->name }}" class="img-cover rounded-16">

        <div class="event-speaker-card__content d-flex flex-column justify-content-end p-24">
            <h5 class="font-16 text-white">{{ $eventSpeaker->name }}</h5>
            <p class="mt-4 text-white">{{ truncate($eventSpeaker->description, 60) }}</p>
        </div>
    </div>
</a>
