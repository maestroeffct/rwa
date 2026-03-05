@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $useRounded = (!empty($contents['enable_radius']) and $contents['enable_radius'] == "on");
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("banner_full_width") }}">
    @endpush

    <div class="banner-full-width-section position-relative">
        @if(!empty($contents['banner_style']) and $contents['banner_style'] == "container")
            <div class="container">
                <div class="row">
                    @if(!empty($contents['specific_banners']) and is_array($contents['specific_banners']))
                        @foreach($contents['specific_banners'] as $bannerData)
                            @if(!empty($bannerData['image']))
                                <div class="col-12 mt-24">
                                    <a href="{{ !empty($bannerData['url']) ? $bannerData['url'] : '' }}" target="_blank" class="">
                                        <div class="banner-full-width-section__container-img {{ $useRounded ? 'rounded-24' : '' }}">
                                            <img src="{{ $bannerData['image'] }}" alt="image" class="img-cover {{ $useRounded ? 'rounded-24' : '' }}">
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @else
            @if(!empty($contents['specific_banners']) and is_array($contents['specific_banners']))
                @foreach($contents['specific_banners'] as $bannerData2)
                    @if(!empty($bannerData2['image']))
                        <a href="{{ !empty($bannerData2['url']) ? $bannerData2['url'] : '' }}" target="_blank" class="d-block mt-24">
                            <div class="banner-full-width-section__full-width-img {{ $useRounded ? 'rounded-24' : '' }}">
                                <img src="{{ $bannerData2['image'] }}" alt="image" class="img-cover {{ $useRounded ? 'rounded-24' : '' }}">
                            </div>
                        </a>
                    @endif
                @endforeach
            @endif
        @endif
    </div>
@endif
