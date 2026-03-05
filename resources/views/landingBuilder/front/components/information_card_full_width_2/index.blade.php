@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("information_card_full_width_2") }}">
    @endpush

    <div class="information-card-full-width-2-section position-relative">
        <div class="container">
            <div class="row {{ (!empty($contents['reverse_direction']) and $contents['reverse_direction'] == "on") ? 'flex-row-reverse' : '' }}">
                <div class="col-12 col-lg-6">
                    <div class="d-flex align-items-start justify-content-center flex-column bg-white rounded-32 px-24 py-48 p-lg-48 h-100">
                        <div class="d-flex align-items-center gap-8">
                            @if(!empty($contents['main_content']['pre_title_icon']))
                                <div class="d-flex-center size-64 bg-gray-100 rounded-circle">
                                    @svg("iconsax-{$contents['main_content']['pre_title_icon']}", ['width' => '32px', 'height' => '32px', 'class' => "icons text-primary"])
                                </div>
                            @endif
                        </div>

                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                            <h2 class="mt-12 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                        @endif

                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                            <p class="mt-20 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                        @endif

                        {{-- Checked Items --}}
                        @if(!empty($contents['checked_items']) and is_array($contents['checked_items']))
                            <div class="d-flex align-items-center flex-wrap gap-12 mt-20">
                                @foreach($contents['checked_items'] as $checkedItem)
                                    <div class="d-flex text-center px-16 py-8 rounded-8 border-gray-300 bg-gray-100 font-14 text-gray-500">{{ $checkedItem }}</div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Button --}}
                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                            <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white mt-32" data-text="{{ $contents['main_content']['button']['label'] }}">
                                @if(!empty($contents['main_content']['button']['icon']))
                                    @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                <span class="btn-flip-effect__text">{{ $contents['main_content']['button']['label'] }}</span>
                            </a>
                        @endif

                    </div>
                </div>

                <div class="col-12 col-lg-6 mt-32 mt-lg-0">
                    <div class="information-card-full-width-2-section__main-img position-relative rounded-32">
                        @if(!empty($contents['images']) and !empty($contents['images']['main_image']))
                            <img src="{{ $contents['images']['main_image'] }}" alt="main_image" class="img-cover rounded-32" width="32px" height="32px">
                        @endif
                    </div>
                </div>
            </div>


        </div>

    </div>
@endif
