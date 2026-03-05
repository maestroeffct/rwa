@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("links_and_images_6_items_per_row") }}">
    @endpush

    <div class="links-and-images-6-items-per-row-section position-relative">
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

            @if(!empty($contents['specific_links']) and is_array($contents['specific_links']))
                <div class="row mt-24">
                    @foreach($contents['specific_links'] as $linkData)
                        @if(!empty($linkData['title']) and !empty($linkData['image']) and !empty($linkData['url'])  and !empty($linkData['enable']) and $linkData['enable'] == "on")
                            <div class="col-6 col-md-4 col-lg-2 mb-32">
                                <a href="{{ $linkData['url'] }}" class="links-and-images-6-items-per-row-section__link-box d-flex flex-column w-100" target="_blank">
                                    <div class="links-and-images-6-items-per-row-section__link-box-img rounded-16 bg-white">
                                        <img src="{{ $linkData['image'] }}" alt="image " class="img-cover rounded-16">
                                    </div>
                                    <h4 class="mt-12 font-16 text-dark text-center">{{ $linkData['title'] }}</h4>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif
