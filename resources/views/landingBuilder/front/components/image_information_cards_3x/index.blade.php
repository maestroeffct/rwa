@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("image_information_cards_3x") }}">
    @endpush

    <div class="image-information-cards-3x-section position-relative">
        <div class="container">
            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="mt-8 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                    <p class="mt-16 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                @endif
            </div>

            @if(!empty($contents['specific_information']) and is_array($contents['specific_information']))
                <div class="row mt-8">
                    @foreach($contents['specific_information'] as $informationData)
                        @if(!empty($informationData['title']) and !empty($informationData['enable']) and $informationData['enable'] == "on")
                            <div class="col-12 col-md-4 mt-24">
                                <a href="{{ !empty($informationData['url']) ? $informationData['url'] : '#!' }}" target="_blank" class="">
                                    <div class="image-information-cards-3x-section__item-card position-relative rounded-24">
                                        @if(!empty($informationData['image']))
                                            <img src="{{ $informationData['image'] }}" alt="image" class="img-cover rounded-24">
                                        @endif

                                        <div class="image-information-cards-3x-section__item-card-footer d-flex flex-column align-items-start justify-content-end">
                                            <h3 class="font-24 text-white">{{ $informationData['title'] }}</h3>

                                            @if(!empty($informationData['subtitle']))
                                                <p class="mt-8 font-16 text-white ">{{ $informationData['subtitle'] }}</p>
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
    </div>
@endif
