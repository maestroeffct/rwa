@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("linked_images_3x") }}">
    @endpush

    <div class="linked-images-3x-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
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

            @if(!empty($contents['specific_banners']) and is_array($contents['specific_banners']))
                <div class="row mt-16">
                    @foreach($contents['specific_banners'] as $bannerData)
                        @if(!empty($bannerData['title']) and !empty($bannerData['enable']) and $bannerData['enable'] == "on")
                            <div class="col-12 col-md-6 col-lg-4 mt-16">

                                @if(!empty($bannerData['url']))
                                    <a href="{{ $bannerData['url'] }}" target="_blank" class="">
                                        @endif

                                        <div class="linked-images-3x-section__item-card rounded-24 bg-gray-100">
                                            @if(!empty($bannerData['image']))
                                                <img src="{{ $bannerData['image'] }}" alt="image" class="img-cover rounded-24">
                                            @endif

                                            <div class="linked-images-3x-section__item-card-overlay d-flex-center flex-column gap-4 text-center {{ (!empty($contents['enable_overlay'])) ? 'enable-overlay' : '' }}">
                                                <div class="overlay-contents">
                                                    <h4 class="font-20 text-white">{{ $bannerData['title'] }}</h4>
                                                </div>

                                                <div class="line-divider"></div>
                                            </div>
                                        </div>

                                        @if(!empty($bannerData['url']))
                                    </a>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif


        </div>
    </div>
@endif
