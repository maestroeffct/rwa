@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("banners_grid_3_in_different_sizes") }}">
    @endpush

    <div class="banners-grid-3-in-different-sizes-section position-relative container">
        <div class="row h-100">
            @if(!empty($contents['banner_1']) and !empty($contents['banner_1']['image']))
                <div class="col-12 col-lg-6">

                    @if(!empty($contents['banner_1']['url']))
                        <a href="{{ $contents['banner_1']['url'] }}">
                            @endif

                            <div class="banners-grid-3-in-different-sizes-section__banner-1 position-relative rounded-32">
                                <img src="{{ $contents['banner_1']['image'] }}" alt="banner_1" class="img-cover rounded-32">

                                <div class="banner-bottom-contents">
                                    <div class="position-relative d-flex flex-column justify-content-end p-24 z-index-2 w-100 h-100">
                                        @if(!empty($contents['banner_1']['title']))
                                            <h3 class="font-24 text-white">{{ $contents['banner_1']['title'] }}</h3>
                                        @endif

                                        @if(!empty($contents['banner_1']['subtitle']))
                                            <p class="mt-8 font-16 text-white opacity-70">{{ $contents['banner_1']['subtitle'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(!empty($contents['banner_1']['url']))
                        </a>
                    @endif

                </div>
            @endif

            <div class="col-12 col-lg-6 mt-24 mt-lg-0">
                <div class="row">

                    @if(!empty($contents['banner_2']) and !empty($contents['banner_2']['image']))
                        <div class="col-12">

                            @if(!empty($contents['banner_2']['url']))
                                <a href="{{ $contents['banner_2']['url'] }}">
                                    @endif


                                    <div class="banners-grid-3-in-different-sizes-section__banner-2 position-relative rounded-32">
                                        <img src="{{ $contents['banner_2']['image'] }}" alt="banner_2" class="img-cover rounded-32">

                                        <div class="banner-bottom-contents">
                                            <div class="position-relative d-flex flex-column justify-content-end p-24 z-index-2 w-100 h-100">
                                                @if(!empty($contents['banner_2']['title']))
                                                    <h3 class="font-24 text-white">{{ $contents['banner_2']['title'] }}</h3>
                                                @endif

                                                @if(!empty($contents['banner_2']['subtitle']))
                                                    <p class="mt-8 font-16 text-white opacity-70">{{ $contents['banner_2']['subtitle'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(!empty($contents['banner_2']['url']))
                                </a>
                            @endif

                        </div>
                    @endif

                    @if(!empty($contents['banner_3']) and !empty($contents['banner_3']['image']))
                        <div class="col-12 col-md-6 mt-24">
                            @if(!empty($contents['banner_3']['url']))
                                <a href="{{ $contents['banner_3']['url'] }}">
                                    @endif

                                    <div class="banners-grid-3-in-different-sizes-section__banner-3 position-relative rounded-32">
                                        <img src="{{ $contents['banner_3']['image'] }}" alt="banner_3" class="img-cover rounded-32">

                                        <div class="banner-bottom-contents">
                                            <div class="position-relative d-flex flex-column justify-content-end p-24 z-index-2 w-100 h-100">
                                                @if(!empty($contents['banner_3']['title']))
                                                    <h3 class="font-24 text-white">{{ $contents['banner_3']['title'] }}</h3>
                                                @endif

                                                @if(!empty($contents['banner_3']['subtitle']))
                                                    <p class="mt-8 font-16 text-white opacity-70">{{ $contents['banner_3']['subtitle'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(!empty($contents['banner_3']['url']))
                                </a>
                            @endif

                        </div>
                    @endif

                    {{-- cta_section --}}
                    @if(!empty($contents['cta_section']) and !empty($contents['cta_section']['title']))
                        <div class="col-12 col-md-6 mt-24">
                            @if(!empty($contents['cta_section']['url']))
                                <a href="{{ $contents['cta_section']['url'] }}">
                                    @endif

                                    <div class="banners-grid-3-in-different-sizes-section__cta-section position-relative rounded-32 bg-primary p-24" @if(!empty($contents['cta_section']['background'])) style="background-image: url({{ $contents['cta_section']['background'] }})" @endif>

                                        <h3 class="font-32 text-white">{{ $contents['cta_section']['title'] }}</h3>

                                        @if(!empty($contents['cta_section']['subtitle']))
                                            <p class="mt-8 font-16 text-white opacity-70">{{ $contents['cta_section']['subtitle'] }}</p>
                                        @endif

                                        <div class="d-flex justify-content-end mt-40">
                                            <div class="banners-grid-3-in-different-sizes-section__cta-section-arrow-icon d-flex-center size-48 rounded-circle border-gray-200">
                                                <x-iconsax-lin-arrow-right class="icons text-white" width="24px" height="24px"/>
                                            </div>
                                        </div>
                                    </div>


                                    @if(!empty($contents['cta_section']['url']))
                                </a>
                            @endif

                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
@endif
