@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("information_card_full_width") }}">
    @endpush

    @if(!empty($contents))
        <div class="information-card-full-width-section position-relative">
            <div class="container bg-white rounded-32">
                <div class="row {{ (!empty($contents['reverse_direction']) and $contents['reverse_direction'] == "on") ? 'flex-row-reverse' : '' }}">

                    @if(!empty($contents['reverse_direction']) and $contents['reverse_direction'] == "on")
                        <div class="col-lg-1"></div>
                    @endif

                    <div class="col-12 col-lg-5 pt-24 py-lg-48 pl-lg-32">
                        <div class="d-flex align-items-start flex-column">
                            <div class="d-flex align-items-center gap-8">
                                @if(!empty($contents['main_content']['pre_title_icon']))
                                    <div class="d-flex-center size-64 bg-gray-100 rounded-circle">
                                        @svg("iconsax-{$contents['main_content']['pre_title_icon']}", ['width' => '32px', 'height' => '32px', 'class' => "icons text-primary"])
                                    </div>
                                @endif

                                @if(!empty($contents['main_content']) and !empty($contents['main_content']['badge_title']))
                                    <div class="information-card-full-width-section__badge-divider"></div>

                                    <div class="d-inline-flex-center py-8 px-16 rounded-32 bg-dark font-14 font-weight-bold text-white">{{ $contents['main_content']['badge_title'] }}</div>
                                @endif
                            </div>

                            @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                                <h2 class="mt-12 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                            @endif

                            @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                                <p class="mt-16 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                            @endif

                            {{-- Checked Items --}}
                            @if(!empty($contents['checked_items']) and is_array($contents['checked_items']))
                                <div class="row">
                                    @foreach($contents['checked_items'] as $checkedItem)
                                        <div class="col-6 {{ ($loop->iteration <= 2) ? 'mt-24' : 'mt-12' }}">
                                            <div class="d-flex align-items-center gap-4">
                                                <x-tick-icon class="icons text-success" width="16px" height="16px"/>
                                                <span class="font-14 text-gray-500">{{ $checkedItem }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Button --}}
                            @if(!empty($contents['main_content']) and !empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                                <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white mt-28" data-text="{{ $contents['main_content']['button']['label'] }}">
                                    @if(!empty($contents['main_content']['button']['icon']))
                                        @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                    @endif

                                    <span class="btn-flip-effect__text">{{ $contents['main_content']['button']['label'] }}</span>
                                </a>
                            @endif

                        </div>
                    </div>

                    @if(!empty($contents['reverse_direction']) and $contents['reverse_direction'] == "on")
                        <div class="col-lg-1"></div>
                    @else
                        <div class="col-lg-2"></div>
                    @endif

                    <div class="col-12 col-lg-5 mt-32 mt-lg-0 {{ (!empty($contents['reverse_direction']) and $contents['reverse_direction'] == "on") ? 'pl-lg-0' : 'pr-lg-0' }}">
                        @if(!empty($contents['images']))
                            <div class="information-card-full-width-section__images-box position-relative {{ (!empty($contents['component_image_style'])) ? $contents['component_image_style'] : '' }}">
                                <div class="information-card-full-width-section__main-img position-relative">
                                    @if(!empty($contents['images']['main_image']))
                                        <img src="{{ $contents['images']['main_image'] }}" alt="main_image" class="img-cover">
                                    @endif
                                </div>

                                @if(!empty($contents['images']['overlay_image']))
                                    <div class="information-card-full-width-section__overlay-img d-flex-center">
                                        <img src="{{ $contents['images']['overlay_image'] }}" alt="overlay_image" class="" width="208px" height="208px">
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                </div>


            </div>

        </div>
    @endif
@endif
