@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("features_4x") }}">
    @endpush

    <div class="features-4x-section position-relative container">

        <div class="row h-100">
            <div class="col-12 col-lg-4 d-flex justify-content-center align-items-start flex-column">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="mt-12 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                    <p class="mt-20 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                @endif

                {{-- Checked Items --}}
                @if(!empty($contents['checked_items']) and is_array($contents['checked_items']))
                    @foreach($contents['checked_items'] as $checkedItem)
                        <div class="d-flex align-items-center gap-4 {{ $loop->first ? 'mt-24' : 'mt-12' }}">
                            <x-tick-icon class="icons text-success" width="16px" height="16px"/>
                            <span class="font-14 text-gray-500">{{ $checkedItem }}</span>
                        </div>
                    @endforeach
                @endif

                {{-- Button --}}
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                    <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white mt-24" data-text="{{ $contents['main_content']['button']['label'] }}">
                        @if(!empty($contents['main_content']['button']['icon']))
                            @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                        @endif

                        <span class="btn-flip-effect__text">{{ $contents['main_content']['button']['label'] }}</span>
                    </a>
                @endif
            </div>

            <div class="col-lg-2"></div>

            <div class="col-12 col-lg-6 mt-32 mt-lg-0">
                <div class="row">
                    @if(!empty($contents['features_cards']) and is_array($contents['features_cards']))
                        @php
                            $iconBgs = [
                                1 => 'bg-primary-20 text-primary',
                                2 => 'bg-accent-20 text-accent',
                                3 => 'bg-warning-20 text-warning',
                                4 => 'bg-success-20 text-success',
                            ]
                        @endphp

                        @foreach($contents['features_cards'] as $fKey => $featureCardData)
                            @if(!empty($featureCardData['title']) and $loop->iteration <= 4)
                                <div class="col-12 col-md-6 features-4x-section__card-item-{{ $loop->iteration }}">

                                    @if(!empty($featureCardData['url']))
                                        <a href="{{ $featureCardData['url'] }}">
                                            @endif


                                            <div class="features-4x-section__card-item position-relative">
                                                <div class="features-4x-section__card-item-mask"></div>
                                                <div class="position-relative z-index-2 bg-white rounded-24 p-24 w-100 h-100">
                                                    <div class="d-flex-center size-64 rounded-12 {{ !empty($iconBgs[$loop->iteration]) ? $iconBgs[$loop->iteration] : 'bg-primary-20 text-primary' }}">
                                                        @if(!empty($featureCardData['icon']))
                                                            @svg("iconsax-{$featureCardData['icon']}", ['width' => '32px', 'height' => '32px', 'class' => "icons"])
                                                        @endif
                                                    </div>

                                                    <h4 class="mt-24 font-24 text-dark">{{ $featureCardData['title'] }}</h4>

                                                    @if(!empty($featureCardData['description']))
                                                        <p class="mt-12 font-16 text-gray-500">{!! nl2br($featureCardData['description']) !!}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            @if(!empty($featureCardData['url']))
                                        </a>
                                    @endif

                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
