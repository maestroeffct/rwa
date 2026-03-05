@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("links_and_titles_slider_2_rows") }}">
    @endpush

    <div class="links-and-titles-slider-2-rows-section position-relative {{ (!empty($contents['card_style']) and $contents['card_style'] == "rotate") ? 'taps-with-rotate' : '' }}">

        @if(!empty($contents['title_items']) and is_array($contents['title_items']))
            @include('landingBuilder.front.components.links_and_titles_slider_2_rows.tape', ['className' => 'primary-tape', 'contents' => $contents, 'reverseDirection' => false])

            @include('landingBuilder.front.components.links_and_titles_slider_2_rows.tape', ['className' => 'secondary-tape', 'contents' => $contents, 'reverseDirection' => true])
        @endif

    </div>
@endif
