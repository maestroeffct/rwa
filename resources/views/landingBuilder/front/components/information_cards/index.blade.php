@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("information_cards") }}">
    @endpush

    <div class="information-cards-section position-relative container">
        @if(!empty($contents['information_cards']) and is_array($contents['information_cards']))
            <div class="row">
                @foreach($contents['information_cards'] as $informationCard)
                    @if(!empty($informationCard['title']))
                        <div class="col-12 col-md-4 information-cards-section__item-col">
                            <a href="{{ !empty($informationCard['url']) ? $informationCard['url'] : '#!' }}" target="_blank" class="d-flex w-100 h-100">
                                <div class="information-cards-section__item-card d-flex flex-column position-relative bg-white rounded-32 h-100 w-100">
                                    <div class="d-flex-center flex-column text-center px-32 pt-32 pb-24">
                                        <h3 class="font-24 text-dark">{{ $informationCard['title'] }}</h3>

                                        @if(!empty($informationCard['subtitle']))
                                            <p class="font-16 mt-12 text-gray-500">{!! nl2br($informationCard['subtitle']) !!}</p>
                                        @endif
                                    </div>

                                    <div class="information-cards-section__image mt-auto {{ (!empty($informationCard['enable_image_padding']) and $informationCard['enable_image_padding'] == "on") ? 'enable-image-padding' : '' }}">
                                        @if(!empty($informationCard['image']))
                                            <img src="{{ $informationCard['image'] }}" alt="image" class="img-cover">
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endif
