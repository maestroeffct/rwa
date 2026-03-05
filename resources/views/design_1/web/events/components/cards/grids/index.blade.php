@php
    $cardName = getThemeContentCardStyle("event");
@endphp

@push('styles_top')
    @if(empty($withoutStyles))
        <link rel="stylesheet" href="{{ getDesign1StylePath("event_cards/{$cardName}") }}">
    @endif
@endpush

@foreach($events as $event)
    <div class="{{ !empty($gridCardClassName) ? $gridCardClassName : '' }}">
        @include("design_1.web.events.components.cards.grids.{$cardName}", ['event' => $event])
    </div>
@endforeach
