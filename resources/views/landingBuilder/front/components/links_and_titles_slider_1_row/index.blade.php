@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("links_and_titles_slider_1_row") }}">
    @endpush

    <div class="links-and-titles-slider-1-row-section position-relative {{ (!empty($contents['card_style']) and $contents['card_style'] == "rotate") ? 'taps-with-rotate' : '' }}">

        @if(!empty($contents['title_items']) and is_array($contents['title_items']))
            @include('landingBuilder.front.components.links_and_titles_slider_1_row.tape', [
                'className' => (!empty($contents['card_color']) and $contents['card_color'] == "secondary") ? 'secondary-tape' : 'primary-tape',
                'contents' => $contents,
                'reverseDirection' => false
            ])
        @endif

    </div>
@endif
