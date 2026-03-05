@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $useRounded = (!empty($contents['enable_radius']) and $contents['enable_radius'] == "on");
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("banner_3_items_per_row") }}">
    @endpush

    <div class="banner-3-items-per-row-section container position-relative mt-40">
        <div class="row">
            @if(!empty($contents['specific_banners']) and is_array($contents['specific_banners']))
                @foreach($contents['specific_banners'] as $bannerData)
                    @if(!empty($bannerData['image']))
                        <div class="col-12 col-md-4 mt-24">
                            <a href="{{ !empty($bannerData['url']) ? $bannerData['url'] : '' }}" target="_blank" class="">
                                <div class="banner-3-items-per-row-section__img-card {{ $useRounded ? 'rounded-24' : '' }}">
                                    <img src="{{ $bannerData['image'] }}" alt="image" class="img-cover {{ $useRounded ? 'rounded-24' : '' }}">
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
@endif
