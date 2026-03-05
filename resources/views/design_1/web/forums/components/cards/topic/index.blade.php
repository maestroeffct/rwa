@php
    $cardName = "card_1";
@endphp

@push('styles_top')
    @if(empty($withoutStyles))
        <link rel="stylesheet" href="{{ getDesign1StylePath("topic_cards/{$cardName}") }}">
    @endif
@endpush

@foreach($forumTopics as $forumTopic)
    <div class="{{ !empty($cardClassName) ? $cardClassName : '' }}">
        @include("design_1.web.forums.components.cards.topic.{$cardName}", ['topic' => $forumTopic])
    </div>
@endforeach
