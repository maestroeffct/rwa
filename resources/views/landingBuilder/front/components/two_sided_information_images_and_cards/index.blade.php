@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $twoSidedInformationImagesCardsBackgroundColor = "secondary";
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        if (!empty($contents['background_color'])) {
            $twoSidedInformationImagesCardsBackgroundColor = $contents['background_color'];
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("two_sided_information_images_and_cards") }}">
    @endpush

    <div class="two-sided-information-images-and-cards-section position-relative" style="background-color: var({{ "--".$twoSidedInformationImagesCardsBackgroundColor }}); {{ (!empty($contents['background']) ? "background-image: url({$contents['background']}); " : '') }}">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6 py-80">
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                        <div class="d-inline-flex-center py-8 px-16 rounded-8 border-white bg-white-20 font-12 text-white">{{ $contents['main_content']['pre_title'] }}</div>
                    @endif

                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                        <h2 class="mt-16 font-44 text-white">{{ $contents['main_content']['title'] }}</h2>
                    @endif

                    @if(!empty($contents['specific_information']) and is_array($contents['specific_information']))
                        <div class="row mt-8">
                            @foreach($contents['specific_information'] as $informationData)
                                @if(!empty($informationData['title']) and !empty($informationData['enable']) and $informationData['enable'] == "on")
                                    <div class="col-12 col-lg-6 mt-16">
                                        <div class="two-sided-information-images-and-cards-section__specific-information-card d-flex flex-column align-items-start bg-white p-24 rounded-24">
                                            <div class="d-flex align-items-start justify-content-between gap-32 w-100">
                                                <div class="">
                                                    <h4 class="font-16 text-dark">{{ $informationData['title'] }}</h4>

                                                    @if(!empty($informationData['subtitle']))
                                                        <p class="mt-8 font-14 text-gray-500">{{ $informationData['subtitle'] }}</p>
                                                    @endif
                                                </div>

                                                <div class="size-40">
                                                    @if(!empty($informationData['icon']))
                                                        <img src="{{ $informationData['icon'] }}" alt="icon" class="img-fluid" width="40px" height="40px">
                                                    @endif
                                                </div>
                                            </div>

                                            @if(!empty($informationData['description']))
                                                <p class="mt-16 mb-20 font-16 text-gray-500">{!! nl2br(truncate($informationData['description'], 150)) !!}</p>
                                            @endif

                                            @if(!empty($informationData['link_title']) and !empty($informationData['url']))
                                                <a href="{{ $informationData['url'] }}" class="btn-flip-effect btn-flip-effect__right-0 btn-flip-effect__text-dark d-inline-flex align-items-center gap-4 text-dark mt-auto" data-text="{{ $informationData['link_title'] }}">
                                                    <x-iconsax-lin-arrow-right class="icons text-dark" width="16px" height="16px"/>
                                                    <span class="btn-flip-effect__text">{{ $informationData['link_title'] }}</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-lg-1"></div>

                <div class="col-12 col-lg-5 d-flex justify-content-end">
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['image']))
                        <div class="two-sided-information-images-and-cards-section__side-img">
                            <img src="{{ $contents['main_content']['image'] }}" alt="image" class="img-cover">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
